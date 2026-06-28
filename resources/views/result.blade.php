@extends('layouts.ocr')

@section('title', 'Kết quả OCR')

@section('content')

@php
    $type = $document->document_type ?? ($result['document_type'] ?? 'other');

    // Nhãn hiển thị cho từng loại field, dùng chung cho mọi document_type
    $fieldLabels = [
        'id_number'        => 'Số CCCD',
        'full_name'        => 'Họ tên',
        'birth_date'       => 'Ngày sinh',
        'gender'           => 'Giới tính',
        'nationality'      => 'Quốc tịch',
        'address'          => 'Địa chỉ',
        'passport_number'  => 'Số hộ chiếu',
        'student_id'       => 'Mã học sinh/sinh viên',
        'school_name'      => 'Trường',
        'class_name'       => 'Lớp',
        'major'            => 'Ngành học',
        'gpa'              => 'GPA',
        'classification'   => 'Xếp loại',
        'father_name'      => 'Họ tên cha',
        'mother_name'      => 'Họ tên mẹ',
        'ethnic'           => 'Dân tộc',
        'place_of_birth'   => 'Nơi sinh',
        'issue_date'       => 'Ngày cấp',
    ];

    $typeNames = [
        'cccd'       => 'CCCD',
        'passport'   => 'Hộ chiếu',
        'birth'      => 'Giấy khai sinh',
        'report'     => 'Học bạ',
        'transcript' => 'Bảng điểm',
        'degree'     => 'Bằng tốt nghiệp',
        'other'      => 'Tài liệu khác',
    ];
@endphp

    <h1 class="page-title">Kết quả OCR</h1>
    <p class="page-subtitle">Thông tin trích xuất từ ảnh {{ $typeNames[$type] ?? 'tài liệu' }}.</p>

    {{-- ── THÔNG TIN TRÍCH XUẤT (động theo loại tài liệu) ──────────── --}}
    <div class="card-box mb-4">
        <h5 class="mb-3">Thông tin {{ $typeNames[$type] ?? '' }}</h5>
        <table class="table table-bordered">
            @foreach($fieldLabels as $key => $label)
                @if(!empty($document->$key ?? $result[$key] ?? null))
                    <tr>
                        <th style="width:30%">{{ $label }}</th>
                        <td>{{ $document->$key ?? $result[$key] }}</td>
                    </tr>
                @endif
            @endforeach
        </table>

        <details class="mt-3">
            <summary class="text-muted" style="cursor:pointer">Văn bản OCR gốc</summary>
            <pre class="bg-light p-3 rounded mt-2">{{ $document->raw_text ?? ($result['raw_text'] ?? '') }}</pre>
        </details>
    </div>

    @if($type === 'cccd')

    {{-- ── UPLOAD MẶT SAU (chỉ áp dụng cho CCCD) ───────────────────── --}}
    <div class="card-box mb-4">
        <h5 class="mb-3">📷 Upload ảnh CCCD mặt sau</h5>
        <form action="{{ route('upload.back') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 flex-wrap">
            @csrf
            <input type="hidden" name="document_id" value="{{ $documentId ?? '' }}">
            <input type="file" name="image" accept="image/*" required class="form-control" style="max-width:320px">
            <button type="submit" class="btn btn-primary">Đọc mặt sau</button>
        </form>
    </div>

    {{-- ── KẾT QUẢ MẶT SAU (chỉ hiện sau khi upload) ──────────────── --}}
    @if(!empty($issueDate) || !empty($issuedBy) || !empty($expireDate) || !empty($features))
    <div class="card-box mb-4">
        <h5 class="mb-3">✅ Thông tin CCCD mặt sau</h5>
        <table class="table table-bordered">
            <tr><th style="width:30%">Ngày cấp</th><td>{{ $issueDate ?? '' }}</td></tr>
            <tr><th>Ngày hết hạn</th><td>{{ $expireDate ?? '' }}</td></tr>
            <tr><th>Nơi cấp</th><td>{{ $issuedBy ?? '' }}</td></tr>
            <tr><th>Đặc điểm nhận dạng</th><td>{{ $features ?? '' }}</td></tr>
        </table>

        @if(!empty($rawTextBack))
        <details class="mt-3">
            <summary class="text-muted" style="cursor:pointer">Văn bản OCR gốc (mặt sau)</summary>
            <pre class="bg-light p-3 rounded mt-2">{{ $rawTextBack }}</pre>
        </details>
        @endif
    </div>
    @endif
{{-- ── THÔNG TIN TRÍCH XUẤT (có thể sửa tay) ──────────────── --}}
<div class="card-box mb-4">
    <h5 class="mb-3">Thông tin {{ $typeNames[$type] ?? '' }}</h5>

    <form action="{{ route('documents.updateExtracted', $document->id) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($fieldLabels as $key => $label)
            @if(array_key_exists($key, $result) || isset($document->$key))
                <div class="mb-3 row">
                    <label class="col-sm-3 col-form-label fw-bold">{{ $label }}</label>
                    <div class="col-sm-9">
                        <input type="text" name="{{ $key }}" class="form-control"
                               value="{{ old($key, $document->$key ?? $result[$key] ?? '') }}">
                    </div>
                </div>
            @endif
        @endforeach

        <button type="submit" class="btn btn-success mt-2">
            <i class="bi bi-save me-1"></i> Lưu thông tin đã sửa
        </button>
    </form>

    <details class="mt-3">
        <summary class="text-muted" style="cursor:pointer">Văn bản OCR gốc</summary>
        <pre class="bg-light p-3 rounded mt-2">{{ $document->raw_text ?? ($result['raw_text'] ?? '') }}</pre>
    </details>
