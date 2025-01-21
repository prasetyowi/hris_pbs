<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('karyawan_detail', function (Blueprint $table) {
            $table->string('karyawan_detail_id')->primary();
            $table->string('karyawan_id')->index()->nullable();
            $table->string('karyawan_detail_judul_alamat')->nullable();
            $table->string('karyawan_detail_alamat')->nullable();
            $table->string('karyawan_detail_propinsi')->nullable();
            $table->string('karyawan_detail_kota')->nullable();
            $table->string('karyawan_detail_kecamatan')->nullable();
            $table->string('karyawan_detail_kelurahan')->nullable();
            $table->string('karyawan_detail_kodepos')->nullable();
            $table->string('karyawan_detail_phone')->nullable();
            $table->string('karyawan_detail_latitude')->nullable();
            $table->string('karyawan_detail_longitude')->nullable();
            $table->string('area_id')->nullable();
            $table->string('kelas_jalan_id')->nullable();
            $table->tinyInteger('karyawan_detail_is_deleted')->default(0);
            $table->tinyInteger('karyawan_detail_is_aktif')->default(1);
            $table->tinyInteger('karyawan_detail_alamat_default')->default(1);
            $table->string('kelas_jalan_id2')->nullable();
            $table->timestamps();

            $table->foreign('karyawan_id')->references('karyawan_id')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan_detail');
    }
};
