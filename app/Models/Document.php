<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = [
        'full_name',
        'id_number',
        'birth_date',
        'gender',
        'nationality',
        'address',
        'issue_date',
        'image_path'
    ];
}