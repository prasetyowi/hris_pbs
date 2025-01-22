<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTunjangansTable extends Migration
{
    public function up()
    {
        Schema::table('tunjangan', function (Blueprint $table) {
            $table->string('tunjangan_id')->unique()->primary();
            $table->string('perusahaan_id')->nullable();
            $table->string('depo_id')->nullable();
            $table->string('kategori_tunjangan_id')->nullable();
            $table->string('tunjangan_kode')->nullable();
            $table->string('tunjangan_nama')->nullable();
            $table->string('tunjangan_keterangan')->nullable();
            $table->string('tunjangan_jenistunjangan')->nullable();
            $table->string('tunjangan_dasarbayar')->nullable();
            $table->string('tunjangan_dibayar_oleh')->nullable();
            $table->string('tunjangan_print_slip')->nullable();
            $table->string('tunjangan_nama_print')->nullable();
            $table->tinyInteger('tunjangan_is_aktif')->default(1);
            $table->string('tunjangan_who_create')->nullable();
            $table->dateTime('tunjangan_tgl_create')->nullable();
            $table->string('tunjangan_who_update')->nullable();
            $table->dateTime('tunjangan_tgl_update')->nullable();
            $table->integer('tunjangan_flag_pph')->nullable();
            $table->integer('tunjangan_khusus')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tunjangan');
    }
}
