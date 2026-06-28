<?php

namespace App\Services;

use App\Extractors\CCCDExtractor;
use App\Extractors\PassportExtractor;
use App\Extractors\ReportCardExtractor;
use App\Extractors\TranscriptExtractor;
use App\Extractors\DegreeExtractor;
use App\Extractors\BirthCertificateExtractor;
use App\Extractors\AdmissionExtractor;

class DocumentService
{
    protected $classifier;

    public function __construct()
    {
        $this->classifier = new DocumentClassifier();
    }

    public function process(string $ocrText): array
    {
        $type = $this->classifier->detect($ocrText);

        switch ($type) {

            case 'cccd':
                $extractor = new CCCDExtractor();
                break;

            case 'passport':
                $extractor = new PassportExtractor();
                break;

            case 'report_card':
                $extractor = new ReportCardExtractor();
                break;

            case 'transcript':
                $extractor = new TranscriptExtractor();
                break;

            case 'degree':
                $extractor = new DegreeExtractor();
                break;

            case 'birth_certificate':
                $extractor = new BirthCertificateExtractor();
                break;

            case 'admission':
                $extractor = new AdmissionExtractor();
                break;

            default:

                return [

                    'document_type' => 'unknown',

                    'raw_text' => $ocrText

                ];
        }

        return $extractor->extract($ocrText);
    }
}