<!DOCTYPE html>
<html>
<head>
    <title>Kết quả OCR</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 30px;
        }
        .container {
            width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2563eb;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        table th {
            background: #2563eb;
            color: white;
            width: 30%;
        }
        .raw {
            margin-top: 30px;
            background: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
        }
        .btn {
            background: #2563eb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover { background: #1d4ed8; }
        .btn-green {
            background: #16a34a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-green:hover { background: #15803d; }
        input[type=text] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        .export-box {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fafafa;
        }
        .section-title {
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 6px;
            margin-top: 30px;
        }
        .back-upload-box {
            margin-top: 30px;
            padding: 20px;
            border: 2px dashed #2563eb;
            border-radius: 8px;
            background: #eff6ff;
        }
        .back-result-box {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #16a34a;
            border-radius: 8px;
            background: #f0fdf4;
        }
        .back-result-box h3 {
            color: #16a34a;
            margin-top: 0;
        }
        table.back-table th {
            background: #16a34a;
        }
    </style>
</head>
<body>

<div class="container">

    {{-- ── MẶT TRƯỚC ─────────────────────────────────────────────── --}}
    <h2>THÔNG TIN CCCD MẶT TRƯỚC</h2>

    <table>
        <tr>
            <th>Số CCCD</th>
            <td>{{ $cccd }}</td>
        </tr>
        <tr>
            <th>Họ tên</th>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <th>Ngày sinh</th>
            <td>{{ $dob }}</td>
        </tr>
        <tr>
            <th>Giới tính</th>
            <td>{{ $gender }}</td>
        </tr>
    </table>

    <div class="raw">
        <h3>Văn bản OCR gốc (mặt trước)</h3>
        <pre>{{ $rawText }}</pre>
    </div>

    {{-- ── UPLOAD MẶT SAU ─────────────────────────────────────────── --}}
    <div class="back-upload-box">
        <h3 class="section-title">📷 Upload ảnh CCCD mặt sau</h3>

        <form action="{{ route('upload.back') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="document_id" value="{{ $documentId ?? '' }}">
            <input type="file" name="image" accept="image/*" required style="margin-bottom:10px;">
            <br>
            <button type="submit" class="btn-green">Đọc mặt sau</button>
        </form>
    </div>

    {{-- ── KẾT QUẢ MẶT SAU (chỉ hiện sau khi upload) ──────────────── --}}
    @if(!empty($issueDate) || !empty($issuedBy) || !empty($expireDate) || !empty($features))
    <div class="back-result-box">
        <h3>✅ Thông tin CCCD mặt sau</h3>
        <table class="back-table">
            <tr>
                <th>Ngày cấp</th>
                <td>{{ $issueDate ?? '' }}</td>
            </tr>
            <tr>
                <th>Ngày hết hạn</th>
                <td>{{ $expireDate ?? '' }}</td>
            </tr>
            <tr>
                <th>Nơi cấp</th>
                <td>{{ $issuedBy ?? '' }}</td>
            </tr>
            <tr>
                <th>Đặc điểm nhận dạng</th>
                <td>{{ $features ?? '' }}</td>
            </tr>
        </table>

        @if(!empty($rawTextBack))
        <div class="raw" style="margin-top:15px;">
            <h4>Văn bản OCR gốc (mặt sau)</h4>
            <pre>{{ $rawTextBack }}</pre>
        </div>
        @endif
    </div>
    @endif

    {{-- ── XUẤT FILE ───────────────────────────────────────────────── --}}
    <div class="export-box">

        <h3>Chọn thông tin muốn xuất Word</h3>

        <form action="{{ route('preview') }}" method="POST">
            @csrf

            <table>
                <tr>
                    <td>
                        <input type="checkbox" name="fields[]" value="cccd" checked>
                        Số CCCD
                    </td>
                    <td>
                        <input type="checkbox" name="fields[]" value="name" checked>
                        Họ tên
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name="fields[]" value="dob" checked>
                        Ngày sinh
                    </td>
                    <td>
                        <input type="checkbox" name="fields[]" value="gender" checked>
                        Giới tính
                    </td>
                </tr>
            </table>

            <br>
            <h4>Thêm trường mới</h4>
            <input type="text" name="new_field_name"  placeholder="Ví dụ: Email">
            <input type="text" name="new_field_value" placeholder="Ví dụ: abc@gmail.com">

            <input type="hidden" name="cccd"   value="{{ $cccd }}">
            <input type="hidden" name="name"   value="{{ $name }}">
            <input type="hidden" name="dob"    value="{{ $dob }}">
            <input type="hidden" name="gender" value="{{ $gender }}">

            <br>
            <button type="submit" class="btn">Xem kết quả</button>
        </form>

        <hr>

        <form action="/export-excel" method="POST">
            @csrf
            <input type="hidden" name="cccd"   value="{{ $cccd }}">
            <input type="hidden" name="name"   value="{{ $name }}">
            <input type="hidden" name="dob"    value="{{ $dob }}">
            <input type="hidden" name="gender" value="{{ $gender }}">
            <button type="submit" class="btn">Xuất Excel</button>
        </form>

    </div>

</div>

</body>
</html>