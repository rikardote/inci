<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Control de Asistencia | Login</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B0000;
            /* Guinda principal */
            --primary-dark: #600018;
            /* Guinda oscuro */
            --primary-light: #d8a7a7;
            /* Guinda claro */
            --text-color: #333;
            --error-color: #d9534f;
            --success-color: #5cb85c;
            --bg-gradient: linear-gradient(135deg, #f5f5f5 0%, #e6d2d2 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-color);
            padding: 20px;
        }

        .login-container {
            display: flex;
            max-width: 900px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            border: 1px solid rgba(139, 0, 0, 0.1);
        }

        .login-image {
            flex: 1;
            background-color: var(--primary-color);
            background-image: linear-gradient(160deg, #8B0000 0%, #5c0000 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .login-image img {
            max-width: 100%;
            height: auto;
            position: relative;
            z-index: 2;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
        }

        .login-form-container {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        .login-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .login-header p {
            color: #777;
            font-size: 16px;
        }

        .login-form {
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-control {
            height: 50px;
            padding: 10px 20px 10px 45px;
            width: 100%;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(139, 0, 0, 0.15);
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 17px;
            color: #aaa;
            transition: all 0.3s;
        }

        .form-control:focus+i {
            color: var(--primary-color);
        }

        .has-error .form-control {
            border-color: var(--error-color);
        }

        .has-error .form-control:focus {
            box-shadow: 0 0 0 3px rgba(217, 83, 79, 0.2);
        }

        .alert {
            display: block;
            color: var(--error-color);
            font-size: 13px;
            padding: 5px 0;
        }

        .log-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .log-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right,
                    rgba(255, 255, 255, 0.1) 0%,
                    rgba(255, 255, 255, 0.2) 50%,
                    rgba(255, 255, 255, 0) 100%);
            transition: all 0.6s;
        }

        .log-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 0, 0, 0.3);
        }

        .log-btn:hover::after {
            left: 100%;
        }

        .log-btn:active {
            transform: translateY(0);
        }

        .log-btn i {
            margin-right: 8px;
        }

        .login-footer {
            text-align: center;
            margin-top: auto;
        }

        .login-footer h2 {
            font-size: 18px;
            font-weight: 500;
            color: #555;
            margin-bottom: 10px;
        }

        .system-version {
            font-size: 12px;
            color: #999;
            position: relative;
            display: inline-block;
        }

        .system-version::before,
        .system-version::after {
            content: '';
            position: absolute;
            height: 1px;
            background-color: #e0e0e0;
            top: 50%;
            width: 30px;
        }

        .system-version::before {
            right: 100%;
            margin-right: 10px;
        }

        .system-version::after {
            left: 100%;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-image {
                padding: 30px;
            }

            .login-image img {
                max-width: 200px;
            }

            .login-form-container {
                padding: 30px;
            }
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header,
        .login-form,
        .login-footer {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .login-form {
            animation-delay: 0.2s;
        }

        .login-footer {
            animation-delay: 0.4s;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-image">
            <img src="{{ asset('images/60issste.png') }}" alt="Logo del Sistema">
        </div>

        <div class="login-form-container">
            <div class="login-header">
                <h1>Bienvenido</h1>
                <p>Ingresa tus credenciales para acceder</p>
            </div>

            <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                {!! csrf_field() !!}

                <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                    <input type="text" class="form-control" placeholder="Usuario" name="username"
                        value="{{ old('username') }}" autofocus>
                    <i class="fa fa-user"></i>
                    @if ($errors->has('username'))
                    <span class="alert">{{ $errors->first('username') }}</span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" placeholder="Contraseña" name="password">
                    <i class="fa fa-lock"></i>
                    @if ($errors->has('password'))
                    <span class="alert">{{ $errors->first('password') }}</span>
                    @endif
                    @if ($errors->has('email'))
                    <span class="alert">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <button type="submit" class="log-btn">
                    <i class="fa fa-sign-in"></i> Acceder
                </button>
            </form>

            <div class="login-footer">
                <h2>SISTEMA PARA EL REGISTRO Y CONTROL DE ASISTENCIA</h2>
                <div class="system-version">v2.0</div>
            </div>
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/js/jquery.js') }}"></script>
    <script src="{{ asset('js/shake.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Animación de error
            if ($('.has-error').length > 0) {
                $('.login-container').shake(5, 5, 200);
            }
        });
    </script>
</body>

</html>
