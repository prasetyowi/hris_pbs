<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriTunjangansTable extends Migration
{
    public function up()
    {
        Schema::table('kategori_tunjangan', function (Blueprint $table) {
            $table->string('kolom_baru')->nullable(); // Menambahkan kolom baru
        });
    }

    public function down()
    {
        Schema::table('kategori_tunjangan', function (Blueprint $table) {
            $table->dropColumn('kolom_baru'); // Menghapus kolom yang ditambahkan
        });
    }
}
