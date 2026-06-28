@extends('layouts.ocr')

@section('title', 'Chi tiết CCCD')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --navy:#0B2E4D;
  --blue-deep:#12497D;
  --blue:#1D74BB;
  --blue-bright:#3B97DB;
  --sky:#E8F3FC;
  --sky-soft:#F3F8FD;
  --panel:#FFFFFF;
  --bg:#F0F5FA;
  --ink:#10202F;
  --muted:#5E7388;
  --muted-soft:#94A8BA;
  --line:#DCE7F1;
  --gold:#C9971F;
  --radius:12px;
}
.ocr-wrap{font-family:'Inter',sans-serif;color:var(--ink);font-size:14.5px}
.ocr-disp{font-family:'Space Grotesk',sans-serif}

.ocr-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;gap:16px;flex-wrap:wrap}
.ocr-head h1{font-family:'Space Grotesk',sans-serif;font-size:21px;font-weight:600;color:var(--navy);margin-bottom:4px}
.ocr-head p{font-size:13px;color:var(--muted);margin:0}

.detail-grid{display:grid;grid-template-columns:1.1fr 0.9fr;gap:18px;align-items:start;margin-bottom:20px}

.ocr-panel{
  background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);
  overflow:hidden;
}
.ocr-panel-head{
  display:flex;justify-content:space-between;align-items:center;
  padding:16px 20px;border-bottom:1px solid var(--line);
}
.ocr-panel-head h3{font-family:'Space Grotesk',sans-serif;font-size:14.5px;font-weight:600;color:var(--navy);margin:0}

.info-list{padding:6px 20px 16px}
.info-row{display:flex;justify-content:space-between;align-items:center;gap:16px;padding:13px 0;border-bottom:1px solid var(--line)}
.info-row:last-child{border-bottom:none}
.info-label{font-size:12.5px;color:var(--muted);font-weight:500;flex-shrink:0}
.info-value{font-size:14px;color:var(--ink);font-weight:600;text-align:right}
.info-value.mono{font-family:'Space Grotesk',sans-serif;color:var(--blue-deep);letter-spacing:0.02em}
.info-value.tag{
  font-size:12px;font-weight:600;color:var(--muted);background:var(--sky-soft);
  border:1px solid var(--line);padding:3px 10px;border-radius:20px;
}

.image-card{padding:20px}
.image-frame{
  border:1px solid var(--line);border-radius:11px;overflow:hidden;background:var(--sky-soft);
}
.image-frame img{width:100%;display:block}
.image-caption{margin-top:12px;font-size:12px;color:var(--muted-soft);text-align:center}

.back-btn{
  display:inline-flex;align-items:center;gap:8px;
  background:#fff;color:var(--navy);font-weight:600;font-size:13.5px;
  padding:11px 22px;border-radius:9px;text-decoration:none;border:1.5px solid var(--line);
  transition:all 0.2s;font-family:'Inter',sans-serif;
}
.back-btn:hover{border-color:var(--blue);color:var(--blue)}
.back-btn svg{width:15px;height:15px;stroke:currentColor}

@media(max-width:880px){
  .detail-grid{grid-template-columns:1fr}
}
</style>

<div class="ocr-wrap">

  <div class="ocr-head">
    <div>
      <h1 class="ocr-disp">Thông tin CCCD</h1>
      <p>Chi tiết hồ sơ căn cước công dân đã quét bằng OCR</p>
    </div>
  </div>

  <div class="detail-grid">

    <div class="ocr-panel">
      <div class="ocr-panel-head">
        <h3>Thông tin cá nhân</h3>
      </div>
      <div class="info-list">
        <div class="info-row">
          <span class="info-label">Số CCCD</span>
          <span class="info-value mono">{{ $document->id_number }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Họ tên</span>
          <span class="info-value">{{ $document->full_name }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Ngày sinh</span>
          <span class="info-value">{{ $document->birth_date }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Giới tính</span>
          <span class="info-value tag">{{ $document->gender }}</span>
        </div>
        <div class="info-row">
          <span class="info-label">Quốc tịch</span>
          <span class="info-value">{{ $document->nationality }}</span>
        </div>
      </div>
    </div>

    <div class="ocr-panel">
      <div class="ocr-panel-head">
        <h3>Ảnh CCCD</h3>
      </div>
      <div class="image-card">
        <div class="image-frame">
          <img src="{{ asset('storage/'.$document->image_path) }}" alt="Ảnh CCCD của {{ $document->full_name }}">
        </div>
        <div class="image-caption">Ảnh được lưu trữ từ hệ thống OCR</div>
      </div>
    </div>

  </div>

  <a href="{{ route('documents.list') }}" class="back-btn">
    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Quay lại
  </a>

</div>

@endsection