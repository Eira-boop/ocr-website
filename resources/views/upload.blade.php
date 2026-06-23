<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR CCCD</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #fdf6ff;
            --bg-2: #fff0f6;
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Quicksand', sans-serif;
            background:
                radial-gradient(circle at 8% 12%, rgba(255, 143, 177, 0.16), transparent 40%),
                radial-gradient(circle at 92% 18%, rgba(185, 163, 255, 0.18), transparent 42%),
                radial-gradient(circle at 50% 95%, rgba(111, 227, 196, 0.14), transparent 45%),
                var(--bg);
            min-height: 100vh;
            color: var(--ink);
            position: relative;
            overflow-x: hidden;
        }

        /* floating doodle blobs */
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

        /* ===== NAVBAR ===== */
        .navbar {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border-bottom: 2px solid var(--line);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar .container {
            width: 100%;
            max-width: 1080px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
        }

        .navbar-brand {
            color: var(--ink);
            font-family: 'Baloo 2', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand::before {
            content: "🪪";
            font-size: 1.3rem;
            display: inline-block;
            transform: rotate(-8deg);
        }

        .navbar-nav {
            display: flex;
            gap: 6px;
        }

        .nav-link {
            color: var(--ink-soft);
            text-decoration: none;
            padding: 9px 18px;
            border-radius: 999px;
            font-size: 0.92rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .nav-link:hover {
            background: var(--pink);
            color: var(--white);
            transform: translateY(-1px);
        }

        /* ===== PAGE LAYOUT ===== */
        .page-wrapper {
            max-width: 1080px;
            margin: 0 auto;
            padding: 56px 24px 80px;
            position: relative;
            z-index: 1;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

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
            margin-bottom: 18px;
        }

        h1 {
            font-family: 'Baloo 2', sans-serif;
            color: var(--ink);
            font-size: clamp(1.9rem, 4.5vw, 2.6rem);
            font-weight: 800;
            line-height: 1.2;
        }

        h1 .accent {
            color: var(--pink-deep);
            position: relative;
            display: inline-block;
        }

        .subtext {
            margin-top: 12px;
            color: var(--ink-soft);
            font-size: 1rem;
            font-weight: 500;
            max-width: 46ch;
            margin-left: auto;
            margin-right: auto;
        }

        h3, h5 {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            color: var(--ink);
        }

        h3 { font-size: 1.15rem; }
        h5 { font-size: 1.1rem; }

        hr {
            border: none;
            height: 0;
            margin: 40px 0;
        }

        /* ===== BUBBLY PANELS ===== */
        .panel {
            position: relative;
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 28px;
            padding: 30px 32px;
            margin-bottom: 10px;
            box-shadow: 0 10px 0 -4px rgba(255, 143, 177, 0.18), 0 16px 32px -12px rgba(155, 125, 240, 0.18);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .panel:hover {
            transform: translateY(-4px) rotate(-0.3deg);
            border-color: var(--pink);
            box-shadow: 0 14px 0 -4px rgba(255, 143, 177, 0.25), 0 22px 40px -12px rgba(155, 125, 240, 0.25);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 14px;
        }

        .panel-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 14px;
            font-size: 1.15rem;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .panel-title-row { display: flex; align-items: center; }

        .panel-tag {
            font-size: 0.74rem;
            font-weight: 700;
            color: var(--ink-soft);
            background: var(--bg-2);
            padding: 5px 12px;
            border-radius: 999px;
            white-space: nowrap;
        }

        /* ===== FORMS ===== */
        form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }

        input[type="file"],
        .form-control {
            flex: 1;
            min-width: 220px;
            padding: 12px 16px;
            border: 2.5px dashed #e9d5e7;
            border-radius: 16px;
            background: var(--bg-2);
            font-family: 'Quicksand', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--ink);
            cursor: pointer;
            transition: border-color 0.25s ease, background 0.25s ease, transform 0.2s ease;
        }

        input[type="file"]::file-selector-button,
        .form-control::file-selector-button {
            font-family: 'Quicksand', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--white);
            background: var(--lilac-deep);
            border: none;
            border-radius: 999px;
            padding: 7px 14px;
            margin-right: 12px;
            cursor: pointer;
            transition: opacity 0.2s ease;
        }

        input[type="file"]:hover::file-selector-button,
        .form-control:hover::file-selector-button {
            opacity: 0.85;
        }

        input[type="file"]:hover {
            border-color: var(--pink);
            background: #fff5f8;
        }

        .form-control:hover {
            border-color: var(--mint-deep);
            background: #f1fcf8;
        }

        button {
            border: none;
            padding: 12px 26px;
            border-radius: 999px;
            font-family: 'Baloo 2', sans-serif;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            color: var(--white);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease, filter 0.2s ease;
            white-space: nowrap;
        }

        button:hover {
            transform: translateY(-3px) scale(1.03);
        }

        button:active {
            transform: translateY(0) scale(0.98);
        }

        form[action="/upload"] button {
            background: linear-gradient(135deg, var(--pink), var(--pink-deep));
            box-shadow: 0 6px 0 var(--pink-deep), 0 10px 20px -6px rgba(255, 111, 160, 0.5);
        }
        form[action="/upload"] button:hover {
            box-shadow: 0 8px 0 var(--pink-deep), 0 14px 26px -6px rgba(255, 111, 160, 0.55);
        }
        form[action="/upload"] button:active {
            box-shadow: 0 3px 0 var(--pink-deep);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--lilac), var(--lilac-deep));
            box-shadow: 0 6px 0 var(--lilac-deep), 0 10px 20px -6px rgba(155, 125, 240, 0.5);
        }
        .btn-success:hover { box-shadow: 0 8px 0 var(--lilac-deep), 0 14px 26px -6px rgba(155, 125, 240, 0.55); }
        .btn-success:active { box-shadow: 0 3px 0 var(--lilac-deep); }

        .btn-warning {
            background: linear-gradient(135deg, var(--mint), var(--mint-deep));
            box-shadow: 0 6px 0 var(--mint-deep), 0 10px 20px -6px rgba(62, 207, 168, 0.5);
        }
        .btn-warning:hover { box-shadow: 0 8px 0 var(--mint-deep), 0 14px 26px -6px rgba(62, 207, 168, 0.55); }
        .btn-warning:active { box-shadow: 0 3px 0 var(--mint-deep); }

        /* ===== GRID ===== */
        .grid-row {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .col-md-4 { width: 100%; }

        .card {
            position: relative;
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 28px;
            overflow: visible;
            box-shadow: 0 10px 0 -4px rgba(111, 227, 196, 0.2), 0 16px 32px -12px rgba(111, 227, 196, 0.2);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-4px) rotate(0.3deg);
            border-color: var(--mint-deep);
            box-shadow: 0 14px 0 -4px rgba(111, 227, 196, 0.28), 0 22px 40px -12px rgba(111, 227, 196, 0.28);
        }

        .card-body { padding: 30px 28px; }
        .card-body.text-center { text-align: left; }

        .card-body.text-center form {
            flex-direction: column;
            align-items: stretch;
            margin-top: 20px;
        }

        .card-body.text-center button { width: 100%; }

        .shadow { box-shadow: none; }
        .section { margin-bottom: 10px; }

        /* footer */
        .footnote {
            margin-top: 52px;
            padding: 16px 24px;
            background: var(--white);
            border: 2px solid var(--line);
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink-soft);
        }

        .footnote .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--mint-deep);
            margin-right: 8px;
            box-shadow: 0 0 0 4px rgba(62, 207, 168, 0.18);
        }

        @media (max-width: 760px) {
            .grid-row { grid-template-columns: 1fr; }
        }

        @media (max-width: 576px) {
            .navbar .container { flex-direction: column; gap: 10px; padding: 14px 18px; }
            .page-wrapper { padding: 40px 16px 60px; }
            form { flex-direction: column; align-items: stretch; }
            button { width: 100%; }
            .panel, .card-body { padding: 24px 20px; }
            .footnote { border-radius: 20px; justify-content: center; text-align: center; }
        }
    </style>
