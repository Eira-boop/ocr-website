<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->string('document_type')->default('cccd');

            $table->string('passport_number')->nullable();

            $table->string('student_id')->nullable();

            $table->string('school_name')->nullable();

            $table->string('class_name')->nullable();

            $table->string('major')->nullable();

            $table->string('gpa')->nullable();

            $table->string('classification')->nullable();

            $table->string('father_name')->nullable();

            $table->string('mother_name')->nullable();

            $table->string('ethnic')->nullable();

            $table->string('place_of_birth')->nullable();

            $table->string('issued_by')->nullable();

            $table->date('expire_date')->nullable();

            $table->longText('raw_text')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn([
                'document_type',
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
                'issued_by',
                'expire_date',
                'raw_text'
            ]);

        });
    }
};