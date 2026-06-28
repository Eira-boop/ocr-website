<?php

namespace App\Extractors;

class AdmissionExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'admission',

            'full_name' => null,

            'student_id' => null,

            'school_name' => null,

            'major' => null,

            'raw_text' => $text

        ];

        if (preg_match('/Họ và tên[:\s]*(.+)/iu', $text, $m)) {
            $data['full_name'] = trim($m[1]);
        }

        if (preg_match('/Số báo danh[:\s]*(.+)/iu', $text, $m)) {
            $data['student_id'] = trim($m[1]);
        }

        if (preg_match('/Trường[:\s]*(.+)/iu', $text, $m)) {
            $data['school_name'] = trim($m[1]);
        }

        if (preg_match('/Ngành[:\s]*(.+)/iu', $text, $m)) {
            $data['major'] = trim($m[1]);
        }

        return $data;
    }
}