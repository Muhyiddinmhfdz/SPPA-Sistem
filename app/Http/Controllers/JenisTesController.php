<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhysicalTestCategory;
use App\Models\PhysicalTestItem;
use App\Models\PhysicalTestItemScore;
use App\Models\Cabor;
use App\Models\JenisDisabilitas;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class JenisTesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = PhysicalTestCategory::with(['items', 'cabor'])->withCount('items')->where('is_active', 1);
            if ($request->filled('cabor_id')) {
                $data->where('cabor_id', $request->cabor_id);
            }
            $data = $data->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('cabor_name', function ($row) {
                    return $row->cabor ? $row->cabor->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center flex-shrink-0">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-name="' . $row->name . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 viewCategory" title="Detail Kategori">
                                <i class="ki-duotone ki-eye fs-2">
                                    <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                                </i>
                             </a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-name="' . $row->name . '" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1 manageItems" title="Kelola Item Tes">
                                <i class="ki-duotone ki-setting-2 fs-2">
                                    <span class="path1"></span><span class="path2"></span>
                                </i>
                             </a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $cabors = Cabor::where('is_active', 1)->get();
        $disabilitas = JenisDisabilitas::where('is_active', 1)->get();
        return view('pages.jenis_tes.index')
            ->with([
                'title' => 'Master Komponen Tes Fisik',
                'breadcrum' => ['Tes & Evaluasi', 'Parameter / Jenis Tes'],
                'cabors' => $cabors,
                'disabilitas' => $disabilitas
            ]);
    }

    public function getDetail($id)
    {
        $category = PhysicalTestCategory::with([
            'cabor',
            'items' => function ($q) {
                $q->where('is_active', 1)->orderBy('id', 'asc');
            },
            'items.jenisDisabilitas',
            'items.scores' => function ($q) {
                $q->where('is_active', 1)->orderBy('score', 'desc');
            }
        ])->findOrFail($id);

        return response()->json(['data' => $category]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cabor_id' => 'required|exists:cabors,id',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            PhysicalTestCategory::updateOrCreate(
                ['id' => $request->id],
                [
                    'cabor_id' => $request->cabor_id,
                    'name' => $request->name,
                    'is_active' => 1
                ]
            );

            return response()->json(['success' => 'Kategori Tes berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan kategori tes.'], 500);
        }
    }

    // --- ITEM TES ---
    public function getItems($categoryId)
    {
        $items = PhysicalTestItem::with('jenisDisabilitas')
            ->where('physical_test_category_id', $categoryId)
            ->where('is_active', 1)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json(['items' => $items]);
    }

    public function storeItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'physical_test_category_id' => 'required|exists:physical_test_categories,id',
            'name' => 'required|string|max:255',
            'jenis_disabilitas_id' => 'nullable|exists:jenis_disabilitas,id',
            'satuan' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            PhysicalTestItem::create([
                'physical_test_category_id' => $request->physical_test_category_id,
                'name' => $request->name,
                'jenis_disabilitas_id' => $request->jenis_disabilitas_id,
                'satuan' => $request->satuan,
                'is_active' => 1
            ]);
            return response()->json(['success' => 'Item Tes berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan item: ' . $e->getMessage()], 500);
        }
    }

    public function destroyItem($id)
    {
        try {
            $item = PhysicalTestItem::findOrFail($id);
            $item->update(['is_active' => 0]);
            $item->delete();
            return response()->json(['success' => 'Item Tes berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus item.'], 500);
        }
    }

    // --- SCORES / KRITERIA ---
    public function getScores($itemId)
    {
        $scores = PhysicalTestItemScore::where('physical_test_item_id', $itemId)
            ->where('is_active', 1)
            ->orderBy('score', 'desc')
            ->get();
        return response()->json(['scores' => $scores]);
    }

    public function storeScore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'physical_test_item_id' => 'required|exists:physical_test_items,id',
            'label' => 'required|string|max:255',
            'score' => 'required|numeric',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            PhysicalTestItemScore::create([
                'physical_test_item_id' => $request->physical_test_item_id,
                'min_value' => $request->min_value,
                'max_value' => $request->max_value,
                'label' => $request->label,
                'score' => $request->score,
                'is_active' => 1
            ]);
            return response()->json(['success' => 'Kriteria Penilaian berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menambahkan kriteria: ' . $e->getMessage()], 500);
        }
    }

    public function destroyScore($id)
    {
        try {
            $score = PhysicalTestItemScore::findOrFail($id);
            $score->update(['is_active' => 0]);
            $score->delete();
            return response()->json(['success' => 'Kriteria Penilaian berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus kriteria.'], 500);
        }
    }
}
