@extends('layouts.main_layout')

@section('content')
<style>
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stat-card {
        border-radius: 12px;
        border: none;
        transition: all 0.3s ease;
    }
    .bg-light-primary-custom { background-color: #E1F0FF; }
    .bg-light-success-custom { background-color: #E8FFF3; }
    .bg-light-warning-custom { background-color: #FFF8DD; }
    
    .text-primary-custom { color: #009ef7; }
    .text-success-custom { color: #50cd89; }
    .text-warning-custom { color: #ffc700; }

    .icon-box {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    /* Clean up the date picker input */
    .form-control-solid-custom {
        background-color: #f5f8fa;
        border-color: #f5f8fa;
        color: #5e6278;
        transition: color 0.2s ease, background-color 0.2s ease;
    }
    .form-control-solid-custom:focus {
        background-color: #eef3f7;
        border-color: #eef3f7;
        color: #5e6278;
    }
</style>

@endsection

@section('script')
<script src="{{ asset('assets/js/dashboard/index.js') }}"></script>
@endsection
