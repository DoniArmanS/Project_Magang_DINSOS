<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM-PPKS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            /* Animated Background */
            background: linear-gradient(45deg, #1a237e, #283593, #311b92, #4a148c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Abstract Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
        }

        .shape-1 {
            width: 300px;
            /* Reverted size */
            height: 300px;
            background: #ffca28;
            top: -100px;
            left: -100px;
            animation: float 8s ease-in-out infinite alternate;
        }

        .shape-2 {
            width: 400px;
            /* Reverted size */
            height: 400px;
            background: #f50057;
            bottom: -150px;
            right: -100px;
            opacity: 0.4;
            animation: float 10s ease-in-out infinite alternate-reverse;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(20px);
            }
        }

        /* Card Reverted to Compact Style */
        .glass-login {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 3rem;
            /* Reverted padding */
            width: 100%;
            max-width: 420px;
            /* Reverted width */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff !important;
            /* Force white text */
            padding: 12px 15px;
            /* Reverted padding */
            border-radius: 10px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ffca28;
            box-shadow: none;
            color: #fff !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Input Group Text (Icon) */
        .input-group-text {
            background: transparent;
            border-right: 0;
            color: rgba(255, 255, 255, 0.5);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-label {
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .btn-warning {
            background: #ffca28;
            border: none;
            color: #333;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-warning:hover {
            background: #ffd54f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 202, 40, 0.3);
        }

        .logo-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 2rem;
            color: #ffca28;
        }
    </style>
</head>

<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="glass-login p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="logo-container">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="fw-bold mb-1">Selamat Datang!</h3>
            <p class="text-white-50 small">Sistem Informasi Manajemen PPKS — Dinas Sosial</p>
        </div>

        <form action="{{ route('authenticate') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label small text-uppercase text-white-50 fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white-50"
                        style="border-color: rgba(255,255,255,0.2)">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" class="form-control border-start-0 ps-0" id="email" name="email"
                        placeholder="name@example.com" value="{{ old('email') }}" required autofocus
                        style="color: white;">
                </div>
                @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small text-uppercase text-white-50 fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-white-50"
                        style="border-color: rgba(255,255,255,0.2)">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password"
                        placeholder="Enter your password" required style="color: white;">
                </div>
                @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning">Sign In</button>
            </div>
        </form>
    </div>

</body>

</html>