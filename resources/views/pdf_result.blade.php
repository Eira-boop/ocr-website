@extends('layouts.ocr')

@section('title', 'Kết quả PDF')

@section('content')

    <h1 class="page-title">Nội dung PDF</h1>

    <div class="card-box">
        <pre class="mb-0">{{ $text }}</pre>
    </div>

@endsection
