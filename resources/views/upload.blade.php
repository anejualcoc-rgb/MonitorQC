@extends('layouts.app')

@section('content')
<style>
    .upload-wrapper {
        max-width: 500px;
        margin: 60px auto;
    }
    .upload-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-align: center;
    }
    .upload-card h3 {
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
    }
    .upload-card p {
        color: #777;
        margin-bottom: 20px;
        font-size: 14px;
    }
    .upload-form input[type="file"] {
        margin: 15px 0;
        display: block;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 8px;
        width: 100%;
    }
    .upload-btn {
        background-color: #015255ff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }
    .upload-btn:hover {
        background-color: #084298;
    }
    .icon-upload {
        font-size: 40px;
        color: #015255ff;
        margin-bottom: 10px;
    }
</style>

<div class="upload-wrapper">
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    <div class="upload-card">
        <div class="icon-upload">
            <i class="bi bi-cloud-upload-fill"></i>
        </div>
        <h3>Import Data Excel</h3>
        <p>Silakan upload file Excel (.xlsx, .xls, .csv) sesuai format template</p>

        <form class="upload-form" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
            <button type="submit" class="upload-btn">
                <i class="bi bi-upload"></i> Upload
            </button>
        </form>

         <form action="{{ route('delete.all') }}" method="POST" onsubmit="return confirm('Yakin ingin hapus SEMUA data? Tindakan ini tidak bisa dibatalkan!')">
            @csrf
            <button type="submit" class="upload-btn" style="background-color:#dc3545;margin-top:15px">
                <i class="bi bi-trash"></i> Hapus Semua Data
            </button>
        </form>
    </div>
</div>
@endsection
