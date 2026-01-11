@extends('layouts.app')

@section('content')
<style>
    .upload-container {
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-header h2 {
        color: #333;
        font-weight: 700;
        font-size: 28px;
        margin-bottom: 8px;
    }
    
    .page-header p {
        color: #6c757d;
        font-size: 14px;
    }
    
    .upload-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 768px) {
        .upload-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .upload-card {
        background: #fff;
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .upload-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 28px;
    }
    
    .icon-upload-main {
        background: linear-gradient(135deg, #015255 0%, #028b8f 100%);
        color: #fff;
    }
    
    .icon-info {
        background: linear-gradient(135deg, #4dabf7 0%, #228be6 100%);
        color: #fff;
    }
    
    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 12px;
    }
    
    .card-description {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 25px;
    }
    
    .upload-form {
        margin-top: 25px;
    }
    
    .file-input-wrapper {
        position: relative;
        margin-bottom: 20px;
    }
    
    .file-input-wrapper input[type="file"] {
        width: 100%;
        padding: 15px;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
    }
    
    .file-input-wrapper input[type="file"]:hover {
        border-color: #015255;
        background: #e8f5f5;
    }
    
    .btn-primary-upload {
        background: linear-gradient(135deg, #015255 0%, #028b8f 100%);
        color: #fff;
        border: none;
        padding: 14px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .btn-primary-upload:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(1, 82, 85, 0.3);
    }
    
    .btn-danger-delete {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: #fff;
        border: none;
        padding: 14px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 15px;
    }
    
    .btn-danger-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
    }
    
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .info-list li {
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: #495057;
    }
    
    .info-list li:last-child {
        border-bottom: none;
    }
    
    .info-list i {
        color: #015255;
        font-size: 18px;
        width: 24px;
    }
    
    .format-badge {
        display: inline-block;
        background: #e8f5f5;
        color: #015255;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin: 0 3px;
    }
    
    .alert {
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 25px;
        border: none;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .alert-success {
        background: #d1f2eb;
        color: #0f5132;
    }
    
    .alert-danger {
        background: #f8d7da;
        color: #842029;
    }
    
    .alert i {
        font-size: 20px;
    }
    
    .date-info {
        background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        border-left: 4px solid #ffc107;
        padding: 15px 20px;
        border-radius: 10px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        color: #664d03;
    }
    
    .date-info i {
        font-size: 24px;
        color: #ffc107;
    }
    
    .date-info strong {
        color: #664d03;
    }
</style>

<div class="upload-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Import Data Produksi</h2>
        <p>Kelola data produksi Anda dengan mudah melalui import file Excel</p>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Upload Grid -->
    <div class="upload-grid">
        <!-- Upload Card -->
        <div class="upload-card">
            <div class="card-icon icon-upload-main">
                <i class="bi bi-cloud-upload-fill"></i>
            </div>
            <h3 class="card-title">Upload File Excel</h3>
            <p class="card-description">
                Pilih file Excel yang berisi data produksi untuk diimport ke dalam sistem
            </p>

            <form class="upload-form" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="file-input-wrapper">
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required>
                </div>
                <button type="submit" class="btn-primary-upload">
                    <i class="bi bi-upload"></i>
                    <span>Upload File</span>
                </button>
            </form>

            <form action="{{ route('delete.all') }}" method="POST" onsubmit="return confirm('Yakin ingin hapus SEMUA data? Tindakan ini tidak bisa dibatalkan!')">
                @csrf
                <button type="submit" class="btn-danger-delete">
                    <i class="bi bi-trash3-fill"></i>
                    <span>Hapus Semua Data</span>
                </button>
            </form>

            <div class="date-info">
                <i class="bi bi-calendar-check"></i>
                <div>
                    <strong>Hari ini:</strong> {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </div>
            </div>
        </div>

        <!-- Information Card -->
        <div class="upload-card">
            <div class="card-icon icon-info">
                <i class="bi bi-info-circle-fill"></i>
            </div>
            <h3 class="card-title">Informasi Format File</h3>
            <p class="card-description">
                Pastikan file yang Anda upload sesuai dengan format berikut
            </p>

            <ul class="info-list">
                <li>
                    <i class="bi bi-file-earmark-spreadsheet"></i>
                    <span>Format file: <span class="format-badge">.XLSX</span> <span class="format-badge">.XLS</span> <span class="format-badge">.CSV</span></span>
                </li>
                <li>
                    <i class="bi bi-list-columns"></i>
                    <span>Kolom wajib: Tanggal, Line, Shift, Produksi, Target, Cacat</span>
                </li>
                <li>
                    <i class="bi bi-calendar-range"></i>
                    <span>Format tanggal: YYYY-MM-DD atau DD/MM/YYYY</span>
                </li>
                <li>
                    <i class="bi bi-123"></i>
                    <span>Data numerik harus berupa angka tanpa karakter khusus</span>
                </li>
                <li>
                    <i class="bi bi-arrow-down-circle"></i>
                    <span>Baris pertama harus berisi nama kolom (header)</span>
                </li>
                <li>
                    <i class="bi bi-exclamation-circle"></i>
                    <span>Pastikan tidak ada baris kosong di tengah data</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection