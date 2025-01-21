<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanKeluargasTable extends Migration
{
    public function up()
    {
        Schema::table('karyawan_keluarga', function (Blueprint $table) {
            $table->string('karyawan_keluarga_id')->unique()->primary();
            $table->string('karyawan_id')->index()->nullable();
            $table->string('karyawan_keluarga_nama')->nullable();
            $table->date('karyawan_keluarga_tanggal_lahir')->nullable();
            $table->string('karyawan_keluarga_hub_keluarga')->nullable();
            $table->string('karyawan_keluarga_jenis_kelamin')->nullable();
            $table->string('karyawan_keluarga_agama')->nullable();
            $table->string('karyawan_keluarga_pendidikan')->nullable();
            $table->tinyInteger('karyawan_keluarga_is_aktif')->default(1);
            $table->timestamps();

            $table->foreign('karyawan_id')->references('karyawan_id')->on('karyawan')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('karyawan_keluarga');
    }
}
