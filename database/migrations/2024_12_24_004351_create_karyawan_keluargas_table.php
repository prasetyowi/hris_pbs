<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanKeluargasTable extends Migration
{
    public function up()
    {
        Schema::table('karyawan_keluarga', function (Blueprint $table) {
            $table->string('kolom_baru')->nullable(); // Menambahkan kolom baru
        });
    }

    public function down()
    {
        Schema::table('karyawan_keluarga', function (Blueprint $table) {
            $table->dropColumn('kolom_baru'); // Menghapus kolom yang ditambahkan
        });
    }
}
