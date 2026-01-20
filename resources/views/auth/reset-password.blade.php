<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - QC Monitoring System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
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
        .reset-wrapper {
            max-width: 650px; width: 100%; margin: 0 auto;
            background: rgba(255, 255, 255, 0.98); border-radius: 24px; overflow: hidden;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            animation: slideUpFade 0.8s ease-out;
        }
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #015255 0%, #017a7f 100%); 
            padding: 50px 60px;
            color: white; 
            position: relative; 
            overflow: hidden;
            text-align: center;
        }
        .header-section::before {
            content: ''; position: absolute; top: -50%; right: -50%; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 0.3; }
        }
        .header-icon {
            width: 80px; height: 80px; background: rgba(255, 255, 255, 0.15); 
            backdrop-filter: blur(10px); border-radius: 20px; 
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 40px; margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            position: relative; z-index: 2;
        }
        .header-title {
            font-size: 32px; font-weight: 700; margin-bottom: 10px;
            position: relative; z-index: 2;
        }
        .header-subtitle {
            font-size: 16px; opacity: 0.9; margin: 0;
            position: relative; z-index: 2;
        }

        /* Form Section */
        .form-section { 
            padding: 50px 60px; 
        }
        
        .alert-modern {
            padding: 16px 20px; border-radius: 12px; margin-bottom: 28px; display: flex;
            align-items: center; gap: 14px; font-size: 15px; border: 1px solid;
        }
        .alert-modern.success { background-color: #d1fae5; color: #065f46; border-color: #10b981; }
        .alert-modern.error { background-color: #fee2e2; color: #991b1b; border-color: #ef4444; }

        .form-group { margin-bottom: 28px; }
        .form-label { 
            font-weight: 600; color: #374151; margin-bottom: 12px; 
            font-size: 15px; display: flex; align-items: center; gap: 10px; 
        }
        .form-label i { font-size: 18px; color: #015255; }
        
        .input-wrapper { position: relative; }
        
        .input-icon {
            position: absolute; left: 20px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 22px; transition: color 0.3s ease; pointer-events: none;
        }
        
        .toggle-password {
            position: absolute; right: 20px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 22px; cursor: pointer; transition: color 0.3s ease;
            z-index: 10;
        }
        .toggle-password:hover { color: #015255; }
        
        .form-control-modern {
            width: 100%; padding: 18px 56px 18px 56px; border: 2px solid #e5e7eb;
            border-radius: 14px; font-size: 16px; transition: all 0.3s ease; background-color: #f9fafb;
        }
        .form-control-modern:focus {
            outline: none; border-color: #015255; background-color: white; 
            box-shadow: 0 0 0 4px rgba(1, 82, 85, 0.1);
        }
        .form-control-modern:focus + .input-icon { color: #015255; }
        .form-control-modern::placeholder { color: #9ca3af; }
        .form-control-modern:disabled,
        .form-control-modern:read-only {
            background-color: #f3f4f6; cursor: not-allowed;
        }

        .password-requirements {
            background: #f0f9ff; border-left: 3px solid #0284c7;
            padding: 14px 16px; border-radius: 10px; margin-top: 12px;
        }
        .password-requirements p {
            font-size: 13px; color: #0369a1; margin: 0 0 8px 0; font-weight: 500;
        }
        .password-requirements ul {
            margin: 0; padding-left: 20px; font-size: 13px; color: #075985;
        }
        .password-requirements li { margin-bottom: 4px; }

        .btn-reset {
            width: 100%; padding: 18px; background: linear-gradient(135deg, #015255 0%, #017a7f 100%);
            color: white; border: none; border-radius: 14px; font-size: 17px; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center;
            justify-content: center; gap: 12px; margin-top: 10px; 
            box-shadow: 0 4px 12px rgba(1, 82, 85, 0.3);
        }
        .btn-reset:hover { 
            background: linear-gradient(135deg, #017a7f 0%, #01999e 100%); 
            transform: translateY(-2px); 
            box-shadow: 0 8px 24px rgba(1, 82, 85, 0.4); 
        }

        .back-to-login {
            text-align: center; margin-top: 28px; padding-top: 28px;
            border-top: 1px solid #e5e7eb;
        }
        .back-to-login a {
            color: #015255; font-size: 15px; text-decoration: none; 
            font-weight: 500; display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.3s ease;
        }
        .back-to-login a:hover {
            color: #017a7f; text-decoration: underline;
        }

        .form-footer { 
            text-align: center; 
            margin-top: 25px;
        }
        .copyright { color: #6b7280; font-size: 14px; }

        @media (max-width: 768px) {
            .reset-wrapper { max-width: 550px; }
            .header-section { padding: 40px 40px; }
            .form-section { padding: 40px 40px; }
            .header-title { font-size: 28px; }
            .header-subtitle { font-size: 15px; }
        }
        @media (max-width: 576px) {
            .header-section { padding: 35px 30px; }
            .form-section { padding: 35px 30px; }
            .header-icon { width: 70px; height: 70px; font-size: 36px; }
            .header-title { font-size: 26px; }
            .form-control-modern { padding: 16px 50px 16px 50px; font-size: 15px; }
            .btn-reset { padding: 16px; font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="main-container">
        <div class="reset-wrapper">
            <div class="header-section">
                <div class="header-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <h1 class="header-title">Buat Password Baru</h1>
                <p class="header-subtitle">Silakan masukkan password baru Anda untuk mengamankan akun</p>
            </div>

            <div class="form-section">
                @if (session('status'))
                    <div class="alert-modern success">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email (Readonly) -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope-fill"></i>
                            Alamat Email
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="email" 
                                name="email" 
                                id="email"
                                class="form-control-modern @error('email') is-invalid @enderror" 
                                value="{{ $email ?? old('email') }}" 
                                readonly
                            >
                            <i class="bi bi-envelope-fill input-icon"></i>
                        </div>
                        @error('email')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill"></i>
                            Password Baru
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password" 
                                id="password"
                                class="form-control-modern @error('password') is-invalid @enderror" 
                                placeholder="Masukkan password baru"
                                required
                            >
                            <i class="bi bi-lock-fill input-icon"></i>
                            <i class="bi bi-eye-fill toggle-password" id="togglePassword"></i>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                        
                        <div class="password-requirements">
                            <p><i class="bi bi-info-circle"></i> Persyaratan Password:</p>
                            <ul>
                                <li>Minimal 8 karakter</li>
                                <li>Kombinasi huruf besar dan kecil</li>
                                <li>Mengandung angka dan simbol</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-shield-check"></i>
                            Konfirmasi Password
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation"
                                class="form-control-modern" 
                                placeholder="Ulangi password baru"
                                required
                            >
                            <i class="bi bi-shield-check input-icon"></i>
                            <i class="bi bi-eye-fill toggle-password" id="togglePasswordConfirm"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-reset">
                        <i class="bi bi-check-circle"></i>
                        <span>Reset Password</span>
                    </button>
                </form>

                <div class="back-to-login">
                    <a href="{{ route('login') }}">
                        <i class="bi bi-arrow-left-circle"></i>
                        Kembali ke Halaman Login
                    </a>
                </div>

                <div class="form-footer">
                    <p class="copyright">© 2024 PT XYZ. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');
        });

        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const passwordConfirmInput = document.getElementById('password_confirmation');

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');
        });
    </script>
</body>
</html>