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
        <h2>
            <i class="bi bi-pencil-square"></i>
            Form Input Data Produksi
        </h2>
        <div class="date">
            <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <div class="table-card" style="max-width: 900px; margin: 0 auto;">
        <div class="chart-header" style="margin-bottom: 1.5rem;">
            <div class="chart-title">
                <i class="bi bi-clipboard-data"></i>
                Informasi Produksi
            </div>
        </div>

        <form action="{{ route('data.store') }}" method="POST" style="padding: 0 1rem 1rem;">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-person-fill"></i>
                        User
                    </label>
                    <input 
                        type="text" 
                        name="User" 
                        class="form-input" 
                        placeholder="Masukkan nama user"
                        value="{{ old('User') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-calendar-event"></i>
                        Tanggal Produksi
                    </label>
                    <input 
                        type="date" 
                        name="Tanggal_Produksi" 
                        class="form-input"
                        value="{{ old('Tanggal_Produksi', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-clock-history"></i>
                        Shift Produksi
                    </label>
                    <select name="Shift_Produksi" class="form-input" required>
                        <option value="">Pilih Shift</option>
                        <option value="Shift 1" {{ old('Shift_Produksi') == 'Shift 1' ? 'selected' : '' }}>Shift 1</option>
                        <option value="Shift 2" {{ old('Shift_Produksi') == 'Shift 2' ? 'selected' : '' }}>Shift 2</option>
                        <option value="Shift 3" {{ old('Shift_Produksi') == 'Shift 3' ? 'selected' : '' }}>Shift 3</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-gear-fill"></i>
                        Line Produksi
                    </label>
                    <select name="Line_Produksi" class="form-input" required>
                        <option value="">Pilih Line</option>
                        <option value="Line 1" {{ old('Line_Produksi') == 'Line 1' ? 'selected' : '' }}>Line 1</option>
                        <option value="Line 2" {{ old('Line_Produksi') == 'Line 2' ? 'selected' : '' }}>Line 2</option>
                        <option value="Line 3" {{ old('Line_Produksi') == 'Line 3' ? 'selected' : '' }}>Line 3</option>
                        <option value="Line 4" {{ old('Line_Produksi') == 'Line 4' ? 'selected' : '' }}>Line 4</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-boxes"></i>
                        Jumlah Produksi
                    </label>
                    <input 
                        type="number" 
                        name="Jumlah_Produksi" 
                        class="form-input" 
                        placeholder="0"
                        value="{{ old('Jumlah_Produksi') }}"
                        min="0"
                        required
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-bullseye"></i>
                        Target Produksi
                    </label>
                    <input 
                        type="number" 
                        name="Target_Produksi" 
                        class="form-input" 
                        placeholder="0"
                        value="{{ old('Target_Produksi') }}"
                        min="0"
                        required
                    >
                </div>
            </div>

            <div class="form-actions">
                <button type="reset" class="btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </button>
                <button type="submit" class="btn-primary">
                    <i class="bi bi-save"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </div>

    <style>
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
        }

        .form-label i {
            color: #015255ff;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9375rem;
            color: #1f2937;
            background-color: #fff;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #015255ff ;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        select.form-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-primary, .btn-secondary {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #015255ff 0%, #4f46e5 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .alert-modern {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
        }

        .alert-modern.success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-modern.error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-modern i {
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection