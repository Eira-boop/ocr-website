<?php

namespace App\Services;

class FieldExtractor
{

    public function extract(string $type,string $text)
    {
        return match($type){

            'cccd'=>$this->extractCCCD($text),

            'passport'=>$this->extractPassport($text),

            'birth_certificate'=>$this->extractBirth($text),

            'report_card'=>$this->extractReport($text),

            'transcript'=>$this->extractTranscript($text),

            'degree'=>$this->extractDegree($text),

            default=>[
                'document_type'=>'other',
                'raw_text'=>$text
            ]
        };
    }

    private function extractCCCD($text)
    {

        preg_match('/(?<!\d)(\d{12})(?!\d)/',$text,$id);

        preg_match('/(\d{2}[\/\-\.]\d{2}[\/\-\.]\d{4})/',$text,$dob);

        preg_match('/(Nam|Nữ)/u',$text,$gender);

        return [

            'document_type'=>'cccd',

            'id_number'=>$id[1] ?? '',

            'birth_date'=>$dob[1] ?? '',

            'gender'=>$gender[1] ?? '',

            'raw_text'=>$text

        ];
    }

    private function extractPassport($text)
    {
        return [

            'document_type'=>'passport',

            'raw_text'=>$text

        ];
    }

    private function extractBirth($text)
    {
        return [

            'document_type'=>'birth_certificate',

            'raw_text'=>$text

        ];
    }

    private function extractReport($text)
    {
        return [

            'document_type'=>'report_card',

            'raw_text'=>$text

        ];
    }

    private function extractTranscript($text)
    {
        return [

            'document_type'=>'transcript',

            'raw_text'=>$text

        ];
    }

    private function extractDegree($text)
    {
        return [

            'document_type'=>'degree',

            'raw_text'=>$text

        ];
    }

}