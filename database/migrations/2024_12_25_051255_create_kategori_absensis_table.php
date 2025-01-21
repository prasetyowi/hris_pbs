<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kategori_absensi', function (Blueprint $table) {
            $table->string('kategori_absensi_id')->primary();
            $table->string('perusahaan_id')->nullable();
            $table->string('depo_id')->nullable();
            $table->string('kategori_absensi_kode')->nullable();
            $table->string('kategori_absensi_nama')->nullable();
            $table->string('kategori_absensi_keterangan')->nullable();
            $table->integer('kategori_absensi_is_unlimited_saldo')->nullable();
            $table->integer('kategori_absensi_saldo_hari')->nullable();
            $table->integer('kategori_absensi_maks_request')->nullable();
            $table->integer('kategori_absensi_allow_exceed')->nullable();
            $table->integer('kategori_absensi_max_exceed')->nullable();
            $table->integer('kategori_absensi_is_cutitahunan')->nullable();
            $table->integer('kategori_absensi_is_potongcutitahunan')->nullable();
            $table->integer('kategori_absensi_is_alpha')->nullable();
            $table->integer('kategori_absensi_is_hadir')->nullable();
            $table->integer('kategori_absensi_is_aktif')->nullable();
            $table->string('kategori_absensi_who_create')->nullable();
            $table->dateTime('kategori_absensi_tgl_create')->nullable();
            $table->string('kategori_absensi_who_update')->nullable();
            $table->dateTime('kategori_absensi_tgl_update')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori_absensi');
    }
};
