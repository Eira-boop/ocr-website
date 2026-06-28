<?php

namespace App\Extractors;

class PassportExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'passport',

            'passport_number' => null,

            'full_name' => null,

            'birth_date' => null,

            'gender' => null,

            'nationality' => null,

            'place_of_birth' => null,

            'issue_date' => null,

            'expire_date' => null,

            'issued_by' => null,

            'raw_text' => $text
        ];

        /*
        |--------------------------------------------------
        | Passport Number
        | Ví dụ: C1234567
        |--------------------------------------------------
        */

        $data['passport_number'] = $this->find(
            '/([A-Z][0-9]{7,8})/',
            $text
        );

        /*
        |--------------------------------------------------
        | Họ tên
        |--------------------------------------------------
        */

        $lines = explode("\n", $text);

        foreach ($lines as $i => $line) {

            if (
                stripos($line, 'Surname') !== false ||
                stripos($line, 'Given') !== false ||
                stripos($line, 'Full name') !== false ||
                stripos($line, 'Họ tên') !== false
            ) {

                for ($j = $i + 1; $j <= $i + 2; $j++) {

                    if (!isset($lines[$j])) {
                        continue;
                    }

                    $candidate = trim($lines[$j]);

                    $candidate = preg_replace(
                        '/[^A-ZÀ-Ỹa-zà-ỹ\s]/u',
                        '',
                        $candidate
                    );

                    if (mb_strlen($candidate) > 5) {

                        $data['full_name'] = strtoupper($candidate);

                        break 2;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------
        | Ngày sinh
        |--------------------------------------------------
        */

        $birth = $this->find(
            '/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/',
            $text
        );

        $data['birth_date'] = $this->formatDate($birth);

        /*
        |--------------------------------------------------
        | Giới tính
        |--------------------------------------------------
        */

        if (preg_match('/Male/i', $text) || preg_match('/Nam/u', $text)) {

            $data['gender'] = 'Nam';
        }

        if (preg_match('/Female/i', $text) || preg_match('/Nữ/u', $text)) {

            $data['gender'] = 'Nữ';
        }

        /*
        |--------------------------------------------------
        | Quốc tịch
        |--------------------------------------------------
        */

        if (preg_match('/Vietnam/i', $text)) {

            $data['nationality'] = 'Việt Nam';
        }

        /*
        |--------------------------------------------------
        | Place of birth
        |--------------------------------------------------
        */

        if (
            preg_match(
                '/Place of birth[:\s]*(.+)/i',
                $text,
                $m
            )
        ) {

            $data['place_of_birth'] = trim($m[1]);
        }

        /*
        |--------------------------------------------------
        | Các ngày
        |--------------------------------------------------
        */

        preg_match_all(
            '/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/',
            $text,
            $dates
        );

        if (count($dates[1]) >= 2) {

            $data['issue_date'] =
                $this->formatDate($dates[1][0]);

            $data['expire_date'] =
                $this->formatDate($dates[1][1]);
        }

        /*
        |--------------------------------------------------
        | Cơ quan cấp
        |--------------------------------------------------
        */

        foreach ($lines as $line) {

            if (
                stripos($line, 'Authority') !== false ||
                stripos($line, 'Issued by') !== false
            ) {

                $data['issued_by'] = trim($line);

                break;
            }
        }

        return $data;
    }
}