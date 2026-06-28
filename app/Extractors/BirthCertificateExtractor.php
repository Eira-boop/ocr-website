<?php

namespace App\Extractors;

class BirthCertificateExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'birth_certificate',

            'full_name' => null,

            'birth_date' => null,

            'gender' => null,

            'father_name' => null,

            'mother_name' => null,

            'place_of_birth' => null,

            'raw_text' => $text

        ];

        if (preg_match('/Họ và tên[:\s]*(.+)/iu', $text, $m)) {
            $data['full_name'] = trim($m[1]);
        }

        if (preg_match('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $text, $m)) {
            $data['birth_date'] = $this->formatDate($m[1]);
        }

        if (preg_match('/Nam/u', $text)) {
            $data['gender'] = 'Nam';
        }

        if (preg_match('/Nữ/u', $text)) {
            $data['gender'] = 'Nữ';
        }

        if (preg_match('/Cha[:\s]*(.+)/iu', $text, $m)) {
            $data['father_name'] = trim($m[1]);
        }

        if (preg_match('/Mẹ[:\s]*(.+)/iu', $text, $m)) {
            $data['mother_name'] = trim($m[1]);
        }

        if (preg_match('/Nơi sinh[:\s]*(.+)/iu', $text, $m)) {
            $data['place_of_birth'] = trim($m[1]);
        }

        return $data;
    }
}