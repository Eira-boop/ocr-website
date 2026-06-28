@extends('layouts.ocr')

@section('title', 'Kết quả Excel')

@section('content')

    <h1 class="page-title">Dữ liệu Excel</h1>

    <div class="card-box">
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

@endsection
