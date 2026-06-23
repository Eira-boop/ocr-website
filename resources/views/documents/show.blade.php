<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết CCCD</title>
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
                radial-gradient(circle at 6% 8%, rgba(255, 143, 177, 0.14), transparent 38%),
                radial-gradient(circle at 95% 14%, rgba(185, 163, 255, 0.16), transparent 40%),
                radial-gradient(circle at 50% 100%, rgba(111, 227, 196, 0.12), transparent 42%),
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
            opacity: 0.45;
            z-index: 0;
            animation: float 9s ease-in-out infinite;
        }
        .blob.b1 { top: 6%; left: 3%; width: 56px; height: 56px; background: var(--yellow); animation-delay: 0s; }
        .blob.b2 { top: 72%; right: 5%; width: 44px; height: 44px; background: var(--mint); animation-delay: 2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-16px) rotate(8deg); }
        }

        .container { position: relative; z-index: 1; max-width: 760px; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--white);
            border: 2px solid var(--line);
            color: var(--pink-deep);
            font-weight: 700;
            font-size: 0.76rem;
            letter-spacing: 0.3px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        h2 {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink);
            font-weight: 800;
            font-size: clamp(1.5rem, 3.5vw, 2rem);
            letter-spacing: -0.3px;
            margin-bottom: 20px;
        }

        h2 .accent { color: var(--pink-deep); }

        h4 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            color: var(--ink);
            font-size: 1.1rem;
            margin: 28px 0 14px;
        }

        /* ===== INFO CARD / TABLE ===== */
        .info-card {
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 24px;
            padding: 10px;
            box-shadow: 0 8px 0 -4px rgba(255, 143, 177, 0.16), 0 14px 28px -12px rgba(155, 125, 240, 0.18);
        }

        table.table {
            margin-bottom: 0;
        }

        table.table-bordered, table.table-bordered th, table.table-bordered td {
            border: none;
        }

        table.table tr {
            border-bottom: 2px solid #faf2fa;
        }

        table.table tr:last-child {
            border-bottom: none;
        }

        table.table th {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--pink-deep);
            background: transparent;
            padding: 16px 18px;
            width: 38%;
            vertical-align: middle;
        }

        table.table th::before {
            content: "🪪 ";
            margin-right: 2px;
            opacity: 0.7;
        }

        table.table tr:nth-child(2) th::before { content: "👤 "; }
        table.table tr:nth-child(3) th::before { content: "🎂 "; }
        table.table tr:nth-child(4) th::before { content: "⚧ "; }
        table.table tr:nth-child(5) th::before { content: "🌍 "; }

        table.table td {
            font-weight: 600;
            font-size: 0.96rem;
            color: var(--ink);
            padding: 16px 18px;
            vertical-align: middle;
        }

        /* ===== IMAGE SECTION ===== */
        .photo-card {
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 24px;
            padding: 18px;
            box-shadow: 0 8px 0 -4px rgba(111, 227, 196, 0.18), 0 14px 28px -12px rgba(111, 227, 196, 0.2);
            display: inline-block;
        }

        .photo-card img {
            border-radius: 16px;
            border: none !important;
            display: block;
            max-width: 100%;
        }

        /* ===== BACK BUTTON ===== */
        .btn-secondary {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            background: linear-gradient(135deg, #c9c2d6, #aba0bd) !important;
            border: none !important;
            border-radius: 999px !important;
            color: var(--white) !important;
            padding: 10px 24px;
            box-shadow: 0 5px 0 #93899f, 0 8px 16px -6px rgba(74, 59, 82, 0.3);
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease, filter 0.2s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 7px 0 #93899f, 0 12px 22px -6px rgba(74, 59, 82, 0.32);
            filter: brightness(1.04);
        }

        .btn-secondary:active {
            transform: translateY(0) scale(0.97);
            box-shadow: 0 2px 0 #93899f;
        }

        .actions-row {
            margin-top: 30px;
        }

        @media (max-width: 576px) {
            table.table th, table.table td { padding: 12px 14px; font-size: 0.86rem; }
            .photo-card { padding: 12px; }
        }
    </style>
</head>
<body>

<span class="blob b1"></span>
<span class="blob b2"></span>

<div class="container mt-4">

    <span class="eyebrow">🪪 Chi tiết hồ sơ</span>

    <h2>Thông tin <span class="accent">CCCD</span></h2>

    <div class="info-card">

        <table class="table table-bordered">

            <tr>
                <th>Số CCCD</th>
                <td>{{ $document->id_number }}</td>
            </tr>

            <tr>
                <th>Họ tên</th>
                <td>{{ $document->full_name }}</td>
            </tr>

            <tr>
                <th>Ngày sinh</th>
                <td>{{ $document->birth_date }}</td>
            </tr>

            <tr>
                <th>Giới tính</th>
                <td>{{ $document->gender }}</td>
            </tr>

            <tr>
                <th>Quốc tịch</th>
                <td>{{ $document->nationality }}</td>
            </tr>

        </table>

    </div>

    <h4>Ảnh CCCD 📷</h4>

    <div class="photo-card">
        <img
            src="{{ asset('storage/'.$document->image_path) }}"
            width="500"
            class="img-fluid border">
    </div>

    <br><br>

    <div class="actions-row">
        <a href="{{ route('documents.list') }}"
           class="btn btn-secondary">
            ← Quay lại
        </a>
    </div>

</div>

</body>
</html>