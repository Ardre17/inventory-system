<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DISTAN — Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
        }

        /* Panel izquierdo */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: rgba(255,255,255,0.03);
            border-radius: 50%;
            top: -100px; left: -100px;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -80px; right: -80px;
        }

        .logo-area {
            text-align: center;
            z-index: 1;
        }

        .logo-icon {
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.1);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .logo-title {
            font-size: 42px;
            font-weight: 800;
            color: white;
            letter-spacing: 4px;
            margin-bottom: 8px;
        }

        .logo-subtitle {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 48px;
        }

        .features {
            z-index: 1;
            width: 100%;
            max-width: 320px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: rgba(255,255,255,0.06);
            border-radius: 12px;
            margin-bottom: 10px;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .feature-icon {
            font-size: 22px;
            width: 40px;
            text-align: center;
        }

        .feature-text strong {
            display: block;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .feature-text span {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
        }

        /* Panel derecho */
        .right-panel {
            width: 480px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 50px;
        }

        .login-header {
            margin-bottom: 36px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            transition: border-color 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
            cursor: pointer;
        }

        .remember-label input {
            width: 16px; height: 16px;
            accent-color: #2563eb;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            letter-spacing: 0.5px;
        }

        .btn-login:hover { opacity: 0.92; transform: translateY(-1px); }
        .btn-login:active { transform: translateY(0); }

        .error-msg {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .footer-text {
            text-align: center;
            margin-top: 32px;
            font-size: 12px;
            color: #94a3b8;
        }

        @media (max-width: 768px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 40px 24px; }
        }
    </style>
</head>
<body>

    <!-- Panel izquierdo -->
    <div class="left-panel">
        <div class="logo-area">
            <div class="logo-icon">🚚</div>
            <div class="logo-title">DISTAN</div>
            <div class="logo-subtitle">Todo tu logística, en un solo lugar</div>
        </div>

        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">📦</div>
                <div class="feature-text">
                    <strong>Control de Inventario</strong>
                    <span>Stock en tiempo real por categoría</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🧾</div>
                <div class="feature-text">
                    <strong>Gestión de Órdenes</strong>
                    <span>Local, encomienda y supermercado</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🏭</div>
                <div class="feature-text">
                    <strong>Órdenes de Producción</strong>
                    <span>Control de etiquetas y suministros</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">📊</div>
                <div class="feature-text">
                    <strong>Reportes y PDF</strong>
                    <span>Documentos listos para despacho</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="right-panel">
        <div class="login-header">
            <h2>Bienvenido 👋</h2>
            <p>Ingresa tus credenciales para continuar</p>
        </div>

        @if($errors->any())
        <div class="error-msg">
            @foreach($errors->all() as $error){{ $error }}@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="tu@correo.com" required autofocus>
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    Recordarme
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   style="font-size:13px; color:#2563eb; text-decoration:none; font-weight:500;">
                    ¿Olvidaste tu contraseña?
                </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                Iniciar Sesión →
            </button>
        </form>

        <div class="footer-text">
            DISTAN © {{ date('Y') }} — Sistema de Inventario y Logística
        </div>
    </div>

</body>
</html>
