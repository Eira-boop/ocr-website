<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Extractors\CCCDExtractor;
use App\Extractors\PassportExtractor;
use App\Extractors\BirthCertificateExtractor;
use App\Extractors\ReportCardExtractor;
use App\Extractors\TranscriptExtractor;
use App\Extractors\DegreeExtractor;

class OCRService
{
    public function read(string $imagePath): string
    {
        return (new TesseractOCR($imagePath))
            ->executable('C:\Program Files\Tesseract-OCR\tesseract.exe')
            ->tessdataDir('C:\Program Files\Tesseract-OCR\tessdata')
            ->lang('vie+eng')
            ->psm(6)
            ->oem(1)
            ->run();
    }

    public function scan(string $imagePath, string $documentType = 'auto'): array
    {
        // Đọc OCR
        $text = $this->read($imagePath);

        $extractor = $this->resolveExtractor($documentType, $text);

        return $extractor->extract($text);
    }

    protected function resolveExtractor(string $documentType, string $text)
    {
        $map = [
            'cccd'       => CCCDExtractor::class,
            'passport'   => PassportExtractor::class,
            'birth'      => BirthCertificateExtractor::class,
            'report'     => ReportCardExtractor::class,
            'transcript' => TranscriptExtractor::class,
            'degree'     => DegreeExtractor::class,
        ];

        if ($documentType !== 'auto' && isset($map[$documentType])) {
            return new $map[$documentType]();
        }

        // 'auto' hoặc 'other': thử tự nhận diện theo từ khóa trong text
        return $this->detectExtractor($text);
    }

    protected function detectExtractor(string $text)
    {
        $map = [
            'CĂN CƯỚC'   => CCCDExtractor::class,
            'PASSPORT'   => PassportExtractor::class,
            'HỘ CHIẾU'   => PassportExtractor::class,
            'KHAI SINH'  => BirthCertificateExtractor::class,
            'HỌC BẠ'     => ReportCardExtractor::class,
            'BẢNG ĐIỂM'  => TranscriptExtractor::class,
            'TỐT NGHIỆP' => DegreeExtractor::class,
            'BẰNG'       => DegreeExtractor::class,
            'DEGREE'     => DegreeExtractor::class,
            'BACHELOR'   => DegreeExtractor::class,
        ];

$name = '';
$lines = explode("\n", $text);
foreach ($lines as $line) {

    $line = trim($line);

    // lọc bỏ dòng rác OCR
    if (strlen($line) < 5) continue;

    // nếu dòng có số hoặc ký tự lạ thì bỏ
    if (preg_match('/[0-9@#$%^&*]/', $line)) continue;

    // giữ lại chỉ chữ
    $clean = preg_replace('/[^A-ZÀ-Ỹa-zà-ỹ\s]/u', '', $line);
    $clean = trim($clean);

    // điều kiện tên hợp lý
    if (mb_strlen($clean) >= 8 && mb_strlen($clean) <= 50) {
        $name = mb_strtoupper($clean);
        break;
    }
}

        // Fallback mặc định
        return new CCCDExtractor();
    }
}