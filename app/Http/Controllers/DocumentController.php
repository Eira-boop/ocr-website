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
            ->lang('vie+eng')
            ->run();

        // Số CCCD
        preg_match('/\b\d{12}\b/', $text, $cccd);

        // Ngày sinh
        preg_match('/\d{2}\/\d{2}\/\d{4}/', $text, $dob);

        // Giới tính
        preg_match('/\b(Nam|Nữ)\b/u', $text, $gender);

        // Họ tên
        $name = '';
        $lines = explode("\n", $text);

        for ($i = 0; $i < count($lines); $i++) {

            if (
                stripos($lines[$i], 'Full name') !== false ||
                stripos($lines[$i], 'Ho va ten') !== false
            ) {

                if (isset($lines[$i + 2])) {

                    $name = trim($lines[$i + 2]);

                    $name = preg_replace('/[^A-ZÀ-Ỹ\s]/u', '', $name);

                    $name = trim($name);
                }

                break;
            }
        }

        // Lưu MySQL
        Document::create([
            'full_name'   => $name,
            'id_number'   => $cccd[0] ?? '',
            'birth_date'  => !empty($dob[0])
                ? date('Y-m-d', strtotime(str_replace('/', '-', $dob[0])))
                : null,
            'gender'      => $gender[0] ?? '',
            'nationality' => 'Việt Nam',
            'address'     => '',
            'issue_date'  => null,
            'image_path'  => 'images/' . $fileName
        ]);

        return view('result', [
            'cccd' => $cccd[0] ?? '',
            'name' => $name,
            'dob' => $dob[0] ?? '',
            'gender' => $gender[0] ?? '',
            'rawText' => $text
        ]);
    }

   public function exportWord(Request $request)
{
    $phpWord = new PhpWord();

    $section = $phpWord->addSection();

    $section->addText(
        'THÔNG TIN HỒ SƠ',
        ['bold' => true, 'size' => 16]
    );

    $table = $section->addTable();

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

    if (
        $request->new_field_name &&
        $request->new_field_value
    ) {
        $table->addRow();

        $table->addCell(3000)
              ->addText($request->new_field_name);

        $table->addCell(6000)
              ->addText($request->new_field_value);
    }

    $fileName = 'HoSo_' . time() . '.docx';

    $path = storage_path(
        'app/public/' . $fileName
    );

    $writer = IOFactory::createWriter(
        $phpWord,
        'Word2007'
    );

    $writer->save($path);

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
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

    return redirect()
        ->route('documents.list')
        ->with('success', 'Xóa thành công');
}
public function dashboard()
{
    $totalDocuments = Document::count();

    return view('dashboard', compact('totalDocuments'));
}
public function uploadWord(Request $request)
{
    $request->validate([
        'word_file' => 'required|mimes:docx'
    ]);

    $file = $request->file('word_file');

    try {

        $phpWord = \PhpOffice\PhpWord\IOFactory::load(
            $file->getPathname()
        );

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
    $file = $request->file('excel_file');

    $spreadsheet = SpreadsheetIOFactory::load(
        $file->getPathname()
    );

    $sheet = $spreadsheet->getActiveSheet();

    $data = $sheet->toArray();

    return view('excel_result', compact('data'));
}
public function preview(Request $request)
{
    $result = [];

    if(in_array('cccd',$request->fields ?? []))
    {
        $result['Số CCCD'] = $request->cccd;
    }

    if(in_array('name',$request->fields ?? []))
    {
        $result['Họ tên'] = $request->name;
    }

    if(in_array('dob',$request->fields ?? []))
    {
        $result['Ngày sinh'] = $request->dob;
    }

    if(in_array('gender',$request->fields ?? []))
    {
        $result['Giới tính'] = $request->gender;
    }

    if(
        !empty($request->new_field_name)
        &&
        !empty($request->new_field_value)
    )
    {
        $result[$request->new_field_name]
            = $request->new_field_value;
    }

    return view('preview', compact('result'));
}
public function exportSelectedWord(Request $request)
{
    $phpWord = new PhpWord();

    $section = $phpWord->addSection();

    $section->addText(
        'THÔNG TIN HỒ SƠ',
        [
            'bold' => true,
            'size' => 16
        ]
    );

    $table = $section->addTable();

    $keys = $request->keys;
    $values = $request->values;

    foreach($keys as $index => $key)
    {
        $table->addRow();

        $table->addCell(3000)
              ->addText($key);

        $table->addCell(6000)
              ->addText($values[$index]);
    }

    $fileName = 'HoSo_' . time() . '.docx';

    $path = storage_path(
        'app/public/' . $fileName
    );

    $writer = IOFactory::createWriter(
        $phpWord,
        'Word2007'
    );

    $writer->save($path);

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
}
public function exportExcel(Request $request)
{
    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    // Tiêu đề
    $sheet->setCellValue('A1', 'Họ tên');
    $sheet->setCellValue('B1', 'CCCD');
    $sheet->setCellValue('C1', 'Ngày sinh');
    $sheet->setCellValue('D1', 'Giới tính');

    // Dữ liệu OCR
    $sheet->setCellValue('A2', $request->name);
    $sheet->setCellValue('B2', $request->cccd);
    $sheet->setCellValue('C2', $request->dob);
    $sheet->setCellValue('D2', $request->gender);

    $fileName = 'CCCD_' . time() . '.xlsx';

    $path = storage_path(
        'app/public/' . $fileName
    );

    $writer = new Xlsx($spreadsheet);

    $writer->save($path);

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
}
public function exportAllExcel()
{
    $documents = Document::all();

    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'STT');
    $sheet->setCellValue('B1', 'Họ tên');
    $sheet->setCellValue('C1', 'CCCD');
    $sheet->setCellValue('D1', 'Ngày sinh');
    $sheet->setCellValue('E1', 'Giới tính');

    $row = 2;
    $stt = 1;

    foreach ($documents as $doc)
    {
        $sheet->setCellValue('A'.$row, $stt);
        $sheet->setCellValue('B'.$row, $doc->full_name);
        $sheet->setCellValue('C'.$row, $doc->id_number);
        $sheet->setCellValue('D'.$row, $doc->birth_date);
        $sheet->setCellValue('E'.$row, $doc->gender);

        $row++;
        $stt++;
    }

    $fileName = 'DanhSachCCCD.xlsx';

    $path = storage_path(
        'app/public/'.$fileName
    );

    $writer = new Xlsx($spreadsheet);

    $writer->save($path);

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
}
}