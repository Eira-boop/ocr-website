@extends('layouts.ocr')

@section('title','Quản lý nhân viên')

@section('content')

@php

    $adminCount = $users->where('role','admin')->count();

    $userCount = $users->where('role','user')->count();

@endphp

<!-- Header -->

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="page-title">

            <i class="bi bi-people-fill text-primary me-2"></i>

            Quản lý nhân viên

        </h1>

        <p class="page-subtitle">

            Quản lý tài khoản và phân quyền người dùng trong hệ thống OCR.

        </p>

    </div>

    <button class="btn btn-primary px-4 py-2">

        <i class="bi bi-person-plus-fill me-2"></i>

        Thêm nhân viên

    </button>

</div>

<!-- Thống kê -->

<div class="row g-4 mb-4">

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <div class="mb-3">

                <i class="bi bi-people-fill display-5 text-primary"></i>

            </div>

            <h6 class="text-muted">

                Tổng tài khoản

            </h6>

            <h2 class="fw-bold text-primary">

                {{ $users->total() }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <div class="mb-3">

                <i class="bi bi-person-workspace display-5 text-success"></i>

            </div>

            <h6 class="text-muted">

                Quản trị viên

            </h6>

            <h2 class="fw-bold text-success">

                {{ $adminCount }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <div class="mb-3">

                <i class="bi bi-person-fill display-5 text-warning"></i>

            </div>

            <h6 class="text-muted">

                Nhân viên

            </h6>

            <h2 class="fw-bold text-warning">

                {{ $userCount }}

            </h2>

        </div>

    </div>

    <div class="col-lg-3 col-md-6">

        <div class="card-box text-center">

            <div class="mb-3">

                <i class="bi bi-check-circle-fill display-5 text-info"></i>

            </div>

            <h6 class="text-muted">

                Đang hoạt động

            </h6>

            <h2 class="fw-bold text-info">

                {{ $users->count() }}

            </h2>

        </div>

    </div>

</div>

<!-- Tìm kiếm -->

<div class="card-box mb-4">

    <div class="row g-3">

        <div class="col-lg-5">

            <div class="input-group">

                <span class="input-group-text bg-white">

                    <i class="bi bi-search"></i>

                </span>

                <input

                    type="text"

                    id="searchUser"

                    class="form-control"

                    placeholder="Tìm kiếm theo tên hoặc email...">

            </div>

        </div>

        <div class="col-lg-3">

            <select class="form-select">

                <option>

                    Tất cả quyền

                </option>

                <option>

                    Quản trị viên

                </option>

                <option>

                    Nhân viên

                </option>

            </select>

        </div>

        <div class="col-lg-2">

            <button class="btn btn-outline-primary w-100">

                <i class="bi bi-funnel-fill me-2"></i>

                Lọc

            </button>

        </div>

        <div class="col-lg-2">

            <button class="btn btn-success w-100">

                <i class="bi bi-file-earmark-excel-fill me-2"></i>

                Excel

            </button>

        </div>

    </div>

</div>

<!-- Danh sách nhân viên -->

<div class="card-box">

    <div class="table-responsive">

        <table class="table table-hover align-middle"

               id="userTable">

            <thead>

                <tr>

                    <th width="80">

                        Avatar

                    </th>

                    <th>

                        Họ tên

                    </th>

                    <th>

                        Email

                    </th>

                    <th>

                        Quyền

                    </th>

                    <th>

                        Đổi quyền

                    </th>

                    <th>

                        Trạng thái

                    </th>

                    <th width="150">

                        Thao tác

                    </th>

                </tr>

            </thead>

            <tbody>
                @forelse($users as $user)

<tr>

    <!-- Avatar -->

    <td>

        <div class="rounded-circle bg-primary text-white fw-bold d-flex justify-content-center align-items-center"

             style="width:50px;height:50px;font-size:18px;">

            {{ strtoupper(substr($user->name,0,1)) }}

        </div>

    </td>

    <!-- Họ tên -->

    <td>

        <div class="fw-bold">

            {{ $user->name }}

            @if($user->id == auth()->id())

                <span class="badge bg-warning text-dark ms-2">

                    Bạn

                </span>

            @endif

        </div>

        <small class="text-muted">

            ID: #{{ $user->id }}

        </small>

    </td>

    <!-- Email -->

    <td>

        <i class="bi bi-envelope-fill text-primary me-2"></i>

        {{ $user->email }}

    </td>

    <!-- Quyền -->

    <td>

        @if($user->role == 'admin')

            <span class="badge bg-primary px-3 py-2">

                <i class="bi bi-shield-check me-1"></i>

                Quản trị viên

            </span>

        @else

            <span class="badge bg-success px-3 py-2">

                <i class="bi bi-person-fill me-1"></i>

                Nhân viên

            </span>

        @endif

    </td>

    <!-- Đổi quyền -->

    <td>

        @if($user->id != auth()->id())

        <form action="{{ route('users.updateRole',$user->id) }}"

              method="POST"

              class="d-flex gap-2">

            @csrf

            @method('PATCH')

            <select

                name="role"

                class="form-select form-select-sm">

                <option

                    value="user"

                    {{ $user->role=='user'?'selected':'' }}>

                    Nhân viên

                </option>

                <option

                    value="admin"

                    {{ $user->role=='admin'?'selected':'' }}>

                    Quản trị viên

                </option>

            </select>

            <button

                class="btn btn-primary btn-sm">

                <i class="bi bi-check-lg"></i>

            </button>

        </form>

        @else

            <span class="text-muted">

                Không thể sửa

            </span>

        @endif

    </td>

    <!-- Trạng thái -->

    <td>

        <span class="badge bg-success">

            <i class="bi bi-circle-fill me-1"

               style="font-size:8px;"></i>

            Hoạt động

        </span>

    </td>

    <!-- Thao tác -->

    <td>

        @if($user->id != auth()->id())

        <div class="d-flex gap-2">

            <button

                class="btn btn-outline-primary btn-sm"

                title="Chi tiết">

                <i class="bi bi-eye-fill"></i>

            </button>

            <button

                class="btn btn-outline-warning btn-sm"

                title="Chỉnh sửa">

                <i class="bi bi-pencil-fill"></i>

            </button>

            <form

                action="{{ route('users.destroy',$user->id) }}"

                method="POST"

                onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này?')">

                @csrf

                @method('DELETE')

                <button

                    class="btn btn-outline-danger btn-sm">

                    <i class="bi bi-trash-fill"></i>

                </button>

            </form>

        </div>

        @else

            <span class="badge bg-secondary">

                Tài khoản hiện tại

            </span>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="7">

        <div class="text-center py-5">

            <i class="bi bi-people display-1 text-muted"></i>

            <h4 class="mt-3">

                Chưa có nhân viên

            </h4>

            <p class="text-muted">

                Hệ thống chưa có tài khoản nào.

            </p>

        </div>

    </td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>
<!-- Pagination -->

<div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">

    <div class="text-muted">

        Hiển thị

        <strong>{{ $users->count() }}</strong>

        / Tổng

        <strong>{{ $users->total() }}</strong>

        tài khoản

    </div>

    <div>

        {{ $users->links() }}

    </div>

</div>

</div>

@endsection

@section('extra_style')

<style>

#userTable tbody tr{

    transition:.25s;

}

#userTable tbody tr:hover{

    transform:scale(1.005);

    box-shadow:0 8px 18px rgba(0,0,0,.05);

}

#userTable td{

    vertical-align:middle;

}

#userTable .btn{

    border-radius:10px;

}

#searchUser{

    border-radius:12px;

}

.avatar{

    width:50px;

    height:50px;

    border-radius:50%;

    background:linear-gradient(135deg,#2563eb,#60a5fa);

    color:#fff;

    font-weight:700;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:18px;

}

.table thead th{

    background:#f8fafc;

    font-weight:700;

    color:#475569;

    white-space:nowrap;

}

.table tbody td{

    white-space:nowrap;

}

.badge{

    border-radius:30px;

    padding:8px 14px;

}

@media(max-width:992px){

    .table{

        min-width:900px;

    }

}

</style>

@endsection


@section('extra_script')

<script>

const searchInput=document.getElementById("searchUser");

searchInput.addEventListener("keyup",function(){

    let keyword=this.value.toLowerCase();

    let rows=document.querySelectorAll("#userTable tbody tr");

    rows.forEach(function(row){

        let text=row.innerText.toLowerCase();

        if(text.indexOf(keyword)>-1){

            row.style.display="";

        }else{

            row.style.display="none";

        }

    });

});

</script>

@endsection