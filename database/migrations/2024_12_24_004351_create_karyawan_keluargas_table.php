<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanKeluargasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_keluarga', function (Blueprint $table) {
            $table->string('karyawan_keluarga_id');
            $table->string('karyawan_id')->nullable();
            $table->string('karyawan_keluarga_nama')->nullable();
            $table->date('karyawan_keluarga_tanggal_lahir')->nullable();
            $table->string('karyawan_keluarga_hub_keluarga')->nullable();
            $table->string('karyawan_keluarga_jenis_kelamin')->nullable();
            $table->string('karyawan_keluarga_agama')->nullable();
            $table->string('karyawan_keluarga_pendidikan')->nullable();
            $table->boolean('karyawan_keluarga_is_aktif')->default(1);
            $table->timestamps();

            $table->foreign('karyawan_id')->references('karyawan_id')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_keluarga');
    }
}
