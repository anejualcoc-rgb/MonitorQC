<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QC Monitoring System - PT XYZ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        /* CSS SAMA SEPERTI SEBELUMNYA */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #0a4d4e 0%, #015255 50%, #023a3c 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        .bg-pattern {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; opacity: 0.05;
            background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);
            animation: slidePattern 20s linear infinite; pointer-events: none;
        }
        @keyframes slidePattern {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(70px) translateY(70px); }
        }
        .main-container {
            position: relative; z-index: 1; min-height: 100vh; display: flex; align-items: center; padding: 30px 15px;
        }
        .login-wrapper {
            max-width: 1200px; width: 100%; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr;
            background: rgba(255, 255, 255, 0.98); border-radius: 24px; overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: slideUpFade 0.8s ease-out;
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* Info Section Styles */
        .info-section {
            background: linear-gradient(135deg, #015255 0%, #017a7f 100%); padding: 60px 50px;
            color: white; position: relative; overflow: hidden;
        }
        .info-section::before {
            content: ''; position: absolute; top: -50%; right: -50%; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.3; }
        }
        .brand-logo { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; position: relative; z-index: 2; }
        .logo-icon {
            width: 60px; height: 60px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            font-size: 32px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        .brand-name h1 { font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.5px; }
        .brand-name p { font-size: 14px; opacity: 0.9; margin: 0; font-weight: 300; }
        .section-title { font-size: 20px; font-weight: 600; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; position: relative; z-index: 2;}
        .feature-list { list-style: none; padding: 0; margin: 0; position: relative; z-index: 2;}
        .feature-item {
            display: flex; align-items: flex-start; gap: 15px; margin-bottom: 24px; padding: 20px;
            background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px;
            border-left: 3px solid rgba(255, 255, 255, 0.3); transition: all 0.3s ease;
        }
        .feature-item:hover { background: rgba(255, 255, 255, 0.15); transform: translateX(5px); border-left-color: #fff; }
        .feature-icon {
            width: 40px; height: 40px; min-width: 40px; background: rgba(255, 255, 255, 0.2);
            border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .feature-text h4 { font-size: 15px; font-weight: 600; margin-bottom: 5px; }
        .feature-text p { font-size: 13px; opacity: 0.85; margin: 0; line-height: 1.5; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 30px; position: relative; z-index: 2;}
        .stat-card { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); padding: 20px; border-radius: 12px; text-align: center; }
        .stat-number { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .stat-label { font-size: 12px; opacity: 0.85; }

        /* Form Section Styles */
        .form-section { padding: 60px 50px; display: flex; flex-direction: column; justify-content: center; }
        .form-header { margin-bottom: 40px; }
        .form-header h2 { font-size: 32px; font-weight: 700; color: #1a1a1a; margin-bottom: 10px; }
        .form-header p { color: #6b7280; font-size: 15px; }
        
        .alert-modern {
            padding: 14px 18px; border-radius: 12px; margin-bottom: 24px; display: flex;
            align-items: center; gap: 12px; font-size: 14px; border: 1px solid;
        }
        .alert-modern.error { background-color: #fee2e2; color: #991b1b; border-color: #ef4444; }

        .form-group { margin-bottom: 24px; }
        .form-label { font-weight: 600; color: #374151; margin-bottom: 10px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .form-label i { font-size: 16px; color: #015255; }
        
        .input-wrapper { position: relative; }
        
        .input-icon {
            position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 20px; transition: color 0.3s ease; pointer-events: none;
        }
        
        /* Toggle password icon */
        .toggle-password {
            position: absolute; right: 18px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 20px; cursor: pointer; transition: color 0.3s ease;
            z-index: 10;
        }
        .toggle-password:hover { color: #015255; }
        
        .form-control-modern {
            width: 100%; padding: 16px 52px 16px 52px; border: 2px solid #e5e7eb;
            border-radius: 12px; font-size: 15px; transition: all 0.3s ease; background-color: #f9fafb;
        }
        .form-control-modern:focus {
            outline: none; border-color: #015255; background-color: white; box-shadow: 0 0 0 4px rgba(1, 82, 85, 0.1);
        }
        .form-control-modern:focus + .input-icon { color: #015255; }
        .form-control-modern::placeholder { color: #9ca3af; }

        .btn-login {
            width: 100%; padding: 16px; background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center;
            justify-content: center; gap: 10px; margin-top: 10px; box-shadow: 0 4px 12px rgba(1, 82, 85, 0.3);
        }
        .btn-login:hover { background: linear-gradient(135deg, #017a7f 0%, #01999e 100%); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(1, 82, 85, 0.4); }
        
        .form-footer { text-align: center; margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb; }
        .copyright { color: #6b7280; font-size: 13px; }

        @media (max-width: 992px) {
            .login-wrapper { grid-template-columns: 1fr; max-width: 500px; }
            .info-section { display: none; }
            .form-section { padding: 40px 30px; }
        }
        @media (max-width: 576px) {
            .form-section { padding: 30px 20px; }
            .form-header h2 { font-size: 26px; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="main-container">
        <div class="login-wrapper">
            <div class="info-section">
                <div class="brand-header">
                    <div class="brand-logo">
                        <div class="logo-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="brand-name">
                            <h1>QC Monitoring</h1>
                            <p>PT XYZ Production System</p>
                        </div>
                    </div>
                </div>

                <div class="info-content">
                    <div class="section-title">
                        <i class="bi bi-star-fill"></i><span>Fitur Unggulan Sistem</span>
                    </div>
                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon"><i class="bi bi-speedometer2"></i></div>
                            <div class="feature-text"><h4>Monitoring Real-Time</h4><p>Pantau defect produksi secara langsung.</p></div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div class="feature-text"><h4>Analisis Tren</h4><p>Visualisasi tren produksi dan defect harian, mingguan, dan bulanan.</p></div>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon"><i class="bi bi-people-fill"></i></div>
                            <div class="feature-text"><h4>Multi-User Access</h4><p>Akses terpusat untuk semua divisi.</p></div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="form-section">
                <div class="form-header">
                    <h2>Selamat Datang</h2>
                    <p>Masuk ke sistem monitoring kualitas produksi</p>
                </div>

                <div class="alert-modern error" style="display: none;" id="errorAlert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span id="errorMessage"></span>
                </div>
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf

                        <div class="form-group">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-wrapper">
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email"
                                    class="form-control-modern @error('email') is-invalid @enderror" 
                                    placeholder="nama@perusahaan.com" 
                                    required 
                                    autofocus
                                    value="{{ old('email') }}"
                                >
                                <i class="bi bi-envelope-fill input-icon"></i>
                            </div>
                            {{-- Menampilkan pesan error validasi email --}}
                            @error('email')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control-modern" 
                                placeholder="Masukkan kata sandi" 
                                required
                            >
                            <i class="bi bi-lock-fill input-icon"></i>
                            <i class="bi bi-eye-fill toggle-password" id="togglePassword"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk ke Sistem</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');
        });
    </script>
</body>
</html>