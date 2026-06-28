<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::table('documents', function (Blueprint $table) {
    $table->string('document_type')->nullable();
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
    $table->longText('raw_text')->nullable();
});
    }
};
