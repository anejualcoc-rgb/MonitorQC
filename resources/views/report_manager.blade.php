@extends('layouts.app_manager')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color: #0C5A7D;">Export Laporan Produksi</h2>
            <p class="text-muted mb-0">Download data produksi dalam format Excel</p>
        </div>
        <div class="text-muted">
            <i class="bi bi-calendar3"></i> {{ date('d F Y') }}
        </div>
    </div>

    <div class="row g-4">
        <!-- Export Form Card -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="p-3 rounded-3 me-3" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                            <i class="bi bi-file-earmark-spreadsheet-fill text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold">Konfigurasi Export</h5>
                            <small class="text-muted">Pilih tipe dan periode laporan</small>
                        </div>
                    </div>

                    <form action="{{ route('export.manager') }}" method="GET">
                        @csrf
                        
                        <!-- Tipe Laporan -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-filter-circle me-2"></i>Tipe Laporan
                            </label>
                            <select class="form-select form-select-lg shadow-sm" name="type" id="filterType" onchange="toggleInput()" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                                <option value="daily">Harian</option>
                                <option value="monthly">Bulanan</option>
                                <option value="yearly">Tahunan</option>
                                <option value="all">Semua Data</option>
                            </select>
                        </div>

                        <!-- Input Harian -->
                        <div class="filter-input mb-4" id="input-daily">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-calendar-date me-2"></i>Pilih Tanggal
                            </label>
                            <input type="date" class="form-control form-control-lg shadow-sm" name="date" value="{{ date('Y-m-d') }}" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        </div>

                        <!-- Input Bulanan -->
                        <div class="filter-input mb-4 d-none" id="input-monthly">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-calendar-month me-2"></i>Pilih Bulan
                            </label>
                            <input type="month" class="form-control form-control-lg shadow-sm" name="month" value="{{ date('Y-m') }}" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                        </div>

                        <!-- Input Tahunan -->
                        <div class="filter-input mb-4 d-none" id="input-yearly">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-calendar4-range me-2"></i>Pilih Tahun
                            </label>
                            <select class="form-select form-select-lg shadow-sm" name="year" style="border-radius: 12px; border: 2px solid #e5e7eb;">
                                @for($i = date('Y'); $i >= 2020; $i--)
                                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Button Export -->
                        <button type="submit" class="btn btn-lg w-100 text-white shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border: none; border-radius: 12px; padding: 14px; transition: all 0.3s ease;">
                            <i class="bi bi-download me-2"></i>
                            <span class="fw-semibold">Download Laporan Excel</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-12">
                </div>
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-info-circle me-2" style="color: #3b82f6;"></i>Informasi Format
                            </h6>
                            <div class="mb-2">
                                <small class="text-muted d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill me-2 mt-1" style="color: #22c55e;"></i>
                                    <span>File akan diunduh dalam format <strong>.xlsx</strong></span>
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-flex align-items-start">
                                    <i class="bi bi-check-circle-fill me-2 mt-1" style="color: #22c55e;"></i>
                                    <span>Termasuk data produksi, target, dan cacat</span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                        <div class="card-body p-4 text-white">
                            <h6 class="fw-semibold mb-2">
                                <i class="bi bi-lightbulb me-2"></i>Tips
                            </h6>
                            <small class="opacity-90">
                                Gunakan export bulanan untuk analisis trend, dan export harian untuk monitoring detail per hari.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-select:focus,
    .form-control:focus {
        border-color: #22c55e !important;
        box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.15) !important;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(34, 197, 94, 0.3) !important;
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
    }
</style>

<script>
    function toggleInput() {
        const type = document.getElementById('filterType').value;
        
        // Sembunyikan semua input dulu
        document.querySelectorAll('.filter-input').forEach(el => {
            el.classList.add('d-none');
        });

        // Tampilkan yang sesuai dengan animasi
        if (type === 'daily') {
            document.getElementById('input-daily').classList.remove('d-none');
        } else if (type === 'monthly') {
            document.getElementById('input-monthly').classList.remove('d-none');
        } else if (type === 'yearly') {
            document.getElementById('input-yearly').classList.remove('d-none');
        }
    }

    // Hover effect untuk button
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('button[type="submit"]');
        if (btn) {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });
</script>
@endsection