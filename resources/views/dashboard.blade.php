@extends('layouts.ocr')

@section('title', 'Dashboard')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="page-title mb-2">

            <i class="bi bi-speedometer2 text-primary"></i>

            Dashboard

        </h1>

        <p class="page-subtitle">

            Xin chào <strong>{{ auth()->user()->name }}</strong> 👋

            Hôm nay là

            <strong>{{ now()->format('d/m/Y') }}</strong>

        </p>

    </div>

    <div class="text-end">

        <span class="badge bg-success fs-6">

            Hệ thống đang hoạt động

        </span>

    </div>

</div>

<!-- Thống kê -->

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <i class="bi bi-folder2-open display-4 text-primary"></i>

            <h6 class="mt-3 text-muted">

                Tổng tài liệu

            </h6>

            <h2 class="fw-bold text-primary">

                {{ $totalDocuments }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <i class="bi bi-calendar-check display-4 text-success"></i>

            <h6 class="mt-3 text-muted">

                Hôm nay

            </h6>

            <h2 class="fw-bold text-success">

                {{ $todayDocuments ?? 0 }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <i class="bi bi-people-fill display-4 text-warning"></i>

            <h6 class="mt-3 text-muted">

                Người dùng

            </h6>

            <h2 class="fw-bold text-warning">

                {{ $totalUsers ?? 0 }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <i class="bi bi-check-circle-fill display-4 text-info"></i>

            <h6 class="mt-3 text-muted">

                Thành công

            </h6>

            <h2 class="fw-bold text-info">

                {{ $totalDocuments }}

            </h2>

        </div>

    </div>

</div>

<!-- Thao tác nhanh -->

<div class="row g-4">

    <div class="col-lg-8">

        <div class="card-box">

            <h4 class="mb-4">

                <i class="bi bi-lightning-charge-fill text-warning"></i>

                Thao tác nhanh

            </h4>

            <div class="row g-3">

                <div class="col-md-4">

                    <a href="{{ url('/ocr') }}"
                       class="btn btn-primary w-100 p-4">

                        <i class="bi bi-cloud-upload fs-2 d-block mb-2"></i>

                        Quét OCR

                    </a>

                </div>

                <div class="col-md-4">

                    <a href="{{ Route::has('documents.list') ? route('documents.list') : url('/documents') }}"
                       class="btn btn-outline-primary w-100 p-4">

                        <i class="bi bi-folder2-open fs-2 d-block mb-2"></i>

                        Hồ sơ

                    </a>

                </div>

                @if((auth()->user()->role ?? null)=='admin')

                <div class="col-md-4">

                    <a href="{{ route('export.all.excel') }}"
                       class="btn btn-success w-100 p-4">

                        <i class="bi bi-file-earmark-excel fs-2 d-block mb-2"></i>

                        Xuất Excel

                    </a>

                </div>

                @endif

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card-box">

            <h4 class="mb-4">

                <i class="bi bi-pie-chart-fill text-primary"></i>

                Tiến trình OCR

            </h4>

            <div class="mb-3">

                OCR tài liệu

                <div class="progress mt-2">

                    <div class="progress-bar bg-primary"

                         style="width:90%">

                        90%

                    </div>

                </div>

            </div>

            <div class="mb-3">

                Xuất Excel

                <div class="progress mt-2">

                    <div class="progress-bar bg-success"

                         style="width:95%">

                        95%

                    </div>

                </div>

            </div>

            <div>

                PDF OCR

                <div class="progress mt-2">

                    <div class="progress-bar bg-warning"

                         style="width:80%">

                        80%

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Hoạt động -->

<div class="row mt-4">

    <div class="col-lg-12">

        <div class="card-box">

            <h4 class="mb-4">

                <i class="bi bi-clock-history text-primary"></i>

                Hoạt động gần đây

            </h4>

            <table class="table table-hover">

                <thead>

                <tr>

                    <th>Loại tài liệu</th>

                    <th>Người xử lý</th>

                    <th>Thời gian</th>

                    <th>Trạng thái</th>

                </tr>

                </thead>

                <tbody>

                <tr>

                    <td>CCCD</td>

                    <td>{{ auth()->user()->name }}</td>

                    <td>{{ now()->format('d/m/Y H:i') }}</td>

                    <td>

                        <span class="badge bg-success">

                            Thành công

                        </span>

                    </td>

                </tr>

                <tr>

                    <td>PDF</td>

                    <td>{{ auth()->user()->name }}</td>

                    <td>{{ now()->subMinutes(10)->format('d/m/Y H:i') }}</td>

                    <td>

                        <span class="badge bg-success">

                            Thành công

                        </span>

                    </td>

                </tr>

                <tr>

                    <td>Passport</td>

                    <td>{{ auth()->user()->name }}</td>

                    <td>{{ now()->subHour()->format('d/m/Y H:i') }}</td>

                    <td>

                        <span class="badge bg-warning">

                            Đang xử lý

                        </span>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection