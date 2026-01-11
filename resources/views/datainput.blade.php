@extends('layouts.app')

@section('title', 'Input Data Produksi')

@section('content')
    @if(session('success'))
        <div class="alert-modern success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-modern error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-modern error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                <strong>Terdapat kesalahan:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h2>Input Data Produksi</h2>
                <p class="header-subtitle">Kelola data produksi dan defect dengan mudah</p>
            </div>
            <div class="date-badge">
                <i class="bi bi-calendar3"></i>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-blue: #4f46e5;
            --primary-blue-light: #6366f1;
            --primary-blue-dark: #4338ca;
            --primary-red: #ef4444;
            --primary-red-light: #f87171;
            --primary-red-dark: #dc2626;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --bg-gradient-red: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
            background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
        }

        .page-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .header-text h2 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .header-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0.5rem 0 0 0;
            font-weight: 500;
        }

        .date-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            box-shadow: var(--shadow-md);
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
        }

        .date-badge i {
            color: var(--primary-blue);
            font-size: 1.1rem;
        }

        .input-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 500px), 1fr));
            gap: 2rem;
            padding: 0 1.5rem 5rem 1.5rem;
        }

        .input-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border: 1px solid #e2e8f0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .input-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: all 0.3s ease;
        }

        .input-card.blue::before {
            background: var(--bg-gradient);
        }

        .input-card.red::before {
            background: var(--bg-gradient-red);
        }

        .input-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-4px);
            border-color: #cbd5e1;
        }

        .card-header-input {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
        }

        .card-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            flex-shrink: 0;
            position: relative;
            box-shadow: var(--shadow-md);
        }

        .card-icon.blue {
            background: var(--bg-gradient);
            color: white;
        }

        .card-icon.red {
            background: var(--bg-gradient-red);
            color: white;
        }

        .card-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 18px;
            padding: 2px;
            background: inherit;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0.3;
        }

        .card-title-section {
            flex: 1;
            min-width: 0;
        }

        .card-title-section h3 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.01em;
        }

        .card-title-section p {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0.375rem 0 0 0;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            letter-spacing: 0.01em;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: #fafafa;
            box-sizing: border-box;
            color: #1e293b;
            font-weight: 500;
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-control:hover:not(:focus) {
            border-color: #cbd5e1;
            background: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
            gap: 1.25rem;
        }

        .submit-btn {
            width: 100%;
            padding: 1.125rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            letter-spacing: 0.02em;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .submit-btn:hover::before {
            transform: translateX(100%);
        }

        .submit-btn.blue {
            background: var(--bg-gradient);
            color: white;
        }

        .submit-btn.red {
            background: var(--bg-gradient-red);
            color: white;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .submit-btn:active {
            transform: translateY(0);
            box-shadow: var(--shadow-md);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .submit-btn i {
            font-size: 1.125rem;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 14px;
            padding-right: 3rem;
            cursor: pointer;
        }

        .form-hint {
            font-size: 0.8125rem;
            color: #94a3b8;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-weight: 500;
        }

        .form-hint::before {
            content: '💡';
            font-size: 0.875rem;
        }

        .mt-2 {
            margin-top: 0.75rem !important;
        }

        /* Alert Styles */
        .alert-modern {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.875rem;
            font-weight: 500;
            box-shadow: var(--shadow-md);
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-modern.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-modern.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .alert-modern i {
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        /* Tablet */
        @media (max-width: 1024px) {
            .input-grid {
                gap: 1.5rem;
            }

            .input-card {
                padding: 1.75rem;
            }

            .card-icon {
                width: 52px;
                height: 52px;
                font-size: 24px;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .page-header {
                padding: 0 1rem;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-text h2 {
                font-size: 1.75rem;
            }

            .header-subtitle {
                font-size: 0.875rem;
            }

            .date-badge {
                align-self: stretch;
                justify-content: center;
            }

            .input-grid {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                padding: 0 1rem 5rem 1rem;
            }

            .input-card {
                padding: 1.5rem;
                border-radius: 16px;
            }

            .card-header-input {
                gap: 1rem;
                margin-bottom: 1.5rem;
                padding-bottom: 1.25rem;
            }

            .card-icon {
                width: 48px;
                height: 48px;
                font-size: 22px;
            }

            .card-title-section h3 {
                font-size: 1.25rem;
            }

            .card-title-section p {
                font-size: 0.8125rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-control {
                padding: 0.75rem 1rem;
                font-size: 0.9375rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .submit-btn {
                padding: 1rem 1.25rem;
                font-size: 0.9375rem;
            }

            .alert-modern {
                margin: 1rem;
                padding: 0.875rem 1rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .header-text h2 {
                font-size: 1.5rem;
            }

            .input-card {
                padding: 1.25rem;
            }

            .card-header-input {
                flex-direction: row;
                align-items: center;
            }

            .card-icon {
                width: 44px;
                height: 44px;
                font-size: 20px;
            }

            .card-title-section h3 {
                font-size: 1.125rem;
            }

            .form-control {
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
            }

            select.form-control {
                background-position: right 0.75rem center;
                padding-right: 2.5rem;
            }
        }

        /* Touch-friendly inputs on mobile */
        @media (max-width: 768px) {
            .form-control,
            .submit-btn {
                -webkit-tap-highlight-color: transparent;
                touch-action: manipulation;
            }

            input[type="number"]::-webkit-inner-spin-button,
            input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            input[type="number"] {
                -moz-appearance: textfield;
            }
        }

        /* Safe area for mobile devices */
        @media (max-width: 768px) {
            .input-grid {
                padding-bottom: calc(5rem + env(safe-area-inset-bottom));
            }
        }
    </style>

    <div class="input-grid">
        <div class="input-card blue">
            <div class="card-header-input">
                <div class="card-icon blue">
                    <i class="bi bi-boxes"></i>
                </div>
                <div class="card-title-section">
                    <h3>Input Data Produksi</h3>
                    <p>Masukkan data hasil produksi harian</p>
                </div>
            </div>

            <form action="{{ route('data.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person-fill"></i> User
                    </label>
                    <input type="text" name="User" class="form-control" placeholder="Masukkan nama user" value="{{ old('User') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calendar-event"></i> Tanggal Produksi
                        </label>
                        <input type="date" name="Tanggal_Produksi" class="form-control" value="{{ old('Tanggal_Produksi', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-clock-history"></i> Shift Produksi
                        </label>
                        <select name="Shift_Produksi" class="form-control" required>
                            <option value="">Pilih Shift</option>
                            <option value="Shift 1" {{ old('Shift_Produksi') == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                            <option value="Shift 2" {{ old('Shift_Produksi') == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-diagram-3"></i> Line Produksi
                    </label>
                    <select name="Line_Produksi" class="form-control" required>
                        <option value="">Pilih Line</option>
                        <option value="Line 1" {{ old('Line_Produksi') == 'Line 1' ? 'selected' : '' }}>Line 1</option>
                        <option value="Line 2" {{ old('Line_Produksi') == 'Line 2' ? 'selected' : '' }}>Line 2</option>
                        <option value="Line 3" {{ old('Line_Produksi') == 'Line 3' ? 'selected' : '' }}>Line 3</option>
                        <option value="Line 4" {{ old('Line_Produksi') == 'Line 4' ? 'selected' : '' }}>Line 4</option>
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-box-seam"></i> Jumlah Produksi
                        </label>
                        <input type="number" name="Jumlah_Produksi" class="form-control" placeholder="0" value="{{ old('Jumlah_Produksi') }}" min="0" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-bullseye"></i> Target Produksi
                        </label>
                        <input type="number" name="Target_Produksi" class="form-control" placeholder="0" value="{{ old('Target_Produksi') }}" min="0" required>
                    </div>
                </div>

                <button type="submit" class="submit-btn blue">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Simpan Data Produksi</span>
                </button>
            </form>
        </div>

        <div class="input-card red">
            <div class="card-header-input">
                <div class="card-icon red">
                    <i class="bi bi-bug-fill"></i>
                </div>
                <div class="card-title-section">
                    <h3>Input Data Defect</h3>
                    <p>Catat jenis dan jumlah defect yang terjadi</p>
                </div>
            </div>

            <form action="{{ route('defect.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-link-45deg"></i> Pilih Data Produksi
                    </label>
                    <select name="data_produksi_id" class="form-control" required>
                        <option value="">-- Pilih Produksi --</option>
                        @foreach($produksiList as $produksi)
                            <option value="{{ $produksi->id }}">
                                {{ $produksi->Tanggal_Produksi }} - {{ $produksi->User }} (Line {{ $produksi->Line_Produksi }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-event"></i> Tanggal Produksi
                    </label>
                    <input type="date" name="Tanggal_Produksi" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-tag"></i> Model Sepatu
                    </label>
                    <input type="text" name="Nama_Barang" class="form-control" placeholder="Masukkan Model Sepatu" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-exclamation-diamond"></i> Jenis Defect
                    </label>
                    <select id="jenis_defect" name="Jenis_Defect" class="form-control" required>
                        <option value="">-- Pilih Jenis Defect --</option>
                        <option value="Bonding Gap">Bonding Gap</option>
                        <option value="Over Cementing">Over Cementing</option>
                        <option value="Thread Ends">Thread Ends</option>
                        <option value="Dirty/Stain">Dirty / Stain</option>
                        <option value="Off Center">Off Center</option>
                        <option value="Lainnya">Lainnya...</option>
                    </select>

                    <input type="text" id="jenis_defect_lainnya" name="Jenis_Defect_Lainnya"
                        class="form-control mt-2" placeholder="Masukkan jenis defect lainnya"
                        style="display:none;">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-calculator"></i> Jumlah Cacat Per Jenis
                        </label>
                        <input type="number" name="Jumlah_Cacat_perjenis" class="form-control" placeholder="0" min="1" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-flag-fill"></i> Severity
                        </label>
                        <select name="Severity" class="form-control" required>
                            <option value="">-- Pilih Tingkat Keparahan --</option>
                            <option value="Minor">Minor</option>
                            <option value="Major">Major</option>
                            <option value="High">High</option>
                            <option value="Critical">Critical</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="submit-btn red">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Simpan Data Defect</span>
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-modern').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            alert.style.transition = 'all 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Add animation when form is submitted
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('.submit-btn');
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i><span>Menyimpan...</span>';
            btn.disabled = true;
        });
    });

    // Handle jenis defect lainnya
    document.getElementById('jenis_defect').addEventListener('change', function () {
        var lainnyaInput = document.getElementById('jenis_defect_lainnya');
        if (this.value === 'Lainnya') {
            lainnyaInput.style.display = 'block';
            lainnyaInput.required = true;
        } else {
            lainnyaInput.style.display = 'none';
            lainnyaInput.required = false;
        }
    });
</script>
@endpush