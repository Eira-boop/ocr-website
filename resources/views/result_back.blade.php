<!DOCTYPE html>
<html>
<head>
    <title>Kết quả OCR mặt sau</title>
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
        h2 { text-align: center; color: #16a34a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 12px; }
        table th { background: #16a34a; color: white; width: 30%; }
        .raw { margin-top: 30px; background: #f8f8f8; padding: 15px; border-radius: 5px; }
        .btn { background: #2563eb; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
<div class="container">

    <h2>THÔNG TIN CCCD MẶT SAU</h2>

    <table>
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

    <div class="raw">
        <h3>Văn bản OCR gốc (mặt sau)</h3>
        <pre>{{ $rawText }}</pre>
    </div>

    <a href="javascript:history.back()" class="btn">← Quay lại</a>

</div>
</body>
</html>