@extends('layouts.ocr')

@section('title', 'Kết quả OCR mặt sau')

@section('content')

    <h1 class="page-title">Kết quả OCR mặt sau</h1>

    <div class="card-box">
        <table class="table table-bordered">
            <tr><th style="width:30%">Ngày cấp</th><td>{{ $issueDate ?? '' }}</td></tr>
            <tr><th>Ngày hết hạn</th><td>{{ $expireDate ?? '' }}</td></tr>
            <tr><th>Nơi cấp</th><td>{{ $issuedBy ?? '' }}</td></tr>
            <tr><th>Đặc điểm nhận dạng</th><td>{{ $features ?? '' }}</td></tr>
        </table>

        <details class="mt-3">
            <summary class="text-muted" style="cursor:pointer">Văn bản OCR gốc (mặt sau)</summary>
            <pre class="bg-light p-3 rounded mt-2">{{ $rawText }}</pre>
        </details>

        <a href="javascript:history.back()" class="btn btn-primary mt-3">← Quay lại</a>
    </div>

@endsection
