<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

protected $fillable = [
    'document_type',
    'full_name',
    'id_number',
    'birth_date',
    'gender',
    'nationality',
    'address',
    'passport_number',
    'student_id',
    'school_name',
    'class_name',
    'major',
    'gpa',
    'classification',
    'father_name',
    'mother_name',
    'ethnic',
    'place_of_birth',
    'issue_date',
    'image_path',
    'raw_text',
];
}