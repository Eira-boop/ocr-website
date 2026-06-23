<!DOCTYPE html>
<html>
<head>
    <title>Kết quả Excel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Dữ liệu Excel</h2>

    <table class="table table-bordered">

        @foreach($data as $row)

        <tr>

            @foreach($row as $cell)

            <td>{{ $cell }}</td>

            @endforeach

        </tr>

        @endforeach

    </table>

</div>

</body>
</html>