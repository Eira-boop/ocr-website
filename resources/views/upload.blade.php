@extends('layouts.ocr')

@section('title','OCR Document Management')

@section('content')

<!-- ================= HERO ================= -->

<div class="hero-section mb-5">

    <div class="row align-items-center">

        <div class="col-lg-7">

            <span class="hero-badge">

                OCR DOCUMENT SYSTEM

            </span>

            <h1 class="hero-title mt-3">

                Hệ thống nhận dạng và trích xuất

                <span class="text-primary">

                    tài liệu thông minh

                </span>

            </h1>

            <p class="hero-text mt-3">

                Hỗ trợ nhận dạng và trích xuất dữ liệu từ

                CCCD, Hộ chiếu, Giấy khai sinh,

                Học bạ, Bảng điểm, Bằng tốt nghiệp,

                Hồ sơ xét tuyển,

                PDF, Word, Excel và nhiều loại tài liệu khác.

            </p>

            <div class="hero-feature mt-4">

                <span>

                    🪪 OCR

                </span>

                <span>

                    📄 PDF

                </span>

                <span>

                    📘 WORD

                </span>

                <span>

                    📊 EXCEL

                </span>

                <span>

                    🤖 AI READY

                </span>

            </div>

        </div>

        <div class="col-lg-5">

            <div class="system-card">

                <div class="system-icon">

                    <i class="bi bi-file-earmark-text-fill"></i>

                </div>

                <h3>

                    OCR Engine

                </h3>

                <p>

                    Upload tài liệu

                    →

                    OCR

                    →

                    Trích xuất dữ liệu

                    →

                    Xuất Excel

                </p>

            </div>

        </div>

    </div>

</div>

<!-- ================= THỐNG KÊ ================= -->

<div class="row mb-5 g-4">

    <div class="col-md-3">

        <div class="info-card">

            <div class="icon bg-primary">

                <i class="bi bi-files"></i>

            </div>

            <h2>

                10+

            </h2>

            <p>

                Loại tài liệu

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="info-card">

            <div class="icon bg-success">

                <i class="bi bi-image-fill"></i>

            </div>

            <h2>

                JPG

            </h2>

            <p>

                PNG • JPEG

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="info-card">

            <div class="icon bg-danger">

                <i class="bi bi-file-earmark-pdf-fill"></i>

            </div>

            <h2>

                PDF

            </h2>

            <p>

                Multi Page OCR

            </p>

        </div>

    </div>

    <div class="col-md-3">

        <div class="info-card">

            <div class="icon bg-warning">

                <i class="bi bi-file-earmark-excel-fill"></i>

            </div>

            <h2>

                XLSX

            </h2>

            <p>

                Export Data

            </p>

        </div>

    </div>

</div>

<!-- ================= UPLOAD ================= -->

<div class="upload-card">

    <div id="dropZone">

        <div class="upload-circle">

            <i class="bi bi-cloud-arrow-up-fill"></i>

        </div>

        <h2 class="mt-4">

            Kéo và thả tài liệu vào đây

        </h2>

        <p class="text-muted">

            Hỗ trợ JPG • PNG • PDF • DOC • DOCX • XLS • XLSX

        </p>

        <button

            type="button"

            id="chooseFile"

            class="btn btn-primary btn-lg px-5">

            <i class="bi bi-folder2-open me-2"></i>

            Chọn tài liệu

        </button>

        <input

            type="file"

            id="documentFile"

            hidden>

    </div>

</div>

