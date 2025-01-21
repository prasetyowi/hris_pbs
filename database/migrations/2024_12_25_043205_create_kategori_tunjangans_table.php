<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_tunjangan', function (Blueprint $table) {
            $table->string('kategori_tunjangan_id')->primary();
            $table->string('perusahaan_id')->nullable();
            $table->string('depo_id')->nullable();
            $table->string('kategori_tunjangan_kode')->nullable();
            $table->string('kategori_tunjangan_nama')->nullable();
            $table->string('kategori_tunjangan_keterangan')->nullable();
            $table->tinyInteger('kategori_tunjangan_is_aktif')->default(1);
            $table->string('kategori_tunjangan_who_create')->nullable();
            $table->dateTime('kategori_tunjangan_tgl_create')->nullable();
            $table->string('kategori_tunjangan_who_update')->nullable();
            $table->dateTime('kategori_tunjangan_tgl_update')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_tunjangan');
    }
};
