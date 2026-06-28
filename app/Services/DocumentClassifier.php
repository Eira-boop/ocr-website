<?php

namespace App\Services;

class DocumentClassifier
{
    public function detect(string $text): string
    {
        $text = mb_strtolower($text);

        /*
        |-----------------------------------
        | CCCD
        |-----------------------------------
        */

        if (
            str_contains($text, 'căn cước') ||
            str_contains($text, 'căn cước công dân') ||
            str_contains($text, 'identity card')
        ) {
            return 'cccd';
        }

        /*
        |-----------------------------------
        | Passport
        |-----------------------------------
        */

        if (
            str_contains($text, 'passport') ||
            str_contains($text, 'hộ chiếu')
        ) {
            return 'passport';
        }

        /*
        |-----------------------------------
        | Học bạ
        |-----------------------------------
        */

        if (
            str_contains($text, 'học bạ') ||
            str_contains($text, 'hạnh kiểm')
        ) {
            return 'report_card';
        }

        /*
        |-----------------------------------
        | Bảng điểm
        |-----------------------------------
        */

        if (
            str_contains($text, 'bảng điểm') ||
            str_contains($text, 'transcript')
        ) {
            return 'transcript';
        }

        /*
        |-----------------------------------
        | Bằng tốt nghiệp
        |-----------------------------------
        */

        if (
            str_contains($text, 'bằng tốt nghiệp') ||
            str_contains($text, 'graduation')
        ) {
            return 'degree';
        }

        /*
        |-----------------------------------
        | Giấy khai sinh
        |-----------------------------------
        */

        if (
            str_contains($text, 'giấy khai sinh') ||
            str_contains($text, 'birth certificate')
        ) {
            return 'birth_certificate';
        }

        /*
        |-----------------------------------
        | Hồ sơ xét tuyển
        |-----------------------------------
        */

        if (
            str_contains($text, 'phiếu đăng ký') ||
            str_contains($text, 'xét tuyển')
        ) {
            return 'admission';
        }

        return 'unknown';
    }
}