<div class="mt-5">

    <div class="row">

        <div class="col-lg-8">

            <div class="card-box">

                <h3 class="mb-4">

                    <i class="bi bi-info-circle-fill text-primary me-2"></i>

                    Thông tin tài liệu

                </h3>
                <div class="row mt-4">

    <!-- Preview -->

    <div class="col-lg-5">

        <div class="card-box h-100">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="mb-0">

                    <i class="bi bi-image-fill text-primary me-2"></i>

                    Xem trước

                </h4>

                <span class="badge bg-primary">

                    Preview

                </span>

            </div>

            <div class="preview-box">

                <img

                    id="previewImage"

                    src="https://placehold.co/500x650?text=No+Preview"

                    class="img-fluid"

                    alt="preview">

            </div>

        </div>

    </div>



    <!-- Thông tin -->

    <div class="col-lg-7">

        <div class="card-box h-100">

            <h4 class="mb-4">

                <i class="bi bi-file-earmark-text-fill text-primary me-2"></i>

                Thông tin tài liệu

            </h4>

            <table class="table align-middle">

                <tbody>

                <tr>

                    <th width="180">

                        Tên tài liệu

                    </th>

                    <td id="fileName">

                        Chưa có dữ liệu

                    </td>

                </tr>

                <tr>

                    <th>

                        Kích thước

                    </th>

                    <td id="fileSize">

                        --

                    </td>

                </tr>

                <tr>

                    <th>

                        Định dạng

                    </th>

                    <td id="fileType">

                        --

                    </td>

                </tr>

                <tr>

                    <th>

                        Độ phân giải

                    </th>

                    <td id="fileResolution">

                        --

                    </td>

                </tr>

                <tr>

                    <th>

                        Ngày tải lên

                    </th>

                    <td>

                        {{ now()->format('d/m/Y H:i') }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Trạng thái

                    </th>

                    <td>

                        <span id="statusBadge"

                              class="badge bg-secondary">

                            Chưa xử lý

                        </span>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- ================= Document Type ================= -->

<div class="card-box mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3>

            <i class="bi bi-card-checklist text-primary me-2"></i>

            Loại tài liệu

        </h3>

        <span class="text-muted">

            Chọn hoặc để hệ thống tự nhận diện

        </span>

    </div>

    <div class="row g-3">

        <div class="col-md-3">

            <div class="doc-card active"

                 data-type="auto">

                🤖

                <h5>Tự động</h5>

                <small>Auto Detect</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="cccd">

                🪪

                <h5>CCCD</h5>

                <small>Căn cước công dân</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="passport">

                🛂

                <h5>Passport</h5>

                <small>Hộ chiếu</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="birth">

                📜

                <h5>Khai sinh</h5>

                <small>Birth Certificate</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="report">

                📚

                <h5>Học bạ</h5>

                <small>School Report</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="transcript">

                📄

                <h5>Bảng điểm</h5>

                <small>Transcript</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="degree">

                🎓

                <h5>Bằng TN</h5>

                <small>Graduation</small>

            </div>

        </div>

        <div class="col-md-3">

            <div class="doc-card"

                 data-type="other">

                📁

                <h5>Khác</h5>

                <small>Other</small>

            </div>

        </div>

    </div>

    <input

        type="hidden"

        id="documentType"

        value="auto">

</div>



<!-- ================= OCR OPTION ================= -->

<div class="card-box mt-5">

    <h3 class="mb-4">

        <i class="bi bi-sliders text-primary me-2"></i>

        Thiết lập OCR

    </h3>

    <div class="row">

        <div class="col-md-6">

            <div class="form-check mb-3">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Tự động xoay ảnh

                </label>

            </div>

            <div class="form-check mb-3">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Tăng độ tương phản

                </label>

            </div>

            <div class="form-check">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Khử nhiễu hình ảnh

                </label>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-check mb-3">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Trích xuất toàn bộ dữ liệu

                </label>

            </div>

            <div class="form-check mb-3">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Lưu lịch sử OCR

                </label>

            </div>

            <div class="form-check">

                <input

                    checked

                    class="form-check-input"

                    type="checkbox">

                <label class="form-check-label">

                    Xuất Excel sau khi xử lý

                </label>

            </div>

        </div>

    </div>

</div>
<!-- ================= OCR PROCESS ================= -->

<div class="card-box mt-5">

    <h3 class="mb-4">

        <i class="bi bi-diagram-3-fill text-primary me-2"></i>

        Quy trình xử lý OCR

    </h3>

    <div class="ocr-timeline">

        <div class="ocr-item active">

            <div class="ocr-icon">

                <i class="bi bi-upload"></i>

            </div>

            <span>Upload</span>

        </div>

        <div class="ocr-line"></div>

        <div class="ocr-item">

            <div class="ocr-icon">

                <i class="bi bi-image"></i>

            </div>

            <span>Tiền xử lý</span>

        </div>

        <div class="ocr-line"></div>

        <div class="ocr-item">

            <div class="ocr-icon">

                <i class="bi bi-search"></i>

            </div>

            <span>OCR</span>

        </div>

        <div class="ocr-line"></div>

        <div class="ocr-item">

            <div class="ocr-icon">

                <i class="bi bi-file-earmark-text"></i>

            </div>

            <span>Trích xuất</span>

        </div>

        <div class="ocr-line"></div>

        <div class="ocr-item">

            <div class="ocr-icon">

                <i class="bi bi-file-earmark-excel"></i>

            </div>

            <span>Excel</span>

        </div>

    </div>

</div>

<!-- ================= Progress ================= -->

<div class="card-box mt-4">

    <div class="d-flex justify-content-between mb-2">

        <strong>

            Tiến trình OCR

        </strong>

        <span id="progressText">

            0%

        </span>

    </div>

    <div class="progress" style="height:24px;">

        <div

            id="progressBar"

            class="progress-bar progress-bar-striped progress-bar-animated"

            style="width:0%">

        </div>

    </div>

</div>

<!-- ================= Upload Forms ================= -->

<form id="imageForm"

      action="/upload"

      method="POST"

      enctype="multipart/form-data"

      style="display:none;">

    @csrf

    <input

        type="file"

        name="image"

        id="imageInput">
        <input type="hidden" name="documentType" id="imageDocType">

</form>

<form id="pdfForm"

      action="{{ route('upload.pdf') }}"

      method="POST"

      enctype="multipart/form-data"

      style="display:none;">

    @csrf

    <input

        type="file"

        name="pdf_file"

        id="pdfInput">
        <input type="hidden" name="documentType" id="pdfDocType">

</form>

<form id="wordForm"

      action="{{ route('upload.word') }}"

      method="POST"

      enctype="multipart/form-data"

      style="display:none;">

    @csrf

    <input

        type="file"

        name="word_file"

        id="wordInput">
        <input type="hidden" name="documentType" id="wordDocType">
</form>

<form id="excelForm"

      action="{{ route('upload.excel') }}"

      method="POST"

      enctype="multipart/form-data"

      style="display:none;">

    @csrf

    <input

        type="file"

        name="excel_file"

        id="excelInput">
        <input type="hidden" name="documentType" id="excelDocType">
</form>

<!-- ================= Buttons ================= -->

<div class="text-center mt-5">

    <button

        type="button"

        id="startOCR"

        class="btn btn-primary btn-lg px-5">

        <i class="bi bi-play-circle-fill me-2"></i>

        Bắt đầu OCR

    </button>

    <button

        type="button"

        id="resetUpload"

        class="btn btn-outline-secondary btn-lg ms-2">

        <i class="bi bi-arrow-clockwise me-2"></i>

        Làm mới

    </button>

    <button

        type="button"

        class="btn btn-success btn-lg ms-2"

        disabled>

        <i class="bi bi-file-earmark-excel-fill me-2"></i>

        Xuất Excel

    </button>

</div>

@if(session('success'))

<div class="alert alert-success mt-4">

    {{ session('success') }}

</div>

@endif

@if($errors->any())

<div class="alert alert-danger mt-4">

    <ul class="mb-0">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

@endsection
@section('extra_style')

<style>

.hero-section{
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    border-radius:25px;
    padding:60px;
    color:white;
}

.hero-badge{
    background:rgba(255,255,255,.18);
    padding:8px 18px;
    border-radius:30px;
    font-size:14px;
}

.hero-title{
    font-size:42px;
    font-weight:700;
}

.hero-text{
    opacity:.92;
    line-height:1.8;
}

.hero-feature{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.hero-feature span{
    background:rgba(255,255,255,.18);
    padding:8px 15px;
    border-radius:30px;
}

.system-card{
    background:white;
    border-radius:20px;
    padding:35px;
    color:#222;
    text-align:center;
}

.system-icon{
    width:90px;
    height:90px;
    margin:auto;
    border-radius:50%;
    background:#2563eb;
    color:white;
    font-size:40px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.info-card{
    background:white;
    border-radius:18px;
    padding:30px;
    text-align:center;
    transition:.3s;
}

.info-card:hover{
    transform:translateY(-8px);
    box-shadow:0 15px 35px rgba(0,0,0,.08);
}

.info-card .icon{
    width:65px;
    height:65px;
    border-radius:50%;
    display:flex;
    justify-content:center;
    align-items:center;
    color:white;
    font-size:28px;
    margin:auto auto 15px;
}

.upload-card{
    background:white;
    border-radius:20px;
    padding:40px;
}

#dropZone{
    border:3px dashed #cbd5e1;
    border-radius:20px;
    padding:70px;
    text-align:center;
    transition:.35s;
    cursor:pointer;
}

#dropZone:hover{
    border-color:#2563eb;
    background:#eef5ff;
}

#dropZone.drag{
    background:#eaf3ff;
    border-color:#2563eb;
}

