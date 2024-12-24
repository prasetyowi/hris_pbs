<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class CreateKaryawanLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_level', function (Blueprint $table) {
            $table->uuid('karyawan_level_id');
            $table->string('karyawan_divisi_id')->nullable();
            $table->string('karyawan_level_kode')->nullable();
            $table->string('karyawan_level_nama')->nullable();
            $table->integer('karyawan_level_is_aktif')->default(1);
            $table->integer('karyawan_level_is_deleted')->default(0);
            $table->integer('posisi_urutan')->nullable();
            $table->timestamps();

            $table->foreign('karyawan_divisi_id')->references('karyawan_divisi_id')->on('karyawan_divisi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_level');
    }
}
