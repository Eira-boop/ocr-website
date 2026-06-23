<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách hồ sơ CCCD</title>
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
            --red-deep: #f24f4f;
            --line: #f3def0;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Quicksand', sans-serif;
            background:
                radial-gradient(circle at 6% 8%, rgba(255, 143, 177, 0.14), transparent 38%),
                radial-gradient(circle at 95% 12%, rgba(185, 163, 255, 0.16), transparent 40%),
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
        .blob.b1 { top: 6%; left: 3%; width: 60px; height: 60px; background: var(--yellow); animation-delay: 0s; }
        .blob.b2 { top: 70%; right: 5%; width: 46px; height: 46px; background: var(--mint); animation-delay: 2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-16px) rotate(8deg); }
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
        }

        h2 .accent { color: var(--pink-deep); }

        /* ===== SEARCH BAR ===== */
        form[method="GET"] {
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 22px;
            padding: 18px 20px;
            margin-top: 18px;
            box-shadow: 0 8px 0 -4px rgba(255, 143, 177, 0.15), 0 12px 26px -10px rgba(155, 125, 240, 0.15);
        }

        form[method="GET"] .row {
            --bs-gutter-x: 0.75rem;
            align-items: center;
        }

        .form-control {
            border: 2.5px dashed #e9d5e7;
            border-radius: 16px;
            background: #fff9fb;
            font-family: 'Quicksand', sans-serif;
            font-weight: 500;
            padding: 10px 16px;
            color: var(--ink);
            transition: border-color 0.25s ease, background 0.25s ease;
        }

        .form-control:focus {
            border-color: var(--pink);
            background: #fff5f8;
            box-shadow: 0 0 0 4px rgba(255, 143, 177, 0.15);
        }

        .form-control::placeholder { color: var(--ink-soft); }

        /* ===== BUTTONS (pill, 3D candy style) ===== */
        .btn {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            border: none !important;
            border-radius: 999px !important;
            color: var(--white) !important;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.2s ease, filter 0.2s ease !important;
        }

        .btn:hover { transform: translateY(-2px) scale(1.02); filter: brightness(1.04); }
        .btn:active { transform: translateY(0) scale(0.97); }

        .btn-primary {
            background: linear-gradient(135deg, var(--pink), var(--pink-deep)) !important;
            box-shadow: 0 5px 0 var(--pink-deep), 0 8px 16px -6px rgba(255, 111, 160, 0.5);
            padding: 10px 22px;
        }
        .btn-primary:hover { box-shadow: 0 7px 0 var(--pink-deep), 0 12px 22px -6px rgba(255, 111, 160, 0.55); }
        .btn-primary:active { box-shadow: 0 2px 0 var(--pink-deep); }

        .btn-info {
            background: linear-gradient(135deg, var(--lilac), var(--lilac-deep)) !important;
            box-shadow: 0 3px 0 var(--lilac-deep), 0 6px 12px -4px rgba(155, 125, 240, 0.45);
        }
        .btn-info:hover { box-shadow: 0 5px 0 var(--lilac-deep), 0 9px 16px -4px rgba(155, 125, 240, 0.5); }
        .btn-info:active { box-shadow: 0 1px 0 var(--lilac-deep); }

        .btn-danger {
            background: linear-gradient(135deg, var(--red), var(--red-deep)) !important;
            box-shadow: 0 3px 0 var(--red-deep), 0 6px 12px -4px rgba(242, 79, 79, 0.4);
        }
        .btn-danger:hover { box-shadow: 0 5px 0 var(--red-deep), 0 9px 16px -4px rgba(242, 79, 79, 0.45); }
        .btn-danger:active { box-shadow: 0 1px 0 var(--red-deep); }

        .btn-sm { font-size: 0.8rem; padding: 6px 14px; }

        /* ===== TABLE ===== */
        .table-wrap {
            background: var(--white);
            border: 2.5px solid var(--line);
            border-radius: 22px;
            padding: 8px;
            margin-top: 24px;
            box-shadow: 0 8px 0 -4px rgba(111, 227, 196, 0.16), 0 14px 28px -12px rgba(111, 227, 196, 0.18);
            overflow-x: auto;
        }

        table.table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        table.table-bordered, table.table-bordered td, table.table-bordered th {
            border: none;
        }

        thead tr th {
            font-family: 'Baloo 2', sans-serif;
            font-weight: 700;
            font-size: 0.86rem;
            color: var(--ink);
            background: #fff0f6;
            border: none !important;
            padding: 14px 16px;
            white-space: nowrap;
        }

        thead tr th:first-child { border-radius: 14px 0 0 14px; }
        thead tr th:last-child { border-radius: 0 14px 14px 0; }

        tbody tr td {
            font-weight: 500;
            font-size: 0.92rem;
            padding: 14px 16px;
            border: none !important;
            border-bottom: 2px solid #faf2fa !important;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none !important; }

        tbody tr {
            transition: background 0.2s ease;
        }

        tbody tr:hover {
            background: #fff7fb;
        }

        tbody td:first-child { color: var(--ink-soft); font-weight: 600; }
        tbody td:nth-child(3) { font-weight: 700; color: var(--ink); }

        .badge-gender {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        /* empty state */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--ink-soft);
        }

        .empty-state .emoji { font-size: 2.4rem; display: block; margin-bottom: 10px; }

        /* pagination */
        .pagination-wrap {
            margin-top: 26px;
            display: flex;
            justify-content: center;
        }

        .pagination .page-link {
            border: none;
            color: var(--ink-soft);
            font-weight: 600;
            font-family: 'Quicksand', sans-serif;
            margin: 0 3px;
            border-radius: 999px !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--pink-deep);
            color: var(--white);
            box-shadow: 0 4px 10px -2px rgba(255, 111, 160, 0.5);
        }

        .pagination .page-link:hover {
            background: #ffe3ec;
            color: var(--pink-deep);
        }

        @media (max-width: 576px) {
            form[method="GET"] .row > div { margin-bottom: 10px; }
            .table-wrap { padding: 4px; border-radius: 16px; }
        }
    </style>
