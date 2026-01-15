<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - QC Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* Menggunakan style dasar yang sama dengan Login Page */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0a4d4e 0%, #015255 50%, #023a3c 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        .bg-pattern {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);
            pointer-events: none;
        }
        .main-container {
            position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 30px 15px;
        }
        .register-wrapper {
            max-width: 900px; width: 100%; 
            background: rgba(255, 255, 255, 0.98); border-radius: 24px; overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3);
            display: flex; flex-direction: column;
        }
        
        .form-section { padding: 50px 40px; }
        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h2 { font-size: 28px; font-weight: 700; color: #015255; margin-bottom: 5px; }
        .form-header p { color: #6b7280; font-size: 14px; }

        .form-group { margin-bottom: 20px; }
        .form-label { font-weight: 600; color: #374151; margin-bottom: 8px; font-size: 14px; display: block; }
        
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 18px; pointer-events: none;
        }
        
        .form-control-modern {
            width: 100%; padding: 14px 20px 14px 50px; border: 2px solid #e5e7eb;
            border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background-color: #f9fafb;
        }
        .form-control-modern:focus {
            outline: none; border-color: #015255; background-color: white; 
            box-shadow: 0 0 0 4px rgba(1, 82, 85, 0.1);
        }
        
        .form-select-modern {
            width: 100%; padding: 14px 20px 14px 50px; border: 2px solid #e5e7eb;
            border-radius: 12px; font-size: 14px; background-color: #f9fafb; cursor: pointer;
            appearance: none; -webkit-appearance: none;
        }
        .form-select-modern:focus { outline: none; border-color: #015255; }

        .btn-register {
            width: 100%; padding: 14px; background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; margin-top: 10px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(1, 82, 85, 0.3); }

        .form-footer { text-align: center; margin-top: 25px; font-size: 14px; color: #6b7280; }
        .form-footer a { color: #015255; text-decoration: none; font-weight: 600; }
        .form-footer a:hover { text-decoration: underline; }

        .invalid-feedback { font-size: 12px; color: #dc2626; margin-top: 5px; display: block; }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    
    <div class="main-container">
        <div class="register-wrapper">
            <div class="form-section">
                <div class="form-header">
                    <div class="mb-3" style="font-size: 40px; color: #015255;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h2>Buat Akun Baru</h2>
                    <p>Lengkapi data diri Anda untuk mengakses sistem monitoring.</p>
                </div>

                <form method="POST" action="{{ route('register.process') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-wrapper">
                            <input type="text" name="name" class="form-control-modern @error('name') is-invalid @enderror" 
                                   placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required autofocus>
                            <i class="bi bi-person-fill input-icon"></i>
                        </div>
                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" class="form-control-modern @error('email') is-invalid @enderror" 
                                   placeholder="nama@perusahaan.com" value="{{ old('email') }}" required>
                            <i class="bi bi-envelope-fill input-icon"></i>
                        </div>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jabatan / Role</label>
                        <div class="input-wrapper">
                            <select name="role" class="form-select-modern @error('role') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Jabatan...</option>
                                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff QC</option>
                                <option value="spv" {{ old('role') == 'spv' ? 'selected' : '' }}>Supervisor (SPV)</option>
                                <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                            </select>
                            <i class="bi bi-briefcase-fill input-icon"></i>
                            <i class="bi bi-chevron-down" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #9ca3af;"></i>
                        </div>
                        @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kata Sandi</label>
                                <div class="input-wrapper">
                                    <input type="password" name="password" class="form-control-modern @error('password') is-invalid @enderror" 
                                           placeholder="Min. 6 karakter" required>
                                    <i class="bi bi-lock-fill input-icon"></i>
                                </div>
                                @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Ulangi Kata Sandi</label>
                                <div class="input-wrapper">
                                    <input type="password" name="password_confirmation" class="form-control-modern" 
                                           placeholder="Ketik ulang password" required>
                                    <i class="bi bi-check-lg input-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Daftar Sekarang</span>
                    </button>

                    <div class="form-footer">
                        Sudah memiliki akun? <a href="{{ route('login') }}">Masuk disini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>