.upload-circle{
    width:120px;
    height:120px;
    margin:auto;
    border-radius:50%;
    background:#2563eb;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:55px;
}

.preview-box{
    height:500px;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f8fafc;
    border-radius:18px;
    overflow:hidden;
}

.preview-box img{
    max-width:100%;
    max-height:100%;
}

.doc-card{
    background:white;
    border:2px solid #e5e7eb;
    border-radius:18px;
    padding:25px;
    cursor:pointer;
    transition:.3s;
    text-align:center;
}

.doc-card:hover{
    transform:translateY(-6px);
    border-color:#2563eb;
}

.doc-card.active{
    background:#2563eb;
    color:white;
    border-color:#2563eb;
}

.ocr-timeline{
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.ocr-item{
    text-align:center;
    flex:1;
}

.ocr-icon{
    width:65px;
    height:65px;
    margin:auto;
    border-radius:50%;
    background:#edf2ff;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:28px;
}

.ocr-item.active .ocr-icon{
    background:#2563eb;
    color:white;
}

.ocr-line{
    flex:1;
    height:4px;
    background:#dbeafe;
    margin:0 5px;
}

.progress{
    border-radius:30px;
}

.progress-bar{
    font-weight:bold;
}

</style>

@endsection



@section('extra_script')

<script>

let currentFile=null;

const dropZone=document.getElementById("dropZone");
const fileInput=document.getElementById("documentFile");

dropZone.onclick=()=>fileInput.click();

fileInput.onchange=function(){

    if(this.files.length){

        loadFile(this.files[0]);

    }

};

dropZone.addEventListener("dragover",e=>{

    e.preventDefault();

    dropZone.classList.add("drag");

});

dropZone.addEventListener("dragleave",()=>{

    dropZone.classList.remove("drag");

});

dropZone.addEventListener("drop",e=>{

    e.preventDefault();

    dropZone.classList.remove("drag");

    if(e.dataTransfer.files.length){

        loadFile(e.dataTransfer.files[0]);

    }

});

function loadFile(file){

    currentFile=file;

    document.getElementById("fileName").innerHTML=file.name;

    document.getElementById("fileSize").innerHTML=(file.size/1024/1024).toFixed(2)+" MB";

    document.getElementById("fileType").innerHTML=file.type;

    document.getElementById("statusBadge").className="badge bg-warning";

    document.getElementById("statusBadge").innerHTML="Đã tải";

    if(file.type.startsWith("image")){

        const reader=new FileReader();

        reader.onload=function(e){

            document.getElementById("previewImage").src=e.target.result;

            const img=new Image();

            img.onload=function(){

                document.getElementById("fileResolution").innerHTML=this.width+" x "+this.height;

            }

            img.src=e.target.result;

        }

        reader.readAsDataURL(file);

    }

}
document.querySelectorAll(".doc-card").forEach(card=>{
    card.onclick=function(){

        document.querySelectorAll(".doc-card").forEach(c=>{
            c.classList.remove("active");
        });

        this.classList.add("active");

        const type = this.getAttribute("data-type");

        document.getElementById("documentType").value = type;

        console.log("Selected type:", type);
    }
});

document.getElementById("startOCR").onclick=function(){

    if(currentFile==null){

        alert("Vui lòng chọn tài liệu!");

        return;

    }

    let progress=0;

    const bar=document.getElementById("progressBar");

    const text=document.getElementById("progressText");

    const timer=setInterval(()=>{

        progress+=4;

        bar.style.width=progress+"%";

        bar.innerHTML=progress+"%";

        text.innerHTML=progress+"%";

        if(progress>=100){

            clearInterval(timer);

            submitOCR();

        }

    },70);

};


function submitOCR(){
    const docType = document.getElementById("documentType").value;
document.getElementById("imageDocType").value = docType;
document.getElementById("pdfDocType").value = docType;
document.getElementById("wordDocType").value = docType;
document.getElementById("excelDocType").value = docType;

    const ext=currentFile.name.split('.').pop().toLowerCase();

    const dt=new DataTransfer();

    dt.items.add(currentFile);

    if(["jpg","jpeg","png","bmp","webp"].includes(ext)){

        imageInput.files=dt.files;

        imageForm.submit();

    }

    else if(ext==="pdf"){

        pdfInput.files=dt.files;

        pdfForm.submit();

    }

    else if(ext==="doc"||ext==="docx"){

        wordInput.files=dt.files;

        wordForm.submit();

    }

    else if(ext==="xls"||ext==="xlsx"){

        excelInput.files=dt.files;

        excelForm.submit();

    }

    else{

        alert("Định dạng chưa hỗ trợ.");

    }

}

document.getElementById("resetUpload").onclick=function(){

    location.reload();

};

</script>

@endsection