</div>
    {{-- ── XUẤT FILE (chỉ áp dụng cho CCCD, dùng 4 field cố định) ──── --}}
    <div class="card-box">
        <h5 class="mb-3">Chọn thông tin muốn xuất</h5>

        <form action="{{ route('preview') }}" method="POST" class="mb-4">
            @csrf
            <div class="row mb-2">
                <div class="col-6">
                    <input type="checkbox" name="fields[]" value="cccd" checked id="f_cccd">
                    <label for="f_cccd">Số CCCD</label>
                </div>
                <div class="col-6">
                    <input type="checkbox" name="fields[]" value="name" checked id="f_name">
                    <label for="f_name">Họ tên</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <input type="checkbox" name="fields[]" value="dob" checked id="f_dob">
                    <label for="f_dob">Ngày sinh</label>
                </div>
                <div class="col-6">
                    <input type="checkbox" name="fields[]" value="gender" checked id="f_gender">
                    <label for="f_gender">Giới tính</label>
                </div>
            </div>

            <h6>Thêm trường mới</h6>
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <input type="text" name="new_field_name" class="form-control" placeholder="Ví dụ: Email">
                </div>
                <div class="col-6">
                    <input type="text" name="new_field_value" class="form-control" placeholder="Ví dụ: abc@gmail.com">
                </div>
            </div>

            <input type="hidden" name="cccd"   value="{{ $document->id_number ?? '' }}">
            <input type="hidden" name="name"   value="{{ $document->full_name ?? '' }}">
            <input type="hidden" name="dob"    value="{{ $document->birth_date ?? '' }}">
            <input type="hidden" name="gender" value="{{ $document->gender ?? '' }}">

            <button type="submit" class="btn btn-primary">Xem kết quả</button>
        </form>

        <hr>

        <form action="/export-excel" method="POST">
            @csrf
            <input type="hidden" name="cccd"   value="{{ $document->id_number ?? '' }}">
            <input type="hidden" name="name"   value="{{ $document->full_name ?? '' }}">
            <input type="hidden" name="dob"    value="{{ $document->birth_date ?? '' }}">
            <input type="hidden" name="gender" value="{{ $document->gender ?? '' }}">
            <button type="submit" class="btn btn-outline-primary">Xuất Excel</button>
        </form>
    </div>

    @endif
<div class="mt-4">
    <a href="{{ route('ocr') }}" class="btn btn-secondary">
        ← Upload ảnh mới
    </a>

    <a href="{{ route('ocr.reuse-last') }}" class="btn btn-primary">
        ↪ Dùng lại ảnh & kết quả vừa OCR
    </a>

    @if(isset($document))
        <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info">
            Xem chi tiết hồ sơ
        </a>
    @endif
</div>
@endsection