@extends('layouts.main_layout')

@section('content')
<div class="row g-5 g-xl-8">
    <div class="col-xl-12">
        <!-- Card Filter -->
        <div class="card mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-4 text-gray-900">Filter Pencarian</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Saring data program latihan berdasarkan kriteria</span>
                </h3>
            </div>
            <div class="card-body pb-10 pt-0">
                <div class="row g-8">
                    <div class="col-md-3">
                        <label class="form-label fs-7 fw-bold text-gray-700">Cabang Olahraga:</label>
                        <select class="form-select form-select-solid" id="filter_cabor_id" data-control="select2" data-placeholder="Semua Cabor" data-allow-clear="true">
                            <option></option>
                            @foreach($cabors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7 fw-bold text-gray-700">Nama Atlet:</label>
                        <select class="form-select form-select-solid" id="filter_atlet_id" data-control="select2" data-placeholder="Pilih Atlet" data-allow-clear="true">
                            <option></option>
                            @foreach($atlets as $a)
                            <option value="{{ $a->id }}" data-cabor="{{ $a->cabor_id }}">{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fs-7 fw-bold text-gray-700">Periode Latihan:</label>
                        <select class="form-select form-select-solid" id="filter_periode" data-control="select2" data-placeholder="Semua Periode" data-allow-clear="true">
                            <option></option>
                            <option value="harian">Harian</option>
                            <option value="mingguan">Mingguan</option>
                            <option value="bulanan">Bulanan</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-light-danger w-100" id="btnResetFilter">
                            <i class="ki-duotone ki-arrows-loop fs-2"><span class="path1"></span><span class="path2"></span></i>Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card card-xl-stretch mb-5 mb-xl-8 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 text-gray-900">Program Latihan</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Manajemen Program Latihan Atlet NPCI</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-primary" id="btnTambahPembinaan" data-bs-toggle="modal" data-bs-target="#kt_modal_pembinaan">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Program
                    </a>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_pembinaan">
                        <thead>
                            <tr class="fw-bold text-muted bg-light">
                                <th class="ps-4 min-w-50px rounded-start">No</th>
                                <th class="min-w-100px">Tanggal</th>
                                <th class="min-w-150px">Nama Atlet</th>
                                <th class="min-w-100px">Cabor</th>
                                <th class="min-w-100px">Periodisitas</th>
                                <th class="min-w-100px">Intensitas</th>
                                <th class="min-w-100px">Jenis Latihan</th>
                                <th class="min-w-150px">Komponen Latihan</th>
                                <th class="min-w-100px text-center rounded-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('pages.pembinaan-prestasi.form_modal')

@endsection

@section('script')
<script>
    window.routes = {
        pembinaanIndex: "{{ route('pembinaan-prestasi.index') }}",
        pembinaanStore: "{{ route('pembinaan-prestasi.store') }}",
        trainingData: "{{ route('pembinaan-prestasi.training-data') }}",
        components: "{{ route('pembinaan-prestasi.components') }}"
    };
    var csrf_token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script src="{{ asset('js/pages/pembinaan-prestasi.js') }}"></script>
@endsection
