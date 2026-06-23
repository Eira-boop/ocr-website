<!DOCTYPE html>
<html>
<head>
    <title>Kết quả OCR</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            padding:30px;
        }

        .container{
            width:900px;
            margin:auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align:center;
            color:#2563eb;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th,
        table td{
            border:1px solid #ddd;
            padding:12px;
        }

        table th{
            background:#2563eb;
            color:white;
            width:30%;
        }

        .raw{
            margin-top:30px;
            background:#f8f8f8;
            padding:15px;
            border-radius:5px;
        }

        .btn{
            background:#2563eb;
            color:white;
            border:none;
            padding:10px 20px;
            border-radius:5px;
            cursor:pointer;
        }

        .btn:hover{
            background:#1d4ed8;
        }

        input[type=text]{
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:10px;
        }

        .export-box{
            margin-top:30px;
            padding:15px;
            border:1px solid #ddd;
            border-radius:8px;
            background:#fafafa;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>THÔNG TIN CCCD</h2>
    <h1 style="color:red">
CHECKBOX TEST
</h1>

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
        <h3>Văn bản OCR gốc</h3>
        <pre>{{ $rawText }}</pre>
    </div>

    <div class="export-box">

        <h3>Chọn thông tin muốn xuất Word</h3>

        <form action="{{ route('preview') }}" method="POST">

            @csrf

            <table>

                <tr>
                    <td>
                        <input type="checkbox"
                               name="fields[]"
                               value="cccd"
                               checked>

                        Số CCCD
                    </td>

                    <td>
                        <input type="checkbox"
                               name="fields[]"
                               value="name"
                               checked>

                        Họ tên
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="checkbox"
                               name="fields[]"
                               value="dob"
                               checked>

                        Ngày sinh
                    </td>

                    <td>
                        <input type="checkbox"
                               name="fields[]"
                               value="gender"
                               checked>

                        Giới tính
                    </td>
                </tr>

            </table>

            <br>

            <h4>Thêm trường mới</h4>

            <input type="text"
                   name="new_field_name"
                   placeholder="Ví dụ: Email">

            <input type="text"
                   name="new_field_value"
                   placeholder="Ví dụ: abc@gmail.com">

            <input type="hidden"
                   name="cccd"
                   value="{{ $cccd }}">

            <input type="hidden"
                   name="name"
                   value="{{ $name }}">

            <input type="hidden"
                   name="dob"
                   value="{{ $dob }}">

            <input type="hidden"
                   name="gender"
                   value="{{ $gender }}">

            <br>

           <button type="submit" class="btn">
    Xem kết quả
</button>

        </form>
        <hr>

<form action="/export-excel" method="POST">

    @csrf

    <input type="hidden" name="cccd" value="{{ $cccd }}">
    <input type="hidden" name="name" value="{{ $name }}">
    <input type="hidden" name="dob" value="{{ $dob }}">
    <input type="hidden" name="gender" value="{{ $gender }}">

    <button type="submit">
        Xuất Excel
    </button>

</form>

    </div>

</div>

</body>
</html>