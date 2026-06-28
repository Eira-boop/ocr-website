<?php

namespace App\Extractors;

class TranscriptExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'transcript',

            'full_name' => null,

            'student_id' => null,

            'school_name' => null,

            'major' => null,

            'gpa' => null,

            'classification' => null,

            'raw_text' => $text

        ];

        // MSSV
        if (preg_match('/MSSV[:\s]*([A-Z0-9]+)/iu', $text, $m)) {
            $data['student_id'] = trim($m[1]);
        }

        // Họ tên
        if (preg_match('/Họ và tên[:\s]*(.+)/iu', $text, $m)) {
            $data['full_name'] = trim($m[1]);
        }

        // Trường
        if (preg_match('/Trường[:\s]*(.+)/iu', $text, $m)) {
            $data['school_name'] = trim($m[1]);
        }

        // Ngành
        if (preg_match('/Ngành[:\s]*(.+)/iu', $text, $m)) {
            $data['major'] = trim($m[1]);
        }

        // GPA
        if (preg_match('/([0-4]\.[0-9]{1,2})/', $text, $m)) {
            $data['gpa'] = $m[1];
        }

        // Xếp loại
        if (preg_match('/Xuất sắc/u', $text)) {
            $data['classification'] = 'Xuất sắc';
        } elseif (preg_match('/Giỏi/u', $text)) {
            $data['classification'] = 'Giỏi';
        } elseif (preg_match('/Khá/u', $text)) {
            $data['classification'] = 'Khá';
        }

        return $data;
    }
}