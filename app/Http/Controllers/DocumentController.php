<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

use PhpOffice\PhpSpreadsheet\IOFactory as SpreadsheetIOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Smalot\PdfParser\Parser;
use App\Services\OCRService;

use App\Models\Document;

class DocumentController extends Controller
{
    protected $ocrService;

public function __construct(OCRService $ocrService)
{
    $this->ocrService = $ocrService;
}
    public function index()
    {
        return view('upload');
    }

public function upload(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png,bmp,webp|max:10240',
        'documentType' => 'nullable|string',
    ]);

    $image = $request->file('image');

    $fileName = time() . '.' . $image->getClientOriginalExtension();

    $destinationPath = storage_path('app/public/images');

    $image->move($destinationPath, $fileName);

    $imagePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;

    /*
    |--------------------------------------------------------------------------
    | TIỀN XỬ LÝ ẢNH (giúp Tesseract đọc rõ hơn)
    |--------------------------------------------------------------------------
    */
    try{
$img = new \Imagick($imagePath);

// Tự xoay
$img->autoOrient();

// Tăng DPI
$img->setImageResolution(300, 300);

// Phóng to 4 lần
$img->resizeImage(
    $img->getImageWidth() * 4,
    $img->getImageHeight() * 4,
    \Imagick::FILTER_LANCZOS,
    1
);

// Chuyển xám
$img->setImageColorspace(\Imagick::COLORSPACE_GRAY);

// Tăng tương phản
$img->normalizeImage();

// Khử nhiễu
$img->despeckleImage();

// Làm nét
$img->unsharpMaskImage(1, 1, 1.5, 0.02);

// Nhị phân hóa
$img->thresholdImage(0.75 * \Imagick::getQuantum());

$img->writeImage($imagePath);

$img->clear();
$img->destroy();

    } catch (\Exception $e) {
        // Nếu Imagick lỗi (vd thiếu extension), vẫn tiếp tục OCR ảnh gốc
    }

    // OCR theo loại tài liệu người dùng chọn (hoặc auto-detect)
    $result = $this->ocrService->scan($imagePath, $request->documentType ?? 'auto');

    $document = Document::create([
        'document_type' => $result['document_type'] ?? 'other',
        'full_name'      => $result['full_name'] ?? '',
        'id_number'      => $result['id_number'] ?? '',
        'birth_date'     => !empty($result['birth_date'])
            ? date('Y-m-d', strtotime(str_replace('/', '-', $result['birth_date'])))
            : null,
        'gender'         => $result['gender'] ?? '',
        'nationality'    => $result['nationality'] ?? '',
        'address'        => $result['address'] ?? '',
        'passport_number'=> $result['passport_number'] ?? null,
        'student_id'     => $result['student_id'] ?? null,
        'school_name'    => $result['school_name'] ?? null,
        'class_name'     => $result['class_name'] ?? null,
        'major'          => $result['major'] ?? null,
        'gpa'            => $result['gpa'] ?? null,
        'classification' => $result['classification'] ?? null,
        'father_name'    => $result['father_name'] ?? null,
        'mother_name'    => $result['mother_name'] ?? null,
        'ethnic'         => $result['ethnic'] ?? null,
        'place_of_birth' => $result['place_of_birth'] ?? null,
        'issue_date'     => !empty($result['issue_date'])
            ? date('Y-m-d', strtotime(str_replace('/', '-', $result['issue_date'])))
            : null,
        'image_path' => 'images/' . $fileName,
        'raw_text'   => $result['raw_text'] ?? '',
    ]);
        // === LƯU SESSION ĐỂ REUSE SAU NÀY ===
    session([
        'last_ocr_result'       => $result,
        'last_ocr_raw_text'     => $result['raw_text'] ?? $rawText ?? '',
        'last_ocr_image_path'   => 'images/' . $fileName,
        'last_ocr_document_id'  => $document->id,
        'last_ocr_type'         => $result['document_type'] ?? 'other',
    ]);

    return view('result', [
        'document'   => $document,
        'result'     => $result,
        'documentId' => $document->id,
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
    $documents = Document::latest()->paginate(10);

    $totalDocuments = Document::count();

    return view('dashboard', compact(
        'documents',
        'totalDocuments'
    ));
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
        return redirect()->back()->with('error', 'Vui lòng chọn ảnh!');
    }

    $image = $request->file('image');
    $fileName = time() . '_back.' . $image->getClientOriginalExtension();
    $destPath = storage_path('app/public/images');
    $image->move($destPath, $fileName);
    $imagePath = $destPath . DIRECTORY_SEPARATOR . $fileName;

    $text = (new TesseractOCR($imagePath))
        ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
        ->tessdataDir('C:\Program Files\Tesseract-OCR\tessdata')
        ->lang('vie+eng')
        ->psm(6)
        ->oem(3)
        ->run();

    // Làm sạch text nhiễu
    $cleanText = preg_replace('/[^A-Za-z0-9À-ỹ\/\-\.\s:]/u', ' ', $text);
    $cleanText = preg_replace('/\s+/', ' ', $cleanText);

    // Trích xuất ngày
    $issueDate = '';
    if (preg_match('/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/', $cleanText, $m)) {
        $issueDate = $m[1];
    }

    // Ngày hết hạn (thường là ngày thứ 2)
    $expireDate = '';
    preg_match_all('/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/', $cleanText, $matches);
    if (count($matches[1]) >= 2) {
        $expireDate = $matches[1][1];
    }

    $issuedBy = 'Cục Cảnh sát Quản lý Hành chính về Trật tự Xã hội'; // mặc định cho CCCD

    $features = '';
    if (preg_match('/Đặc điểm nhận dạng[:\s]*(.+?)(?=\n|$)/iu', $cleanText, $m) || 
        preg_match('/Personal identification[:\s]*(.+?)(?=\n|$)/iu', $cleanText, $m)) {
        $features = trim($m[1]);
    }

    // Update database
    $docId = $request->document_id ?? null;
    if ($docId) {
        Document::where('id', $docId)->update([
            'issue_date' => !empty($issueDate) 
                ? date('Y-m-d', strtotime(str_replace('/', '-', $issueDate))) 
                : null,
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
public function uploadPdf(Request $request)
{
    set_time_limit(300);
    ini_set('max_execution_time', 300);

    $request->validate([
        'pdf_file' => 'required|mimes:pdf',
    ]);

    try {

        /*
        |--------------------------------------------------------------------------
        | LẤY FILE PDF
        |--------------------------------------------------------------------------
        */

        $pdf = $request->file('pdf_file');

        $pdfPath = $pdf->getRealPath();

        /*
        |--------------------------------------------------------------------------
        | TẠO FILE PNG
        |--------------------------------------------------------------------------
        */

        $imageWithoutExt = storage_path('app/public/pdf_' . time());

        /*
        |--------------------------------------------------------------------------
        | PDF -> PNG
        |--------------------------------------------------------------------------
        */

        $command =
            '"C:\\poppler\\poppler-26.02.0\\Library\\bin\\pdftoppm.exe" ' .
            '-png ' .
            '-r 300 ' .
            '-f 1 ' .
            '-singlefile "' .
            $pdfPath .
            '" "' .
            $imageWithoutExt .
            '"';

        $output = [];
        $returnCode = 0;

        exec($command . ' 2>&1', $output, $returnCode);

        $imagePath = $imageWithoutExt . '.png';

        if ($returnCode != 0 || !file_exists($imagePath)) {

            dd([
                'command' => $command,
                'returnCode' => $returnCode,
                'output' => $output,
                'imagePath' => $imagePath,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | XỬ LÝ ẢNH
        |--------------------------------------------------------------------------
        */

        $img = new \Imagick($imagePath);

        // ảnh xám
        $img->transformImageColorspace(\Imagick::COLORSPACE_GRAY);

        // tăng sáng nhẹ
        $img->brightnessContrastImage(5, 10);

        // làm nét nhẹ
        $img->sharpenImage(0, 0.8);

        // ghi đè
        $img->writeImage($imagePath);

        $img->clear();
        $img->destroy();

        /*
        |--------------------------------------------------------------------------
        | OCR
        |--------------------------------------------------------------------------
        */

        $text = (new TesseractOCR($imagePath))
            ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
            ->tessdataDir('C:\Program Files\Tesseract-OCR\tessdata')
            ->lang('vie+eng')
            ->psm(6)
            ->oem(1)
            ->run();

        /*
        |--------------------------------------------------------------------------
        | LÀM SẠCH OCR
        |--------------------------------------------------------------------------
        */

        $text = str_replace(
            ['(', ')', 'O', 'o'],
            ['0', '0', '0', '0'],
            $text
        );

        $lines = explode("\n", $text);
                /*
        |--------------------------------------------------------------------------
        | LẤY CCCD
        |--------------------------------------------------------------------------
        */

        $cccdValue = '';

        // Ưu tiên dòng có "Số/No"
        if (preg_match('/(?:S[oố]|No)[^\d]{0,10}([\d ]{12,20})/iu', $text, $m)) {
        // OCR thường đọc nhầm
$text = str_replace(
    ['Q', 'O', 'o', 'D'],
    ['0', '0', '0', '0'],
    $text
);
            $tmp = preg_replace('/\D/', '', $m[1]);

            if (strlen($tmp) >= 12) {
                $cccdValue = substr($tmp, 0, 12);
            }
        }

        // Nếu chưa có thì quét toàn bộ
$cccdValue = '';

if (preg_match('/(?:S[oố]|No)[^0-9]{0,10}([0-9 ]{10,20})/iu', $text, $m)) {

    $tmp = preg_replace('/\D/', '', $m[1]);

    if (strlen($tmp) == 12) {

        $cccdValue = $tmp;

    } elseif (strlen($tmp) == 11) {

        $cccdValue = '0' . $tmp;
    }
}

if (empty($cccdValue)) {

    preg_match_all('/[0-9 ]{10,20}/', $text, $numbers);

    foreach ($numbers[0] as $number) {

        $tmp = preg_replace('/\D/', '', $number);

        if (strlen($tmp) == 12) {

            $cccdValue = $tmp;
            break;

        } elseif (strlen($tmp) == 11) {

            $cccdValue = '0' . $tmp;
            break;
        }
    }
}

        /*
        |--------------------------------------------------------------------------
        | NGÀY SINH
        |--------------------------------------------------------------------------
        */

        $birthDate = '';

        if (preg_match('/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/', $text, $dob)) {

            $birthDate = $dob[1];
        }

        /*
        |--------------------------------------------------------------------------
        | GIỚI TÍNH
        |--------------------------------------------------------------------------
        */

        $genderValue = '';

        if (
            preg_match('/Sex.{0,15}N[uữ]/iu', $text) ||
            preg_match('/Giới.{0,15}N[uữ]/iu', $text)
        ) {

            $genderValue = 'Nữ';

        } elseif (

            preg_match('/Sex.{0,15}Nam/iu', $text) ||
            preg_match('/Giới.{0,15}Nam/iu', $text)

        ) {

            $genderValue = 'Nam';
        }

        /*
        |--------------------------------------------------------------------------
        | HỌ TÊN
        |--------------------------------------------------------------------------
        */
$name = '';

foreach ($lines as $line) {

    $line = trim($line);

    // bỏ dòng quá ngắn
    if (mb_strlen($line) < 5) continue;

    // bỏ dòng có số
    if (preg_match('/\d/', $line)) continue;

    // chỉ giữ chữ + khoảng trắng
    if (!preg_match('/^[A-ZÀ-Ỹa-zà-ỹ ]+$/u', $line)) continue;

    // loại dòng rác hệ thống OCR
    if (
        stripos($line, 'VIET NAM') !== false ||
        stripos($line, 'SOCIALIST') !== false ||
        stripos($line, 'CĂN CƯỚC') !== false
    ) continue;

    // ưu tiên dòng kiểu NAME (viết hoa nhiều)
    if (mb_strtoupper($line) === $line && mb_strlen($line) >= 6) {
        $name = $line;
        break;
    }
}

        /*
        |--------------------------------------------------------------------------
        | LƯU DATABASE
        |--------------------------------------------------------------------------
        */

        Document::create([

            'full_name'   => $name,

            'id_number'   => $cccdValue,

            'birth_date'  => !empty($birthDate)
                ? date(
                    'Y-m-d',
                    strtotime(str_replace('/', '-', $birthDate))
                )
                : null,

            'gender'      => $genderValue,

            'nationality' => 'Việt Nam',

            'address'     => '',

            'issue_date'  => null,

            'image_path'  => basename($imagePath),
        ]);

        /*
        |--------------------------------------------------------------------------
        | HIỂN THỊ
        |--------------------------------------------------------------------------
        */

        return view('result', [

            'cccd'    => $cccdValue,

            'name'    => $name,

            'dob'     => $birthDate,

            'gender'  => $genderValue,

            'rawText' => $text,
        ]);

    } catch (\Exception $e) {

        dd([
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);
    }
}
public function updateExtracted(Request $request, $id)
{
    $document = Document::findOrFail($id);

    $allowedFields = [
        'full_name', 'id_number', 'birth_date', 'gender', 'nationality', 'address',
        'passport_number', 'student_id', 'school_name', 'class_name', 'major',
        'gpa', 'classification', 'father_name', 'mother_name', 'ethnic',
        'place_of_birth', 'issue_date',
    ];

    $data = $request->only($allowedFields);

    // Format lại ngày nếu có
    foreach (['birth_date', 'issue_date'] as $dateField) {
        if (!empty($data[$dateField])) {
            $data[$dateField] = date('Y-m-d', strtotime(str_replace('/', '-', $data[$dateField])));
        }
    }

    $document->update($data);

    return redirect()->route('documents.show', $document->id)
        ->with('success', 'Đã lưu thông tin chỉnh sửa.');
}
    /**
     * Dùng lại ảnh + kết quả OCR lần trước (không cần upload lại)
     */
    public function reuseLast()
    {
        if (!session('last_ocr_result')) {
            return redirect()->route('ocr')  // hoặc route upload của bạn
                ->with('error', 'Không có dữ liệu OCR trước đó. Vui lòng upload ảnh mới.');
        }

        $result     = session('last_ocr_result');
        $rawText    = session('last_ocr_raw_text');
        $imagePath  = session('last_ocr_image_path');
        $documentId = session('last_ocr_document_id');

        return view('result', [
            'document'   => $documentId ? Document::find($documentId) : null,
            'result'     => $result,
            'rawText'    => $rawText,
            'imagePath'  => $imagePath,
            'documentId' => $documentId,
        ]);
    }

}
