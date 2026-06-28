@extends('layouts.ocr')

@section('title', 'Kết quả Word')

@section('content')

    <h1 class="page-title">Nội dung file Word</h1>

    <div class="card-box">
        <pre class="mb-0">{{ $content }}</pre>
    </div>

@endsection
