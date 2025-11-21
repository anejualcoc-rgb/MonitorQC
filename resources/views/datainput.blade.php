<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Produksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.5;
            overflow-x: hidden;
            padding-bottom: 2rem; /* Tambahan padding untuk mencegah terpotong */
        }

        /* Alert Styles */
        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            animation: slideIn 0.3s ease-out;
        }

        .alert-modern.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-modern.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-modern i {
            font-size: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Page Header */
        .page-header {
            padding: 1.5rem 1rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h2 {
            font-size: 1.875rem;
            font-weight: 700;
            color: #1e293b;
        }

        .page-header .date {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-size: 0.9375rem;
        }

        /* Main Grid Layout - PERUBAHAN UTAMA */
        .input-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 500px), 1fr));
            gap: 1.5rem;
            margin: 1.5rem 1rem 3rem; /* Margin bawah ditambah */
            padding: 0 0 2rem; /* Padding bawah ditambah */
            min-height: auto;
        }

        /* Card Styles */
        .input-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }

        .input-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }

        .card-header-input {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .card-icon.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-icon.red {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .card-title-section {
            flex: 1;
            min-width: 0;
        }

        .card-title-section h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-title-section p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: #f9fafb;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            background: white;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control:hover {
            border-color: #d1d5db;
            background: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
            gap: 1rem;
        }

        /* Button Styles - PERUBAHAN PENTING */
        .submit-btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
            position: relative;
        }

        .submit-btn.blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .submit-btn.red {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            padding-right: 2.5rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #9ca3af;
            margin-top: 0.25rem;
        }

        /* Responsive Design */
        /* Tablet */
        @media (max-width: 1024px) {
            .input-grid {
                gap: 1.25rem;
            }

            .input-card {
                padding: 1.25rem;
            }

            .card-icon {
                width: 44px;
                height: 44px;
                font-size: 22px;
            }

            .card-title-section h3 {
                font-size: 1.125rem;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .input-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
                margin: 1rem 0.5rem 2.5rem; /* Margin bawah ditambah untuk mobile */
                padding: 0 0 1.5rem; /* Padding bawah ditambah untuk mobile */
            }

            .input-card {
                padding: 1rem;
                border-radius: 12px;
            }

            .card-header-input {
                gap: 0.75rem;
                margin-bottom: 1.25rem;
                padding-bottom: 1rem;
            }

            .card-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .card-title-section h3 {
                font-size: 1rem;
            }

            .card-title-section p {
                font-size: 0.8125rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                font-size: 0.8125rem;
            }

            .form-control {
                padding: 0.625rem 0.875rem;
                font-size: 0.875rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .submit-btn {
                padding: 0.875rem;
                font-size: 0.9375rem;
                margin-bottom: 0.5rem; /* Margin bawah untuk button */
            }

            .page-header {
                padding: 1rem 0.5rem 0.5rem;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            .page-header .date {
                font-size: 0.875rem;
            }
        }

        /* Small Mobile */
        @media (max-width: 480px) {
            .input-card {
                padding: 0.875rem;
            }

            .card-header-input {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .card-icon {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }

            .form-control {
                font-size: 0.8125rem;
            }

            .form-hint {
                font-size: 0.6875rem;
            }

            select.form-control {
                background-position: right 0.75rem center;
                padding-right: 2rem;
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
    </style>
</head>
<body>
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
        <h2>Input Data Produksi</h2>
        <div class="date">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="input-grid">
        <div class="input-card">
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
                    <label class="form-label">User</label>
                    <input type="text" name="User" class="form-control" placeholder="Masukkan nama user" value="{{ old('User') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Produksi</label>
                        <input type="date" name="Tanggal_Produksi" class="form-control" value="{{ old('Tanggal_Produksi', \Carbon\Carbon::now()->format('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shift Produksi</label>
                        <select name="Shift_Produksi" class="form-control" required>
                            <option value="">Pilih Shift</option>
                            <option value="Shift 1" {{ old('Shift_Produksi') == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                            <option value="Shift 2" {{ old('Shift_Produksi') == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                            <option value="Shift 3" {{ old('Shift_Produksi') == 'Shift 3' ? 'selected' : '' }}>Shift 3</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Line Produksi</label>
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
                        <label class="form-label">Jumlah Produksi</label>
                        <input type="number" name="Jumlah_Produksi" class="form-control" placeholder="0" value="{{ old('Jumlah_Produksi') }}" min="0" required>
                        <div class="form-hint">Jumlah unit yang diproduksi</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Target Produksi</label>
                        <input type="number" name="Target_Produksi" class="form-control" placeholder="0" value="{{ old('Target_Produksi') }}" min="0" required>
                        <div class="form-hint">Target yang harus dicapai</div>
                    </div>
                </div>

                <button type="submit" class="submit-btn blue">
                    <i class="bi bi-save"></i> Simpan Data Produksi
                </button>
            </form>
        </div>

        <div class="input-card">
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
                    <label class="form-label">Pilih Data Produksi</label>
                    <select name="data_produksi_id" class="form-control" required>
                        <option value="">-- Pilih Produksi --</option>
                        @foreach($produksiList as $produksi)
                            <option value="{{ $produksi->id }}">
                                {{ $produksi->Tanggal_Produksi }} - {{ $produksi->User }} (Line {{ $produksi->Line_Produksi }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-hint">Pilih data produksi yang terkait dengan defect</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Produksi</label>
                    <input type="date" name="Tanggal_Produksi" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="Nama_Barang" class="form-control" placeholder="Masukkan nama barang" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Defect</label>
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
                        style="display:none; margin-top: 0.5rem;">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Jumlah Cacat Per Jenis</label>
                        <input type="number" name="Jumlah_Cacat_perjenis" class="form-control" placeholder="0" min="1" required>
                        <div class="form-hint">Jumlah unit dengan defect ini</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Severity</label>
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
                    <i class="bi bi-save"></i> Simpan Data Defect
                </button>
            </form>
        </div>
    </div>

    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-modern').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Add animation when form is submitted
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const btn = this.querySelector('.submit-btn');
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
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
</body>
</html>