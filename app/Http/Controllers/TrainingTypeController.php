<?php

namespace App\Http\Controllers;

use App\Models\Cabor;
use App\Models\TrainingType;
use App\Models\TrainingTypeComponent;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TrainingTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = TrainingType::with('cabor')->latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('cabor_name', fn($row) => $row->cabor?->name ?? '-')
                ->addColumn('components_count', fn($row) => $row->components()->count())
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 manageComponents" title="Manage Components"><i class="ki-duotone ki-setting-3 fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editTrainingType"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteTrainingType"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->orderBy('name')->get();
        return view('pages.training_type.index', compact('cabors'))
            ->with(['title' => 'Master Jenis Latihan', 'breadcrum' => ['Program Pembinaan Prestasi', 'Jenis Latihan']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        TrainingType::create($request->all());

        return response()->json(['success' => 'Jenis Latihan berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $type = TrainingType::find($id);
        if (!$type) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($type);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $type = TrainingType::find($id);
        if (!$type) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $type->update($request->all());

        return response()->json(['success' => 'Jenis Latihan berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $type = TrainingType::find($id);
        if (!$type) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $type->update(['is_active' => 0]);
        $type->delete();

        return response()->json(['success' => 'Jenis Latihan berhasil dinonaktifkan.']);
    }

    // ===== COMPONENT MANAGEMENT =====

    public function getComponents($typeId)
    {
        $components = TrainingTypeComponent::where('training_type_id', $typeId)->get();
        return response()->json($components);
    }

    public function storeComponent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'training_type_id' => 'required|exists:training_types,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        TrainingTypeComponent::create($request->all());

        return response()->json(['success' => 'Komponen Latihan berhasil ditambahkan.']);
    }

    public function destroyComponent($id)
    {
        $component = TrainingTypeComponent::find($id);
        if (!$component) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $component->delete();

        return response()->json(['success' => 'Komponen Latihan berhasil dihapus.']);
    }
}