</head>
<body>

    <span class="blob b1"></span>
    <span class="blob b2"></span>
    <span class="blob b3"></span>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

        <div class="container">

            <a class="navbar-brand" href="/dashboard">
                OCR CCCD
            </a>

            <div class="navbar-nav">

                <a class="nav-link" href="/">
                    Upload OCR
                </a>

                <a class="nav-link" href="/documents">
                    Danh sách hồ sơ
                </a>

            </div>

        </div>

    </nav>

    <div class="page-wrapper">

        <div class="page-header">
            <span class="eyebrow">✨ Trợ lý hồ sơ của bạn</span>
            <h1>Hệ thống <span class="accent">OCR CCCD</span> 🌸</h1>
            <p class="subtext">Tải ảnh CCCD, file Word hoặc Excel lên đây, để mình đọc giúp bạn nha!</p>
        </div>

        <div class="panel section">

            <div class="panel-head">
                <div class="panel-title-row">
                    <span class="panel-icon" style="background:#ffe3ec;">🪪</span>
                    <h3>Quét CCCD từ ảnh</h3>
                </div>
                <span class="panel-tag">Bước 1</span>
            </div>

            <form action="/upload" method="POST" enctype="multipart/form-data">

                @csrf

                <input type="file" name="image">

                <button type="submit">
                    Upload 💗
                </button>

            </form>

        </div>

        <hr>

        <div class="grid-row">

            <div class="panel section">

                <div class="panel-head">
                    <div class="panel-title-row">
                        <span class="panel-icon" style="background:#ece3ff;">📄</span>
                        <h3>Đọc file Word</h3>
                    </div>
                    <span class="panel-tag">.docx</span>
                </div>

                <form action="{{ route('upload.word') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf

                    <input type="file"
                           name="word_file"
                           class="form-control">

                    <br>

                    <button class="btn btn-success">
                        Đọc Word 💜
                    </button>

                </form>

            </div>

            <div class="col-md-4">

                <div class="card shadow">

                    <div class="card-body text-center">

                        <div class="panel-head">
                            <div class="panel-title-row">
                                <span class="panel-icon" style="background:#dffaf2;">📊</span>
                                <h5>Đọc file Excel</h5>
                            </div>
                            <span class="panel-tag">.xlsx</span>
                        </div>

                        <form action="{{ route('upload.excel') }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf

                            <input type="file"
                                   name="excel_file"
                                   class="form-control">

                            <br>

                            <button class="btn btn-warning">
                                Upload Excel 🌿
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <div class="footnote">
            <span><span class="dot"></span>Sẵn sàng nhận file của bạn rồi nè</span>
            <span>Hỗ trợ JPG · PNG · DOCX · XLSX</span>
        </div>

    </div>

</body>
</html>