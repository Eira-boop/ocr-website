@extends('layouts.ocr')

@section('title','Thống kê')

@section('content')

<div class="container-fluid">

    <h2 class="mb-4">
        📊 Thống kê hệ thống OCR
    </h2>

    <div class="row">

        <div class="col-md-3 mb-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">

                    <h5>Tổng tài liệu</h5>

                    <h1 class="text-primary">
                        {{ $totalDocuments }}
                    </h1>

                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">

                    <h5>Hôm nay</h5>

                    <h1 class="text-success">
                        {{ $todayDocuments }}
                    </h1>

                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">

                    <h5>Người dùng</h5>

                    <h1 class="text-warning">
                        {{ $totalUsers }}
                    </h1>

                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card shadow border-0">
                <div class="card-body text-center">

                    <h5>OCR thành công</h5>

                    <h1 class="text-info">
                        {{ $successOCR }}
                    </h1>

                </div>
            </div>
        </div>

    </div>

</div>

@endsection