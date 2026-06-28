@extends('layouts.ocr')

@section('title', 'Kết quả lựa chọn')

@section('content')

    <h1 class="page-title">Kết quả theo lựa chọn</h1>

    <div class="card-box">
        <table class="table table-bordered">
            <tr><th>Thông tin</th><th>Giá trị</th></tr>
            @foreach($result as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </table>

        <hr>

        <form action="{{ route('export.selected.word') }}" method="POST" class="d-flex gap-2">
            @csrf
            @foreach($result as $key => $value)
                <input type="hidden" name="keys[]" value="{{ $key }}">
                <input type="hidden" name="values[]" value="{{ $value }}">
            @endforeach
            <button type="submit" class="btn btn-primary">Tải Word</button>
            <a href="javascript:history.back()" class="btn btn-outline-primary">Quay lại</a>
        </form>
    </div>

@endsection
