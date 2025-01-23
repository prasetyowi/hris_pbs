<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisTable extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('kolom_baru')->nullable(); // Menambahkan kolom baru
        });
    }

    public function down()
    {
        Schema::table('kategori_absensi', function (Blueprint $table) {
            $table->dropColumn('absensi'); // Menghapus kolom yang ditambahkan
        });
    }
}
