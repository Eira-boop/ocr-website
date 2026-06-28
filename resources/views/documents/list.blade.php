@extends('layouts.ocr')

@section('title', 'Danh sách hồ sơ CCCD')

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
  --good:#1E9E6D;
  --good-bg:#E6F7EF;
  --warn:#C77E1B;
  --warn-bg:#FBEFDE;
  --bad:#D8453F;
  --bad-bg:#FBEAE9;
  --gold:#C9971F;
  --radius:12px;
}
.ocr-wrap{font-family:'Inter',sans-serif;color:var(--ink);font-size:14.5px}
.ocr-disp{font-family:'Space Grotesk',sans-serif}

.ocr-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;gap:16px;flex-wrap:wrap}
.ocr-head h1{font-family:'Space Grotesk',sans-serif;font-size:21px;font-weight:600;color:var(--navy);margin-bottom:4px}
.ocr-head p{font-size:13px;color:var(--muted);margin:0}

.ocr-searchbar{
  background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);
  padding:14px 16px;margin-bottom:18px;display:flex;gap:10px;flex-wrap:wrap;
}
.ocr-search-input{
  display:flex;align-items:center;gap:9px;background:var(--sky-soft);
  border:1px solid var(--line);border-radius:9px;padding:9px 14px;flex:1;min-width:240px;max-width:360px;
}
.ocr-search-input svg{width:15px;height:15px;stroke:var(--muted-soft);flex-shrink:0}
.ocr-search-input input{border:none;background:none;outline:none;font-size:13.5px;width:100%;color:var(--ink);font-family:'Inter',sans-serif}
.ocr-search-input input::placeholder{color:var(--muted-soft)}
.ocr-btn-primary{
  background:var(--blue);color:#fff;font-weight:600;font-size:13px;
  padding:9px 20px;border-radius:8px;border:none;cursor:pointer;font-family:'Inter',sans-serif;
}
.ocr-btn-primary:hover{background:var(--blue-deep)}

.ocr-panel{
  background:var(--panel);border:1px solid var(--line);border-radius:var(--radius);
  overflow:hidden;margin-bottom:20px;
}
.ocr-panel-head{
  display:flex;justify-content:space-between;align-items:center;
  padding:16px 20px;border-bottom:1px solid var(--line);
}
.ocr-panel-head h3{font-family:'Space Grotesk',sans-serif;font-size:14.5px;font-weight:600;color:var(--navy);margin:0}
.ocr-panel-head span{font-size:12px;color:var(--muted)}

.ocr-table{width:100%;border-collapse:collapse}
.ocr-table thead tr{background:var(--sky-soft);border-bottom:1px solid var(--line)}
.ocr-table th{
  text-align:left;font-size:11px;font-weight:700;color:var(--muted);
  text-transform:uppercase;letter-spacing:0.04em;padding:12px 16px;white-space:nowrap;
}
.ocr-table th:first-child{padding-left:20px}
.ocr-table tbody tr{border-bottom:1px solid var(--line);transition:background 0.12s}
.ocr-table tbody tr:last-child{border-bottom:none}
.ocr-table tbody tr:hover{background:var(--sky-soft)}
.ocr-table td{padding:13px 16px;vertical-align:middle;font-size:13.5px}
.ocr-table td:first-child{padding-left:20px}

.ocr-id{font-family:'Space Grotesk',sans-serif;font-size:12.5px;color:var(--muted-soft);font-weight:600}

.doc-cell{display:flex;align-items:center;gap:11px}
.doc-avatar{
  width:34px;height:34px;border-radius:9px;background:var(--sky);color:var(--blue);
  display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;
}
.doc-info strong{display:block;font-size:13.5px;font-weight:600;color:var(--ink)}
.doc-info span{font-size:11.5px;color:var(--muted-soft)}

.id-chip{
  display:inline-block;font-family:'Space Grotesk',sans-serif;font-size:12.5px;font-weight:600;
  background:var(--sky-soft);border:1px solid var(--line);color:var(--blue-deep);
  padding:4px 10px;border-radius:7px;letter-spacing:0.02em;
}
.gender-tag{
  display:inline-block;font-size:11.5px;font-weight:600;color:var(--muted);
  background:var(--sky-soft);border:1px solid var(--line);padding:3px 10px;border-radius:20px;
}

