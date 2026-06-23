<!DOCTYPE html>
<html>
<head>
    <title>Kết quả lựa chọn</title>

    <style>
        body{
            font-family: Arial;
            padding:30px;
            background:#f5f5f5;
        }

        .box{
            width:700px;
            margin:auto;
            background:white;
            padding:20px;
            border-radius:10px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        td,th{
            border:1px solid #ddd;
            padding:10px;
        }

        th{
            background:#2563eb;
            color:white;
        }
    </style>
</head>
<body>

<div class="box">

    <h2>Kết quả theo lựa chọn</h2>

    <table>

        <tr>
            <th>Thông tin</th>
            <th>Giá trị</th>
        </tr>

        @foreach($result as $key => $value)

        <tr>
            <td>{{ $key }}</td>
            <td>{{ $value }}</td>
        </tr>

        @endforeach

    </table>
    <hr>

<form action="{{ route('export.selected.word') }}"
      method="POST">

    @csrf

@foreach($result as $key => $value)

<input type="hidden"
       name="keys[]"
       value="{{ $key }}">

<input type="hidden"
       name="values[]"
       value="{{ $value }}">

@endforeach

    <button type="submit">
        Tải Word
    </button>

</form>

    <br>

    <a href="javascript:history.back()">
        Quay lại
    </a>

</div>

</body>
</html>