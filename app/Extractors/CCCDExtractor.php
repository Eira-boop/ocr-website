<?php

namespace App\Extractors;

class CCCDExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $data = [

            'document_type' => 'cccd',

            'full_name' => null,

            'id_number' => null,

            'birth_date' => null,

            'gender' => null,

            'nationality' => 'Việt Nam',

            'address' => null,

            'issue_date' => null,

            'issued_by' => null,

            'expire_date' => null,

            'features' => null,

            'raw_text' => $text

        ];

        /*
        |-----------------------------------------
        | CCCD
        |-----------------------------------------
        */

        $data['id_number'] = $this->find(
            '/(?<!\d)(\d{12})(?!\d)/',
            $text
        );

        /*
        |-----------------------------------------
        | Ngày sinh
        |-----------------------------------------
        */

        $birth = $this->find(
            '/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4})/',
            $text
        );

        $data['birth_date'] = $this->formatDate($birth);

        /*
        |-----------------------------------------
        | Giới tính
        |-----------------------------------------
        */

if (preg_match('/Giới\s*tính.*?(Nam|Nữ)/iu', $text, $match)) {
    $data['gender'] = ucfirst(mb_strtolower($match[1]));
} elseif (preg_match('/Sex.*?(Male|Female)/iu', $text, $match)) {
    $data['gender'] = strtolower($match[1]) == 'male' ? 'Nam' : 'Nữ';
}

        /*
        |-----------------------------------------
        | Họ tên
        |-----------------------------------------
        */

        $lines = explode("\n", $text);

        foreach ($lines as $i => $line) {

            if (
                stripos($line, 'Họ') !== false ||
                stripos($line, 'Full') !== false
            ) {

                for ($j = $i + 1; $j <= $i + 3; $j++) {

                    if (!isset($lines[$j])) {
                        continue;
                    }

 $candidate = trim($lines[$j]);

// Chỉ giữ chữ và khoảng trắng
$candidate = preg_replace('/[^A-ZÀ-Ỹa-zà-ỹ\s]/u', '', $candidate);

// Gộp nhiều khoảng trắng
$candidate = preg_replace('/\s+/', ' ', $candidate);

// Xóa khoảng trắng đầu cuối
$candidate = trim($candidate);

// Nếu phía sau xuất hiện các từ nhiễu như ES RE ER...
$candidate = preg_replace('/\s+(ES|RE|ER|EE|SE)(\s+.*)?$/i', '', $candidate);

if (mb_strlen($candidate) > 5) {
    $data['full_name'] = strtoupper($candidate);
    break 2;
}
                }
            }
        }

        return $data;
        /*
|-----------------------------------------
| Ngày cấp
|-----------------------------------------
*/

if (preg_match('/Ngày.*?(\d{2}\/\d{2}\/\d{4})/su', $text, $m)) {
    $data['issue_date'] = $this->formatDate($m[1]);
}

/*
|-----------------------------------------
| Ngày hết hạn
|-----------------------------------------
*/

if (preg_match('/Có giá trị đến.*?(\d{2}\/\d{2}\/\d{4})/su', $text, $m)) {
    $data['expire_date'] = $this->formatDate($m[1]);
}

/*
|-----------------------------------------
| Đặc điểm nhận dạng
|-----------------------------------------
*/

if (preg_match('/Đặc điểm nhận dạng[:\s]*(.+)/iu', $text, $m)) {
    $data['features'] = trim($m[1]);
}
    }
}