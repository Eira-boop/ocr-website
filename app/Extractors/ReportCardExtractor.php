<?php

namespace App\Extractors;

class ReportCardExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'report_card',

            'full_name' => null,

            'school_name' => null,

            'class_name' => null,

            'student_id' => null,

            'birth_date' => null,

            'gender' => null,

            'ethnic' => null,

            'place_of_birth' => null,

            'father_name' => null,

            'mother_name' => null,

            'gpa' => null,

            'classification' => null,

            'raw_text' => $text
        ];

        /*
        |-----------------------------------------
        | Họ tên
        |-----------------------------------------
        */

        if (preg_match('/Họ và tên[:\s]*(.+)/iu', $text, $m)) {
            $data['full_name'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Mã học sinh
        |-----------------------------------------
        */

        if (preg_match('/Mã học sinh[:\s]*(.+)/iu', $text, $m)) {
            $data['student_id'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Trường
        |-----------------------------------------
        */

        if (preg_match('/Trường[:\s]*(.+)/iu', $text, $m)) {
            $data['school_name'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Lớp
        |-----------------------------------------
        */

        if (preg_match('/Lớp[:\s]*(.+)/iu', $text, $m)) {
            $data['class_name'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Ngày sinh
        |-----------------------------------------
        */

        if (preg_match('/(\d{2}[\/\-]\d{2}[\/\-]\d{4})/', $text, $m)) {
            $data['birth_date'] = $this->formatDate($m[1]);
        }

        /*
        |-----------------------------------------
        | Giới tính
        |-----------------------------------------
        */

        if (preg_match('/Nam/u', $text)) {
            $data['gender'] = 'Nam';
        }

        if (preg_match('/Nữ/u', $text)) {
            $data['gender'] = 'Nữ';
        }

        /*
        |-----------------------------------------
        | Dân tộc
        |-----------------------------------------
        */

        if (preg_match('/Dân tộc[:\s]*(.+)/iu', $text, $m)) {
            $data['ethnic'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Nơi sinh
        |-----------------------------------------
        */

        if (preg_match('/Nơi sinh[:\s]*(.+)/iu', $text, $m)) {
            $data['place_of_birth'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Cha
        |-----------------------------------------
        */

        if (preg_match('/Họ tên cha[:\s]*(.+)/iu', $text, $m)) {
            $data['father_name'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Mẹ
        |-----------------------------------------
        */

        if (preg_match('/Họ tên mẹ[:\s]*(.+)/iu', $text, $m)) {
            $data['mother_name'] = trim($m[1]);
        }

        /*
        |-----------------------------------------
        | Điểm trung bình
        |-----------------------------------------
        */

        if (preg_match('/([0-9]\.[0-9]{1,2})/', $text, $m)) {
            $data['gpa'] = $m[1];
        }

        /*
        |-----------------------------------------
        | Học lực
        |-----------------------------------------
        */

        if (preg_match('/Xuất sắc/u', $text)) {
            $data['classification'] = 'Xuất sắc';
        } elseif (preg_match('/Giỏi/u', $text)) {
            $data['classification'] = 'Giỏi';
        } elseif (preg_match('/Khá/u', $text)) {
            $data['classification'] = 'Khá';
        } elseif (preg_match('/Trung bình/u', $text)) {
            $data['classification'] = 'Trung bình';
        }

        return $data;
    }
}