<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanDivisisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan_divisi', function (Blueprint $table) {
            $table->string('karyawan_divisi_id')->primary();
            $table->string('karyawan_divisi_kode')->nullable();
            $table->string('karyawan_divisi_nama')->nullable();
            $table->string('karyawan_divisi_reff_id')->nullable();
            $table->string('karyawan_divisi_level')->nullable();
            $table->string('perusahaan_id')->nullable();
            $table->boolean('karyawan_divisi_is_aktif')->default(1);
            $table->boolean('karyawan_divisi_is_deleted')->default(0);;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan_divisi');
    }
}
