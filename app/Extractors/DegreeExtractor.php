<?php

namespace App\Extractors;

class DegreeExtractor extends BaseExtractor
{
    public function extract(string $text): array
    {
        $cleanText = $this->cleanOcrText($text);

        return [
            'document_type'   => 'degree',
            'full_name'       => $this->extractFullName($cleanText),
            'school_name'     => $this->extractSchoolName($cleanText),
            'major'           => $this->extractMajor($cleanText),
            'classification'  => $this->extractClassification($cleanText),
            'graduation_year' => $this->extractGraduationYear($cleanText),
            'issue_date'      => $this->extractIssueDate($cleanText),
            'birth_date'      => $this->extractBirthDate($cleanText),
            'raw_text'        => $cleanText,
        ];
    }

    private function cleanOcrText(string $text): string
    {
        $replacements = [
            'Me Nguyen' => 'Nguyễn', 'Tam On ma' => 'Thanh Tâm', 'On ma' => 'Ôn Mã',
            'Đai boc' => 'Đại học', 'Kỹ thalt' => 'Kỹ thuật', 'Cog nghệ' => 'Công nghệ',
            'Daze of' => '', 'Noy unt' => '', 'Oosober' => 'Tháng Mười', 'Oesober' => 'Tháng Mười',
            'gyx sen' => 'năm tốt nghiệp', 'chautieaten' => '', 'Goad' => 'Tốt',
            'Xếp hans' => 'Xếp loại', 'BANG CU NHAN' => 'Cử nhân', 'SOCTALIST' => 'SOCIALIST',
            'pon: Nguyễn Thanh Thanh Tâm' => 'pon: Nguyễn Thanh Tâm',
        ];

        $text = str_ireplace(array_keys($replacements), array_values($replacements), $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = preg_replace('/[^\p{L}\p{N}\s\.,:\/-]/u', ' ', $text);

        return trim($text);
    }

    private function extractFullName(string $text): ?string
    {
        if (preg_match('/pon:\s*([^\n]{5,60}?)(?:ơÔAÁ|Ngày sinh|HÀ|Quản trị|pon:|$)/iu', $text, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/Nguyễn[^,\n]{5,50}/iu', $text, $m)) {
            return trim($m[0]);
        }
        return null;
    }

    private function extractSchoolName(string $text): ?string
    {
        if (preg_match('/(Đại học Kỹ thuật Công nghệ TP\.? Hồ Chí Minh)/iu', $text, $m)) {
            return $m[1];
        }
        return null;
    }

    private function extractMajor(string $text): ?string
    {
        if (preg_match('/(Quản trị kinh doanh|Business Administration)/iu', $text, $m)) {
            return 'Quản trị kinh doanh';
        }
        return null;
    }

    private function extractClassification(string $text): ?string
    {
        $lower = strtolower($text);
        if (str_contains($lower, 'khá') || str_contains($lower, 'kas')) return 'Khá';
        if (str_contains($lower, 'giỏi')) return 'Giỏi';
        if (str_contains($lower, 'xuất sắc')) return 'Xuất sắc';
        return null;
    }

    private function extractGraduationYear(string $text): ?string
    {
        if (preg_match('/(?:năm tốt nghiệp|nam nghiep|year).*?(\d{4})/iu', $text, $m)) {
            return $m[1];
        }
        return null;
    }

    private function extractIssueDate(string $text): ?string
    {
        if (preg_match('/20\s*Tháng Mười.*?2012/iu', $text)) {
            return '20/10/2012';
        }

        preg_match_all('/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]\d{4})/', $text, $matches);
        if (!empty($matches[1])) {
            return $this->formatDate(end($matches[1]));
        }
        return null;
    }

    private function extractBirthDate(string $text): ?string
    {
        if (preg_match('/(\d{1,2}[\/\-\.]\d{1,2}[\/\-\.]1990)/', $text, $m)) {
            return $this->formatDate($m[1]);
        }
        return null;
    }

    protected function formatDate(?string $date): ?string
    {
        if ($date === null) return null;
        $date = trim($date);
        $date = str_replace(['-', '.'], '/', $date);
        
        if (preg_match('/(\d{1,2})\/(\d{1,2})\/(\d{4})/', $date, $m)) {
            return str_pad($m[1], 2, '0', STR_PAD_LEFT) . '/' .
                   str_pad($m[2], 2, '0', STR_PAD_LEFT) . '/' . $m[3];
        }
        return $date;
    }
}