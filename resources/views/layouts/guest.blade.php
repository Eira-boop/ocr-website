<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #fdf6ff;
            --ink: #4a3b52;
            --ink-soft: #8a7a92;
            --white: #ffffff;
            --pink: #ff8fb1;
            --pink-deep: #ff6fa0;
            --lilac: #b9a3ff;
            --lilac-deep: #9b7df0;
            --mint: #6fe3c4;
            --mint-deep: #3ecfa8;
            --yellow: #ffd166;
            --red: #ff6b6b;
            --line: #f3def0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Quicksand', sans-serif;
            background:
                radial-gradient(circle at 8% 10%, rgba(255, 143, 177, 0.16), transparent 40%),
                radial-gradient(circle at 92% 15%, rgba(185, 163, 255, 0.18), transparent 42%),
                radial-gradient(circle at 50% 100%, rgba(111, 227, 196, 0.14), transparent 45%),
                var(--bg);
            min-height: 100vh;
            color: var(--ink);
            position: relative;
            overflow-x: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(2px);
            opacity: 0.5;
            z-index: 0;
            animation: float 9s ease-in-out infinite;
        }
        .blob.b1 { top: 8%; left: 6%; width: 70px; height: 70px; background: var(--yellow); animation-delay: 0s; }
        .blob.b2 { top: 65%; right: 8%; width: 50px; height: 50px; background: var(--mint); animation-delay: 2s; }
        .blob.b3 { bottom: 10%; left: 12%; width: 36px; height: 36px; background: var(--pink); animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-18px) rotate(8deg); }
        }

        .auth-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 28px;
            padding: 40px 36px;
            box-shadow: 0 10px 0 -4px rgba(255, 143, 177, 0.18), 0 16px 32px -12px rgba(155, 125, 240, 0.18);
        }

        .auth-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff0f6;
            border: 2px solid var(--line);
            color: var(--pink-deep);
            font-weight: 700;
            font-size: 0.74rem;
            letter-spacing: 0.3px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        .auth-title {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 800;
            font-size: 1.7rem;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .auth-title .accent { color: var(--pink-deep); }

        .auth-subtext {
            color: var(--ink-soft);
            font-weight: 500;
            font-size: 0.92rem;
            margin-bottom: 28px;
        }

        label {
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            font-size: 0.86rem;
            color: var(--ink);
            margin-bottom: 6px;
            display: block;
        }

        .form-control,
        input[type=text],
        input[type=email],
        input[type=password] {
            width: 100%;
            border: 2.5px dashed #e9d5e7;
            border-radius: 16px;
            background: #fff9fb;
            font-family: 'Quicksand', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 12px 16px;
            color: var(--ink);
            transition: border-color 0.25s ease, background 0.25s ease;
            margin-bottom: 18px;
        }

        .form-control:focus,
        input:focus {
            outline: none;
            border-color: var(--pink);
            background: #fff5f8;
            box-shadow: 0 0 0 4px rgba(255, 143, 177, 0.15);
        }

        .btn-auth {
            width: 100%;
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.02rem;
            border: none;
            border-radius: 20px;
            color: var(--white);
            padding: 13px;
            cursor: pointer;
            background: linear-gradient(135deg, var(--pink), var(--pink-deep));
            box-shadow: 0 6px 0 var(--pink-deep), 0 10px 20px -6px rgba(255, 111, 160, 0.5);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, filter 0.2s ease;
        }

        .btn-auth:hover {
            transform: translateY(-3px);
            filter: brightness(1.04);
            box-shadow: 0 9px 0 var(--pink-deep), 0 16px 28px -6px rgba(255, 111, 160, 0.55);
        }

        .btn-auth:active {
            transform: translateY(0);
            box-shadow: 0 3px 0 var(--pink-deep);
        }

        .auth-links {
            text-align: center;
            margin-top: 22px;
            font-size: 0.88rem;
            color: var(--ink-soft);
        }

        .auth-links a {
            color: var(--pink-deep);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-links a:hover { text-decoration: underline; }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-size: 0.88rem;
            color: var(--ink-soft);
        }

        .error-box {
            background: #fbeaea;
            border: 2px solid #f3c6c6;
            color: #9b2c2c;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 0.86rem;
        }

        .error-box ul { margin: 0; padding-left: 18px; }

        .status-box {
            background: #e7f0e8;
            border: 2px solid #c8e0cb;
            color: #2f6b46;
            border-radius: 14px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 0.86rem;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <span class="blob b1"></span>
    <span class="blob b2"></span>
    <span class="blob b3"></span>

    <div class="auth-card">
        <span class="auth-eyebrow">🪪 Hệ thống OCR CCCD</span>
        {{ $slot }}
    </div>

</body>
</html>