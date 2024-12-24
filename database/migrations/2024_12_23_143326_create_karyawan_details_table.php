<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('karyawan_detail', function (Blueprint $table) {
            $table->uuid('karyawan_detail_id')->primary();
            $table->string('karyawan_id')->nullable();
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
            $table->boolean('karyawan_detail_is_deleted')->default(0);
            $table->boolean('karyawan_detail_is_aktif')->default(1);
            $table->boolean('karyawan_detail_alamat_default')->default(1);
            $table->string('kelas_jalan_id2')->nullable();
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
        Schema::dropIfExists('karyawan_detail');
    }
}
