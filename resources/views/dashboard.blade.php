<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard OCR CCCD</title>

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
        }

        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(2px);
            opacity: 0.5;
            z-index: 0;
            animation: float 9s ease-in-out infinite;
        }
        .blob.b1 { top: 8%; left: 4%; width: 70px; height: 70px; background: var(--yellow); animation-delay: 0s; }
        .blob.b2 { top: 65%; right: 6%; width: 50px; height: 50px; background: var(--mint); animation-delay: 2s; }
        .blob.b3 { bottom: 10%; left: 10%; width: 36px; height: 36px; background: var(--pink); animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-18px) rotate(8deg); }
        }

        .container { position: relative; z-index: 1; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--white);
            border: 2px solid var(--line);
            color: var(--pink-deep);
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.3px;
            padding: 6px 16px;
            border-radius: 999px;
            margin-bottom: 16px;
        }

        h1.text-primary {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink) !important;
            font-weight: 800;
            font-size: clamp(1.7rem, 4vw, 2.3rem);
            letter-spacing: -0.3px;
        }

        h1.text-primary .accent {
            color: var(--pink-deep);
        }

        .subtext {
            color: var(--ink-soft);
            font-weight: 500;
            margin-top: 8px;
        }

        hr {
            border: none;
            height: 0;
            margin: 36px 0;
        }

        /* ===== STAT CARD ===== */
        .card.shadow {
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 28px !important;
            box-shadow: 0 10px 0 -4px rgba(255, 143, 177, 0.18), 0 16px 32px -12px rgba(155, 125, 240, 0.18) !important;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .card.shadow:hover {
            transform: translateY(-4px) rotate(-0.3deg);
            border-color: var(--pink);
            box-shadow: 0 14px 0 -4px rgba(255, 143, 177, 0.25), 0 22px 40px -12px rgba(155, 125, 240, 0.25) !important;
        }

        .card-body.text-center {
            padding: 34px 28px;
        }

        .card-body .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #ffe3ec;
            font-size: 1.4rem;
            margin-bottom: 14px;
        }

        .card-body h1 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 800;
            font-size: 3rem;
            color: var(--pink-deep);
            margin-bottom: 4px;
        }

        .card-body h5 {
            font-family: 'Quicksand', sans-serif;
            font-weight: 600;
            color: var(--ink-soft);
            font-size: 0.96rem;
            margin: 0;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 1.02rem;
            border: none !important;
            border-radius: 20px !important;
            color: var(--white) !important;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, filter 0.2s ease !important;
        }

        .btn:hover {
            transform: translateY(-4px) scale(1.02);
            filter: brightness(1.04);
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink), var(--pink-deep)) !important;
            box-shadow: 0 6px 0 var(--pink-deep), 0 10px 20px -6px rgba(255, 111, 160, 0.5);
        }
        .btn-primary:hover { box-shadow: 0 9px 0 var(--pink-deep), 0 16px 28px -6px rgba(255, 111, 160, 0.55); }
        .btn-primary:active { box-shadow: 0 3px 0 var(--pink-deep); }

        .btn-success {
            background: linear-gradient(135deg, var(--mint), var(--mint-deep)) !important;
            box-shadow: 0 6px 0 var(--mint-deep), 0 10px 20px -6px rgba(62, 207, 168, 0.5);
        }
        .btn-success:hover { box-shadow: 0 9px 0 var(--mint-deep), 0 16px 28px -6px rgba(62, 207, 168, 0.55); }
        .btn-success:active { box-shadow: 0 3px 0 var(--mint-deep); }

        .btn span.emoji { margin-right: 6px; }

        @media (max-width: 576px) {
            .card-body.text-center { padding: 26px 20px; }
            .card-body h1 { font-size: 2.4rem; }
        }
    </style>
</head>
<body>

<span class="blob b1"></span>
<span class="blob b2"></span>
<span class="blob b3"></span>

<div class="container mt-5">

    <div class="text-center">
        <span class="eyebrow">✨ Tổng quan hệ thống</span>
    </div>

    <h1 class="text-center text-primary">
        HỆ THỐNG <span class="accent">OCR CCCD</span> 🌸
    </h1>

    <p class="text-center subtext">
        Theo dõi nhanh số hồ sơ đã xử lý và truy cập các chức năng chính nha!
    </p>

    <hr>

    <div class="row mt-4">

        <div class="col-md-4 mx-auto">

            <div class="card shadow">

                <div class="card-body text-center">

                    <span class="stat-icon">🪪</span>

                    <h1>{{ $totalDocuments }}</h1>

                    <h5>Tổng hồ sơ đã xử lý</h5>

                </div>

            </div>

        </div>

    </div>

    <hr class="my-4">

    <div class="row">

        <div class="col-md-4">

            <a href="/ocr"
               class="btn btn-primary w-100 p-3">

                <span class="emoji">💗</span>OCR CCCD

            </a>

        </div>

        <div class="col-md-4">

            <a href="/documents"
               class="btn btn-success w-100 p-3">

                <span class="emoji">🌿</span>Danh sách hồ sơ

            </a>

        </div>
        <div class="col-md-4">

    <a href="{{ route('export.all.excel') }}"
       class="btn btn-warning w-100 p-3">

        Xuất Excel thống kê

    </a>

</div>

    </div>

</div>

</body>
</html>