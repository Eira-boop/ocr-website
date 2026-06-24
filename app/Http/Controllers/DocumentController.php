<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Document;

class DocumentController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        if (!$request->hasFile('image')) {
            return "Chưa chọn ảnh!";
        }

        $image = $request->file('image');

        $fileName = time() . '.' . $image->getClientOriginalExtension();

        $destinationPath = storage_path('app/public/images');

        $image->move($destinationPath, $fileName);

        $imagePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

        $text = (new TesseractOCR($imagePath))
            ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
            ->tessdataDir('C:\Program Files\Tesseract-OCR\tessdata')
            ->lang('vie')
            ->run();

        // ── Số CCCD ──────────────────────────────────────────────────
        preg_match('/(?<!\d)(\d{12})(?!\d)/', $text, $cccd);
        $cccdValue = $cccd[1] ?? '';

        // ── Ngày sinh ────────────────────────────────────────────────
        preg_match('/\D{0,3}(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4})/', $text, $dob);
        $birthDate = $dob[1] ?? '';

        // Fix OCR đọc nhầm 09 thành 99
        if (str_starts_with($birthDate, '99/')) {
            $birthDate = str_replace('99/', '09/', $birthDate);
        }

        // ── Giới tính ────────────────────────────────────────────────
        preg_match('/(?:Sex|tính)[^\n]{0,20}(Nam|Nữ)/u', $text, $gender);
        if (empty($gender[1])) {
            preg_match('/(Nam|Nữ)/u', $text, $gender);
        }
        $genderValue = $gender[1] ?? '';

        // ── Họ tên ───────────────────────────────────────────────────
        $name  = '';
        $lines = explode("\n", $text);

        foreach ($lines as $index => $line) {
            if (
                stripos($line, 'Full') !== false ||
                stripos($line, 'Họ và tên') !== false
            ) {
                for ($j = $index + 1; $j <= $index + 3; $j++) {
                    if (!empty($lines[$j])) {
                        $candidate = trim($lines[$j]);
                        $candidate = preg_replace('/[^A-ZÀ-Ỹa-zà-ỹ\s]/u', '', $candidate);

                        if (mb_strlen($candidate) > 5) {
                            $name = trim($candidate);
                            break 2;
                        }
                    }
                }
            }
        }

        // Tách âm tiết bị dính (VD: MINHANH → MINH ANH)
        if (!empty($name)) {
            $name = $this->splitVietnameseName($name);
        }

        // ── Lưu MySQL ────────────────────────────────────────────────
        Document::create([
            'full_name'   => $name,
            'id_number'   => $cccdValue,
            'birth_date'  => !empty($birthDate)
                ? date('Y-m-d', strtotime(str_replace('/', '-', $birthDate)))
                : null,
            'gender'      => $genderValue,
            'nationality' => 'Việt Nam',
            'address'     => '',
            'issue_date'  => null,
            'image_path'  => 'images/' . $fileName,
        ]);

        return view('result', [
            'cccd'    => $cccdValue,
            'name'    => $name,
            'dob'     => $birthDate,
            'gender'  => $genderValue,
            'rawText' => $text,
        ]);
    }

    public function exportWord(Request $request)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('THÔNG TIN HỒ SƠ', ['bold' => true, 'size' => 16]);

        $table  = $section->addTable();
        $fields = $request->fields ?? [];

        if (in_array('cccd', $fields)) {
            $table->addRow();
            $table->addCell(3000)->addText('Số CCCD');
            $table->addCell(6000)->addText($request->cccd);
        }

        if (in_array('name', $fields)) {
            $table->addRow();
            $table->addCell(3000)->addText('Họ tên');
            $table->addCell(6000)->addText($request->name);
        }

        if (in_array('dob', $fields)) {
            $table->addRow();
            $table->addCell(3000)->addText('Ngày sinh');
            $table->addCell(6000)->addText($request->dob);
        }

        if (in_array('gender', $fields)) {
            $table->addRow();
            $table->addCell(3000)->addText('Giới tính');
            $table->addCell(6000)->addText($request->gender);
        }

        if ($request->new_field_name && $request->new_field_value) {
            $table->addRow();
            $table->addCell(3000)->addText($request->new_field_name);
            $table->addCell(6000)->addText($request->new_field_value);
        }

        $fileName = 'HoSo_' . time() . '.docx';
        $path     = storage_path('app/public/' . $fileName);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function list(Request $request)
    {
        $query = Document::query();

        if ($request->keyword) {
            $query->where('full_name', 'like', '%' . $request->keyword . '%')
                  ->orWhere('id_number', 'like', '%' . $request->keyword . '%');
        }

        $documents = $query->latest()->paginate(10);

        return view('documents.list', compact('documents'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);

        return view('documents.show', compact('document'));
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return redirect()->route('documents.list')->with('success', 'Xóa thành công');
    }

    public function dashboard()
    {
        $totalDocuments = Document::count();

        return view('dashboard', compact('totalDocuments'));
    }

    public function uploadWord(Request $request)
    {
        $request->validate(['word_file' => 'required|mimes:docx']);

        $file = $request->file('word_file');

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getPathname());
            $content = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $content .= $element->getText() . "\n";
                    }
                }
            }

            return view('word_result', compact('content'));

        } catch (\Exception $e) {
            return 'Lỗi đọc file Word: ' . $e->getMessage();
        }
    }

    public function uploadExcel(Request $request)
    {
        $file        = $request->file('excel_file');
        $spreadsheet = SpreadsheetIOFactory::load($file->getPathname());
        $sheet       = $spreadsheet->getActiveSheet();
        $data        = $sheet->toArray();

        return view('excel_result', compact('data'));
    }

    public function preview(Request $request)
    {
        $result = [];

        if (in_array('cccd', $request->fields ?? [])) {
            $result['Số CCCD'] = $request->cccd;
        }

        if (in_array('name', $request->fields ?? [])) {
            $result['Họ tên'] = $request->name;
        }

        if (in_array('dob', $request->fields ?? [])) {
            $result['Ngày sinh'] = $request->dob;
        }

        if (in_array('gender', $request->fields ?? [])) {
            $result['Giới tính'] = $request->gender;
        }

        if (!empty($request->new_field_name) && !empty($request->new_field_value)) {
            $result[$request->new_field_name] = $request->new_field_value;
        }

        return view('preview', compact('result'));
    }

    public function exportSelectedWord(Request $request)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('THÔNG TIN HỒ SƠ', ['bold' => true, 'size' => 16]);

        $table  = $section->addTable();
        $keys   = $request->keys;
        $values = $request->values;

        foreach ($keys as $index => $key) {
            $table->addRow();
            $table->addCell(3000)->addText($key);
            $table->addCell(6000)->addText($values[$index]);
        }

        $fileName = 'HoSo_' . time() . '.docx';
        $path     = storage_path('app/public/' . $fileName);

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function exportExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Họ tên');
        $sheet->setCellValue('B1', 'CCCD');
        $sheet->setCellValue('C1', 'Ngày sinh');
        $sheet->setCellValue('D1', 'Giới tính');

        $sheet->setCellValue('A2', $request->name);
        $sheet->setCellValue('B2', $request->cccd);
        $sheet->setCellValue('C2', $request->dob);
        $sheet->setCellValue('D2', $request->gender);

        $fileName = 'CCCD_' . time() . '.xlsx';
        $path     = storage_path('app/public/' . $fileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    // ── Private helper ────────────────────────────────────────────────
    private function splitVietnameseName(string $name): string
    {
        $syllables = [
            'THANH', 'THÀNH', 'THỊNH', 'THẢO', 'THÚY', 'THÙY', 'THỦY',
            'PHƯƠNG', 'HƯƠNG', 'CƯỜNG', 'TRUNG', 'DŨNG', 'HÙNG', 'TUẤN',
            'KHANH', 'KHANG', 'KHOA', 'GIANG', 'NGÂN', 'NGỌC', 'NHUNG',
            'TUYỀN', 'UYÊN', 'TRANG', 'VINH', 'VIỆT', 'PHONG', 'TÙNG',
            'PHÚC', 'KIÊN', 'KIỆT', 'HỒNG', 'LONG', 'HƯNG', 'SANG',
            'LOAN', 'DUNG', 'LINH', 'MINH', 'BÌNH', 'MẠNH', 'HIẾU',
            'HIỀN', 'HÒA', 'HÀO', 'ĐÔNG', 'ĐẠT', 'ĐỨC', 'BÍCH',
            'BẢO', 'VĂN', 'VÂN', 'VÕ', 'THU', 'SƠN', 'PHÚ', 'KIM',
            'GIA', 'HÂN', 'HÀN', 'HÀ', 'HOA', 'ANH', 'LAN', 'NHI',
            'CHI', 'THI', 'MỸ', 'VY', 'MY', 'NAM', 'NGA', 'AN',
        ];

        // Sắp xếp dài trước để tránh match ngắn trước
        usort($syllables, fn($a, $b) => mb_strlen($b, 'UTF-8') - mb_strlen($a, 'UTF-8'));

        $words  = explode(' ', $name);
        $result = [];

        foreach ($words as $word) {
            $word    = mb_strtoupper(trim($word), 'UTF-8');
            $wordLen = mb_strlen($word, 'UTF-8');

            // Từ ngắn ≤ 4 ký tự: giữ nguyên
            if ($wordLen <= 4) {
                $result[] = $word;
                continue;
            }

            $split = false;

            foreach ($syllables as $syl) {
                $sylUpper = mb_strtoupper($syl, 'UTF-8');
                $sylLen   = mb_strlen($sylUpper, 'UTF-8');

                // Từ phải KẾT THÚC bằng âm tiết này
                if (
                    $wordLen > $sylLen &&
                    mb_substr($word, -$sylLen, null, 'UTF-8') === $sylUpper
                ) {
                    $prefix    = mb_substr($word, 0, $wordLen - $sylLen, 'UTF-8');
                    $prefixLen = mb_strlen($prefix, 'UTF-8');

                    // Prefix phải có ít nhất 2 ký tự
                    if ($prefixLen >= 2) {
                        $result[] = $prefix;
                        $result[] = $sylUpper;
                        $split    = true;
                        break;
                    }
                }
            }

            if (!$split) {
                $result[] = $word;
            }
        }

        return implode(' ', $result);
    }
    public function exportAllExcel()
{
    $documents = Document::all();

    $spreadsheet = new Spreadsheet();
    $sheet       = $spreadsheet->getActiveSheet();

    // Tiêu đề cột
    $sheet->setCellValue('A1', 'Họ tên');
    $sheet->setCellValue('B1', 'CCCD');
    $sheet->setCellValue('C1', 'Ngày sinh');
    $sheet->setCellValue('D1', 'Giới tính');
    $sheet->setCellValue('E1', 'Quốc tịch');
    $sheet->setCellValue('F1', 'Ảnh');

    // Dữ liệu từng dòng
    foreach ($documents as $i => $doc) {
        $row = $i + 2;
        $sheet->setCellValue('A' . $row, $doc->full_name);
        $sheet->setCellValue('B' . $row, $doc->id_number);
        $sheet->setCellValue('C' . $row, $doc->birth_date);
        $sheet->setCellValue('D' . $row, $doc->gender);
        $sheet->setCellValue('E' . $row, $doc->nationality);
        $sheet->setCellValue('F' . $row, $doc->image_path);
    }

    $fileName = 'DanhSach_CCCD_' . time() . '.xlsx';
    $path     = storage_path('app/public/' . $fileName);

    $writer = new Xlsx($spreadsheet);
    $writer->save($path);

    return response()->download($path)->deleteFileAfterSend(true);
}
public function uploadBack(Request $request)
{
    if (!$request->hasFile('image')) {
        return "Chưa chọn ảnh!";
    }

    $image    = $request->file('image');
    $fileName = time() . '_back.' . $image->getClientOriginalExtension();
    $destPath = storage_path('app/public/images');

    $image->move($destPath, $fileName);

    $imagePath = $destPath . DIRECTORY_SEPARATOR . $fileName;

    $text = (new TesseractOCR($imagePath))
        ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
        ->tessdataDir('C:\Program Files\Tesseract-OCR\tessdata')
        ->lang('vie')
        ->run();

    // ── Ngày cấp ─────────────────────────────────────────────────
    // Mặt sau có dạng: "Ngày cấp / Date of issue: 01/01/2021"
    $issueDate = '';
    if (preg_match('/(?:issue|cấp)[^\d]{0,30}(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4})/iu', $text, $m)) {
        $issueDate = $m[1];
    }

    // ── Ngày hết hạn ─────────────────────────────────────────────
    // Lấy ngày thứ 2 xuất hiện trong text (ngày cấp là thứ 1)
    $expireDate = '';
    preg_match_all('/\D{0,3}(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4})/', $text, $allDates);
    if (!empty($allDates[1])) {
        // Lọc bỏ ngày trùng với issueDate
        $otherDates = array_filter(
            $allDates[1],
            fn($d) => $d !== $issueDate
        );
        $expireDate = array_values($otherDates)[0] ?? '';
    }

    // ── Nơi cấp ──────────────────────────────────────────────────
    // Dòng sau "Nơi cấp" hoặc "Place of issue"
    $issuedBy = '';
    $lines    = explode("\n", $text);

    foreach ($lines as $idx => $line) {
        if (
            stripos($line, 'Nơi cấp') !== false ||
            stripos($line, 'Place of issue') !== false ||
            stripos($line, 'issued by') !== false
        ) {
            for ($j = $idx; $j <= $idx + 2; $j++) {
                if (!isset($lines[$j])) continue;

                // Tìm phần sau dấu ":" trên cùng dòng
                if (str_contains($lines[$j], ':')) {
                    $parts = explode(':', $lines[$j], 2);
                    $val   = trim($parts[1]);
                    if (mb_strlen($val) > 3) {
                        $issuedBy = $val;
                        break 2;
                    }
                }

                // Hoặc lấy dòng tiếp theo
                if ($j > $idx && !empty(trim($lines[$j]))) {
                    $issuedBy = trim($lines[$j]);
                    break 2;
                }
            }
        }
    }

    // ── Đặc điểm nhận dạng ───────────────────────────────────────
    $features = '';
    foreach ($lines as $idx => $line) {
        if (
            stripos($line, 'Đặc điểm') !== false ||
            stripos($line, 'Personal identification') !== false ||
            stripos($line, 'identifying') !== false
        ) {
            // Ghép 1-2 dòng tiếp theo
            $parts = [];
            for ($j = $idx + 1; $j <= $idx + 2; $j++) {
                if (!empty(trim($lines[$j] ?? ''))) {
                    $parts[] = trim($lines[$j]);
                }
            }
            $features = implode(', ', $parts);
            break;
        }
    }

    // ── Cập nhật Document nếu đã có bản ghi (match theo id) ──────
    $docId = $request->document_id ?? null;

    if ($docId) {
        Document::where('id', $docId)->update([
            'issue_date'  => !empty($issueDate)
                ? date('Y-m-d', strtotime(str_replace('/', '-', $issueDate)))
                : null,
            'issued_by'   => $issuedBy,
            'expire_date' => !empty($expireDate)
                ? date('Y-m-d', strtotime(str_replace('/', '-', $expireDate)))
                : null,
            'features'    => $features,
        ]);
    }

    return view('result_back', [
        'issueDate'  => $issueDate,
        'expireDate' => $expireDate,
        'issuedBy'   => $issuedBy,
        'features'   => $features,
        'rawText'    => $text,
        'documentId' => $docId,
    ]);
}
}
