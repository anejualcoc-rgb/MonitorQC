@extends('layouts.app')

@section('content')
<style>
    .upload-wrapper {
        max-width: 500px;
        margin: 60px auto;
        padding: 0 1rem;
    }
    
    .upload-card {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .upload-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    
    .upload-card h3 {
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
        font-size: 1.5rem;
    }
    
    .upload-card p {
        color: #777;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .upload-form input[type="file"] {
        margin: 15px 0;
        display: block;
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 20px;
        width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f9fafb;
        box-sizing: border-box;
    }
    
    .upload-form input[type="file"]:hover {
        border-color: #015255ff;
        background: #fff;
    }
    
    .upload-form input[type="file"]:focus {
        outline: none;
        border-color: #015255ff;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(1, 82, 85, 0.1);
    }
    
    .upload-btn {
        background-color: #015255ff;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .upload-btn:hover {
        background-color: #084298;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .upload-btn:active {
        transform: translateY(0);
    }
    
    .upload-btn.delete-btn {
        background-color: #dc3545;
        margin-top: 15px;
    }
    
    .upload-btn.delete-btn:hover {
        background-color: #c82333;
    }
    
    .icon-upload {
        font-size: 48px;
        color: #015255ff;
        margin-bottom: 15px;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .file-input-wrapper {
        position: relative;
        overflow: hidden;
        display: inline-block;
        width: 100%;
    }
    
    @media (max-width: 768px) {
        .upload-wrapper {
            margin: 40px auto;
            padding: 0 1rem;
        }
        
        .upload-card {
            padding: 25px 20px;
            border-radius: 10px;
        }
        
        .upload-card h3 {
            font-size: 1.25rem;
            margin-bottom: 12px;
        }
        
        .upload-card p {
            font-size: 13px;
            margin-bottom: 18px;
        }
        
        .icon-upload {
            font-size: 40px;
            margin-bottom: 12px;
        }
        
        .upload-form input[type="file"] {
            padding: 16px;
            margin: 12px 0;
            font-size: 14px;
        }
        
        .upload-btn {
            padding: 11px 20px;
            font-size: 0.9375rem;
        }
    }
    
    @media (max-width: 480px) {
        .upload-wrapper {
            margin: 30px auto;
            padding: 0 0.75rem;
        }
        
        .upload-card {
            padding: 20px 16px;
            border-radius: 8px;
        }
        
        .upload-card h3 {
            font-size: 1.125rem;
            margin-bottom: 10px;
        }
        
        .upload-card p {
            font-size: 12px;
            margin-bottom: 16px;
            line-height: 1.4;
        }
        
        .icon-upload {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .upload-form input[type="file"] {
            padding: 14px;
            margin: 10px 0;
            font-size: 13px;
            border-width: 2px;
        }
        
        .upload-btn {
            padding: 10px 18px;
            font-size: 0.875rem;
            gap: 6px;
        }
        
        .upload-btn i {
            font-size: 14px;
        }
        
        .alert {
            padding: 10px 16px;
            font-size: 13px;
            margin-bottom: 16px;
        }
    }
    
    @media (max-width: 360px) {
        .upload-wrapper {
            margin: 20px auto;
            padding: 0 0.5rem;
        }
        
        .upload-card {
            padding: 16px 12px;
        }
        
        .upload-card h3 {
            font-size: 1rem;
        }
        
        .upload-card p {
            font-size: 11px;
        }
        
        .icon-upload {
            font-size: 32px;
        }
        
        .upload-form input[type="file"] {
            padding: 12px;
            font-size: 12px;
        }
        
        .upload-btn {
            padding: 9px 16px;
            font-size: 0.8125rem;
        }
    }
    
    * {
        box-sizing: border-box;
    }
    
    body {
        overflow-x: hidden;
    }
    
    @media (max-width: 768px) {
        .upload-btn,
        .upload-form input[type="file"] {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }
    }
    
    .upload-form input[type="file"]::file-selector-button {
        display: none;
    }
    
    .upload-form input[type="file"]::before {
        content: '📁 Pilih File';
        display: inline-block;
        background: #015255ff;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        margin-right: 10px;
    }
    
    .upload-form input[type="file"]:hover::before {
        background: #084298;
    }
    
    @media (max-width: 480px) {
        .upload-form input[type="file"]::before {
            padding: 6px 12px;
            font-size: 12px;
            margin-right: 8px;
        }
    }
</style>

<div class="upload-wrapper">
    @if (session('success'))
        <div class="alert alert-success text-center">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        </div>
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
            <button type="submit" class="upload-btn delete-btn">
                <i class="bi bi-trash"></i> Hapus Semua Data
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            alert.style.transition = 'all 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    document.querySelector('input[type="file"]').addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const fileName = e.target.files[0].name;
            console.log('File selected:', fileName);
        }
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.upload-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.style.cursor = 'not-allowed';
        });
    });
</script>
@endpush
@endsection