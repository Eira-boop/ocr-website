<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->string('issued_by')->nullable();
        $table->date('expire_date')->nullable();
        $table->text('features')->nullable();
    });
}

public function down()
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropColumn(['issued_by', 'expire_date', 'features']);
    });
}
};
