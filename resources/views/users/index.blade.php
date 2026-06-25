<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý nhân viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700;800&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #fdf6ff; --ink: #4a3b52; --ink-soft: #8a7a92; --white: #ffffff;
            --pink: #ff8fb1; --pink-deep: #ff6fa0; --lilac: #b9a3ff; --lilac-deep: #9b7df0;
            --mint: #6fe3c4; --mint-deep: #3ecfa8; --red: #ff6b6b; --red-deep: #f24f4f; --line: #f3def0;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Quicksand', sans-serif;
            background: radial-gradient(circle at 6% 8%, rgba(255,143,177,0.14), transparent 38%),
                radial-gradient(circle at 95% 12%, rgba(185,163,255,0.16), transparent 40%), var(--bg);
            min-height: 100vh; color: var(--ink);
        }
        .container { max-width: 900px; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 6px; background: var(--white);
            border: 2px solid var(--line); color: var(--pink-deep); font-weight: 700;
            font-size: 0.76rem; padding: 5px 14px; border-radius: 999px; margin-bottom: 12px;
        }
        h2 { font-family: 'Baloo 2', sans-serif; font-weight: 800; color: var(--ink); }
        h2 .accent { color: var(--pink-deep); }
        .alert-success { background: #e7f0e8; border: 2px solid #c8e0cb; color: #2f6b46; border-radius: 14px; }
        .alert-danger { background: #fbeaea; border: 2px solid #f3c6c6; color: #9b2c2c; border-radius: 14px; }
        .table-wrap {
            background: var(--white); border: 2.5px solid var(--line); border-radius: 22px;
            padding: 8px; margin-top: 20px; box-shadow: 0 8px 0 -4px rgba(111,227,196,0.16), 0 14px 28px -12px rgba(111,227,196,0.18);
        }
        thead tr th {
            font-family: 'Baloo 2', sans-serif; font-weight: 700; font-size: 0.86rem;
            background: #fff0f6; border: none !important; padding: 14px 16px;
        }
        tbody tr td { font-weight: 500; padding: 14px 16px; border: none !important; border-bottom: 2px solid #faf2fa !important; vertical-align: middle; }
        .badge-role { padding: 4px 12px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; }
        .badge-admin { background: #ffe3ec; color: var(--pink-deep); }
        .badge-user { background: #e9f7f1; color: var(--mint-deep); }
        select.form-select { border-radius: 12px; border: 2px solid var(--line); font-family: 'Quicksand', sans-serif; font-weight: 600; }
        .btn { font-family: 'Baloo 2', sans-serif; font-weight: 700; border: none !important; border-radius: 999px !important; }
        .btn-sm { font-size: 0.78rem; padding: 6px 14px; }
        .btn-primary { background: linear-gradient(135deg, var(--pink), var(--pink-deep)) !important; color: white !important; }
        .btn-danger { background: linear-gradient(135deg, var(--red), var(--red-deep)) !important; color: white !important; }
        .btn-secondary { background: #c9c2d6 !important; color: white !important; }
    </style>
</head>
<body>

<div class="container mt-4">

    <span class="eyebrow">🔑 Quản lý quyền truy cập</span>
    <h2 class="mb-4">Danh sách <span class="accent">nhân viên</span> 🪪</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-wrap">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Quyền hiện tại</th>
                    <th>Đổi quyền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge-role {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                            {{ $user->role === 'admin' ? 'Quản lý' : 'Nhân viên' }}
                        </span>
                    </td>
                    <td>
                        @if ($user->id !== auth()->id())
                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="role" class="form-select form-select-sm" style="width:auto;">
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Nhân viên</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Quản lý</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Lưu</button>
                        </form>
                        @else
                            <span class="text-muted">(Tài khoản của bạn)</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Xóa tài khoản này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">← Quay lại Dashboard</a>
    </div>

</div>

</body>
</html>