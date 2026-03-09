<?php

namespace App\Http\Controllers;

use App\Models\PembinaanPrestasi;
use App\Models\Atlet;
use App\Models\Cabor;
use App\Models\TrainingType;
use App\Models\TrainingTypeComponent;
use App\Models\TrainingTypeComponentScore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PembinaanPrestasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PembinaanPrestasi::with(['atlet.cabor', 'details.training_type_component.trainingType'])->latest();

            if ($request->has('cabor_id') && $request->cabor_id != '') {
                $data->whereHas('atlet', function ($q) use ($request) {
                    $q->where('cabor_id', $request->cabor_id);
                });
            }

            if ($request->has('atlet_id') && $request->atlet_id != '') {
                $data->where('atlet_id', $request->atlet_id);
            }

            if ($request->has('periodesasi_latihan') && $request->periodesasi_latihan != '') {
                $data->where('periodesasi_latihan', $request->periodesasi_latihan);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('atlet_name', function ($row) {
                    return $row->atlet ? $row->atlet->name : '-';
                })
                ->addColumn('tanggal', function ($row) {
                    return $row->tanggal ? $row->tanggal->format('d-m-Y') : '-';
                })
                ->addColumn('cabor_name', function ($row) {
                    return $row->atlet && $row->atlet->cabor ? $row->atlet->cabor->name : '-';
                })
                ->addColumn('training_type', function ($row) {
                    if ($row->details->isEmpty()) return '-';
                    return $row->details->map(function ($d) {
                        return $d->training_type_component && $d->training_type_component->trainingType
                            ? $d->training_type_component->trainingType->name
                            : '-';
                    })->unique()->implode(', ');
                })
                ->addColumn('training_component', function ($row) {
                    if ($row->details->isEmpty()) return '-';
                    return $row->details->map(function ($d) {
                        $compName = $d->training_type_component ? $d->training_type_component->name : '-';
                        return $compName . ': ' . ($d->value ?? '-');
                    })->implode(', ');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editPembinaan" title="Edit">
                                <i class="ki-duotone ki-pencil fs-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deletePembinaan" title="Hapus">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span>
                                </i>
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $atlets = Atlet::orderBy('name', 'asc')->get();
        $cabors = Cabor::where('is_active', 1)->orderBy('name', 'asc')->get();
        return view('pages.pembinaan-prestasi.index', compact('atlets', 'cabors'))
            ->with(['title' => 'Pembinaan Prestasi', 'breadcrum' => ['Kegiatan', 'Pembinaan Prestasi']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'atlet_id' => 'required|exists:atlets,id',
            'periodesasi_latihan' => 'required|string',
            'intensitas_latihan' => 'required|string',
            'components' => 'required|array',
            'target_performa' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pembinaan = PembinaanPrestasi::create([
                'atlet_id' => $request->atlet_id,
                'tanggal' => $request->tanggal,
                'periodesasi_latihan' => $request->periodesasi_latihan,
                'intensitas_latihan' => $request->intensitas_latihan,
                'target_performa' => $request->target_performa,
            ]);

            foreach ($request->components as $componentId => $value) {
                if ($value !== null && $value !== '') {
                    $pembinaan->details()->create([
                        'training_type_component_id' => $componentId,
                        'value' => $value,
                        'score' => $this->calculateScore($componentId, $value)
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data Pembinaan Prestasi berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pembinaan = PembinaanPrestasi::with(['atlet.cabor', 'details'])->findOrFail($id);
        $atlet = $pembinaan->atlet;

        $types = TrainingType::with('components.scores')
            ->where('cabor_id', $atlet->cabor_id)
            ->get();

        $values = $pembinaan->details->pluck('value', 'training_type_component_id');

        return response()->json([
            'pembinaan' => $pembinaan,
            'types' => $types,
            'values' => $values,
            'klasifikasi' => $atlet->klasifikasi_disabilitas ? $atlet->klasifikasi_disabilitas->kode_klasifikasi . ' - ' . $atlet->klasifikasi_disabilitas->nama_klasifikasi : '-',
            'jenis_disabilitas' => $atlet->jenisDisabilitas ? $atlet->jenisDisabilitas->name : '-',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pembinaan = PembinaanPrestasi::findOrFail($id);
        $request->validate([
            'atlet_id' => 'required|exists:atlets,id',
            'periodesasi_latihan' => 'required|string',
            'intensitas_latihan' => 'required|string',
            'components' => 'required|array',
            'target_performa' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $pembinaan->update([
                'atlet_id' => $request->atlet_id,
                'tanggal' => $request->tanggal,
                'periodesasi_latihan' => $request->periodesasi_latihan,
                'intensitas_latihan' => $request->intensitas_latihan,
                'target_performa' => $request->target_performa,
            ]);

            $pembinaan->details()->delete();
            foreach ($request->components as $componentId => $value) {
                if ($value !== null && $value !== '') {
                    $pembinaan->details()->create([
                        'training_type_component_id' => $componentId,
                        'value' => $value,
                        'score' => $this->calculateScore($componentId, $value)
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => 'Data Pembinaan Prestasi berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Gagal memperbarui data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pembinaan = PembinaanPrestasi::findOrFail($id);
            $pembinaan->delete();
            return response()->json(['success' => 'Data Pembinaan Prestasi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get training types and components based on athlete's cabor.
     */
    public function getTrainingData(Request $request)
    {
        $atlet = Atlet::with(['cabor', 'klasifikasi_disabilitas', 'jenisDisabilitas'])->find($request->atlet_id);
        if (!$atlet) {
            return response()->json(['error' => 'Atlet tidak ditemukan.'], 404);
        }

        $types = TrainingType::with('components.scores')
            ->where('cabor_id', $atlet->cabor_id)
            ->get();

        return response()->json([
            'atlet' => $atlet,
            'cabor_name' => $atlet->cabor ? $atlet->cabor->name : '-',
            'jenis_disabilitas' => $atlet->jenisDisabilitas ? $atlet->jenisDisabilitas->name : '-',
            'klasifikasi' => $atlet->klasifikasi_disabilitas ? $atlet->klasifikasi_disabilitas->kode_klasifikasi . ' - ' . $atlet->klasifikasi_disabilitas->nama_klasifikasi : '-',
            'types' => $types
        ]);
    }

    /**
     * Get components based on training type.
     */
    public function getComponents(Request $request)
    {
        $components = TrainingTypeComponent::where('training_type_id', $request->training_type_id)->get();
        return response()->json($components);
    }

    /**
     * Helper to calculate score based on value and component criteria.
     */
    private function calculateScore($componentId, $value)
    {
        if ($value === null || $value === '') return null;

        $numericValue = is_numeric($value) ? (float)$value : 0;

        $criteria = TrainingTypeComponentScore::where('training_type_component_id', $componentId)
            ->orderBy('score', 'desc')
            ->get();

        foreach ($criteria as $criterion) {
            $min = $criterion->min_value;
            $max = $criterion->max_value;

            if ($min !== null && $max !== null) {
                if ($numericValue >= $min && $numericValue <= $max) return $criterion->score;
            } elseif ($min !== null) {
                if ($numericValue >= $min) return $criterion->score;
            } elseif ($max !== null) {
                if ($numericValue <= $max) return $criterion->score;
            }
        }

        return null;
    }
}
