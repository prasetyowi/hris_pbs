<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tunjangan_detail', function (Blueprint $table) {
            $table->string('tunjangan_detail_id')->primary();
            $table->string('tunjangan_id')->index()->nullable();
            $table->string('kategori_absensi_id')->nullable();
            $table->timestamps();

            $table->foreign('tunjangan_id')->references('tunjangan_id')->on('tunjangan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('kategori_absensi_id')->references('kategori_absensi_id')->on('kategori_absensi')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tunjangan_detail');
    }
};