.row-actions{display:flex;gap:6px;justify-content:flex-end}
.act-btn{
  display:inline-flex;align-items:center;gap:6px;
  font-size:12px;font-weight:600;font-family:'Inter',sans-serif;
  padding:7px 12px;border-radius:8px;border:1px solid var(--line);background:var(--panel);
  cursor:pointer;text-decoration:none;color:var(--ink);
}
.act-btn svg{width:13px;height:13px;stroke:currentColor;flex-shrink:0}
.act-btn.view{color:var(--blue);border-color:var(--line)}
.act-btn.view:hover{border-color:var(--blue);background:var(--sky-soft)}
.act-btn.delete{color:var(--bad);border-color:var(--line)}
.act-btn.delete:hover{border-color:var(--bad);background:var(--bad-bg)}

.ocr-empty{padding:48px 20px;text-align:center;color:var(--muted-soft);font-size:13.5px}

.ocr-panel-footer{
  display:flex;justify-content:center;align-items:center;
  padding:14px 20px;border-top:1px solid var(--line);background:var(--sky-soft);
}
.ocr-pagi{display:flex;gap:5px;list-style:none;margin:0;padding:0}
.ocr-pagi li{display:flex}
.ocr-pagi a, .ocr-pagi span{
  display:flex;align-items:center;justify-content:center;
  min-width:32px;height:32px;padding:0 8px;border-radius:7px;border:1px solid var(--line);
  background:var(--panel);font-size:12.5px;font-weight:600;color:var(--muted);
  text-decoration:none;font-family:'Inter',sans-serif;
}
.ocr-pagi li.active span{background:var(--blue);color:#fff;border-color:var(--blue)}
.ocr-pagi li.disabled span{color:var(--muted-soft);cursor:default}
.ocr-pagi a:hover{border-color:var(--blue);color:var(--blue)}
</style>

<div class="ocr-wrap">

  <div class="ocr-head">
    <div>
      <h1 class="ocr-disp">Danh sách hồ sơ CCCD</h1>
      <p>Tra cứu và quản lý dữ liệu căn cước công dân đã quét bằng OCR</p>
    </div>
  </div>

  <form method="GET" class="ocr-searchbar">
    <div class="ocr-search-input">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input type="text" name="keyword" placeholder="Nhập CCCD hoặc họ tên…" value="{{ request('keyword') }}">
    </div>
    <button type="submit" class="ocr-btn-primary">Tìm kiếm</button>
  </form>

  <div class="ocr-panel">
    <div class="ocr-panel-head">
      <h3>Hồ sơ đã quét</h3>
      @if($documents->total() ?? false)
        <span>Tổng {{ $documents->total() }} hồ sơ</span>
      @endif
    </div>
    <table class="ocr-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Hồ sơ</th>
          <th>Số CCCD</th>
          <th>Ngày sinh</th>
          <th>Giới tính</th>
          <th style="text-align:right">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($documents as $doc)
        @php
          $nameParts = preg_split('/\s+/', trim($doc->full_name ?? ''));
          $initials = mb_strtoupper(mb_substr($nameParts[0] ?? '', 0, 1) . mb_substr($nameParts[1] ?? '', 0, 1));
        @endphp
        <tr>
          <td><span class="ocr-id">#{{ $doc->id }}</span></td>
          <td>
            <div class="doc-cell">
              <div class="doc-avatar">{{ $initials }}</div>
              <div class="doc-info">
                <strong>{{ $doc->full_name }}</strong>
                <span>Hồ sơ CCCD</span>
              </div>
            </div>
          </td>
          <td><span class="id-chip">{{ $doc->id_number }}</span></td>
          <td>{{ $doc->birth_date }}</td>
          <td><span class="gender-tag">{{ $doc->gender }}</span></td>
          <td>
            <div class="row-actions">
              <a href="{{ route('documents.show', $doc->id) }}" class="act-btn view">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Xem
              </a>

              @if ((auth()->user()->role ?? null) === 'admin')
              <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn muốn xóa?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="act-btn delete">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                  Xóa
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="ocr-empty">🗂️ Chưa có hồ sơ nào được tìm thấy</div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if ($documents->hasPages())
  <div class="ocr-panel-footer">
    <ul class="ocr-pagi">

      {{-- Previous --}}
      @if ($documents->onFirstPage())
        <li class="disabled"><span>‹</span></li>
      @else
        <li><a href="{{ $documents->previousPageUrl() }}">‹</a></li>
      @endif

      {{-- Page Numbers --}}
      @foreach ($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
        @if ($page == $documents->currentPage())
          <li class="active"><span>{{ $page }}</span></li>
        @else
          <li><a href="{{ $url }}">{{ $page }}</a></li>
        @endif
      @endforeach

      {{-- Next --}}
      @if ($documents->hasMorePages())
        <li><a href="{{ $documents->nextPageUrl() }}">›</a></li>
      @else
        <li class="disabled"><span>›</span></li>
      @endif

    </ul>
  </div>
  @endif

</div>

@endsection