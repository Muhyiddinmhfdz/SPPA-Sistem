<?php

namespace App\Http\Controllers;

use App\Models\Cabor;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CaborController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Cabor::latest();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('file_sk', function ($row) {
                    if ($row->sk_file_path) {
                        return '<a href="' . asset($row->sk_file_path) . '" target="_blank" class="btn btn-sm btn-light-success px-2 py-1"><i class="ki-duotone ki-file-down fs-3"><span class="path1"></span><span class="path2"></span></i> Download</a>';
                    }
                    return '<span class="badge badge-light-danger">Tidak Ada SK</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="d-flex justify-content-center">';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 editCabor"><i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i></a>';
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm deleteCabor"><i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></a>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['file_sk', 'action'])
                ->make(true);
        }

        return view('pages.cabor.index')->with(['title' => 'Data Cabang Olahraga', 'breadcrum' => ['Master Data', 'Cabang Olahraga']]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sk_start_date' => 'nullable|date',
            'sk_end_date' => 'nullable|date',
            'chairman_name' => 'nullable|string|max:255',
            'secretary_name' => 'nullable|string|max:255',
            'treasurer_name' => 'nullable|string|max:255',
            'secretariat_address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'npwp' => 'nullable|string|max:255',
            'active_athletes_count' => 'nullable|integer',
            'active_coaches_count' => 'nullable|integer',
            'active_medics_count' => 'nullable|integer',
            'sk_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $data = $request->except(['sk_file']);

        if ($request->hasFile('sk_file')) {
            $file = $request->file('sk_file');
            $filename = time() . '_' . str_replace(" ", "_", $file->getClientOriginalName());
            $file->move(public_path('uploads/cabor_sk'), $filename);
            $data['sk_file_path'] = 'uploads/cabor_sk/' . $filename;
        }

        Cabor::create($data);

        return response()->json(['success' => 'Cabang Olahraga berhasil ditambahkan.']);
    }

    public function edit($id)
    {
        $cabor = Cabor::find($id);
        if (!$cabor) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json($cabor);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'sk_start_date' => 'nullable|date',
            'sk_end_date' => 'nullable|date',
            'chairman_name' => 'nullable|string|max:255',
            'secretary_name' => 'nullable|string|max:255',
            'treasurer_name' => 'nullable|string|max:255',
            'secretariat_address' => 'nullable|string',
            'phone_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'npwp' => 'nullable|string|max:255',
            'active_athletes_count' => 'nullable|integer',
            'active_coaches_count' => 'nullable|integer',
            'active_medics_count' => 'nullable|integer',
            'sk_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $cabor = Cabor::find($id);
        if (!$cabor) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        $data = $request->except(['sk_file', '_method']);

        if ($request->hasFile('sk_file')) {
            // Remove old file
            if ($cabor->sk_file_path && file_exists(public_path($cabor->sk_file_path))) {
                unlink(public_path($cabor->sk_file_path));
            }

            $file = $request->file('sk_file');
            $filename = time() . '_' . str_replace(" ", "_", $file->getClientOriginalName());
            $file->move(public_path('uploads/cabor_sk'), $filename);
            $data['sk_file_path'] = 'uploads/cabor_sk/' . $filename;
        }

        $cabor->update($data);

        return response()->json(['success' => 'Cabang Olahraga berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $cabor = Cabor::find($id);
        if (!$cabor) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        if ($cabor->sk_file_path && file_exists(public_path($cabor->sk_file_path))) {
            unlink(public_path($cabor->sk_file_path));
        }

        $cabor->delete();

        return response()->json(['success' => 'Cabang Olahraga berhasil dihapus.']);
    }
}
