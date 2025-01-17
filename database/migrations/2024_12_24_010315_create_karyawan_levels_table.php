<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CreateKaryawanLevelsTable extends Migration
{
    public function up()
    {
        Schema::table('karyawan_level', function (Blueprint $table) {
            $table->string('kolom_baru')->nullable(); // Menambahkan kolom baru
        });
    }

    public function down()
    {
        Schema::table('karyawan_level', function (Blueprint $table) {
            $table->dropColumn('kolom_baru'); // Menghapus kolom yang ditambahkan
        });
    }
}
