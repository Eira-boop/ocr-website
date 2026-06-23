<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('documents', function (Blueprint $table) {
        $table->id();

        $table->string('full_name');
        $table->string('id_number');
        $table->date('birth_date')->nullable();
        $table->string('gender')->nullable();
        $table->string('nationality')->nullable();
        $table->text('address')->nullable();
        $table->date('issue_date')->nullable();
        $table->string('image_path')->nullable();

        $table->timestamps();
    });
}
};