</head>
<body>

<span class="blob b1"></span>
<span class="blob b2"></span>

<div class="container mt-4">

    <span class="eyebrow">📋 Quản lý hồ sơ</span>

    <h2 class="mb-4">Danh sách hồ sơ <span class="accent">CCCD</span> 🌸</h2>

    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input
                    type="text"
                    name="keyword"
                    class="form-control"
                    placeholder="🔍 Nhập CCCD hoặc họ tên">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary">
                    Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <div class="table-wrap">

        <table class="table table-bordered">

            <thead>
            <tr>
                <th>ID</th>
                <th>CCCD</th>
                <th>Họ tên</th>
                <th>Ngày sinh</th>
                <th>Giới tính</th>
                <th>Thao tác</th>
            </tr>
            </thead>

            <tbody>

            @forelse($documents as $doc)

            <tr>
                <td>{{ $doc->id }}</td>
                <td>{{ $doc->id_number }}</td>
                <td>{{ $doc->full_name }}</td>
                <td>{{ $doc->birth_date }}</td>
                <td>{{ $doc->gender }}</td>

                <td>

                    <a href="{{ route('documents.show',$doc->id) }}"
                       class="btn btn-info btn-sm">
                        Xem
                    </a>

                    <form
                        action="{{ route('documents.destroy',$doc->id) }}"
                        method="POST"
                        style="display:inline">

                        @csrf
                        @method('DELETE')

                        <button
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn muốn xóa?')">
                            Xóa
                        </button>

                    </form>

                </td>
            </tr>

            @empty

            <tr>
                <td colspan="6">
                    <div class="empty-state">
                        <span class="emoji">🗂️</span>
                        Chưa có hồ sơ nào được tìm thấy
                    </div>
                </td>
            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="pagination-wrap">
        {{ $documents->links() }}
    </div>

</div>

</body>
</html>