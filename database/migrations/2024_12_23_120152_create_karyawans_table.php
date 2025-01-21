<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->string('karyawan_id')->primary();
            $table->string('perusahaan_id')->nullable();
            $table->string('unit_mandiri_id')->nullable();
            $table->string('depo_id')->nullable();
            $table->string('karyawan_nama')->nullable();
            $table->string('karyawan_telepon')->nullable();
            $table->string('karyawan_email')->nullable();
            $table->date('karyawan_tanggal_lahir')->nullable();
            $table->string('karyawan_divisi_id')->nullable();
            $table->string('karyawan_level_id')->nullable();
            $table->string('karyawan_supervisor_id')->nullable();
            $table->tinyInteger('karyawan_is_perusahaan')->nullable();
            $table->string('karyawan_foto')->nullable();
            $table->text('karyawan_digital_signature')->nullable();
            $table->tinyInteger('karyawan_is_aktif')->default(1);
            $table->string('karyawan_is_dewa')->default(0);
            $table->string('karyawan_is_deleted')->default(0);
            $table->text('karyawan_quote')->nullable();
            $table->string('karyawan_nip')->nullable();
            $table->string('karyawan_nik')->nullable();
            $table->string('karyawan_tempat_lahir')->nullable();
            $table->string('karyawan_jenis_kelamin')->nullable();
            $table->string('karyawan_agama')->nullable();
            $table->decimal('karyawan_basic_salary', 30, 2)->nullable();
            $table->decimal('karyawan_basic_bpjs', 30, 2)->nullable();
            $table->string('karyawan_bank')->nullable();
            $table->string('karyawan_no_rek')->nullable();
            $table->string('karyawan_nama_rek')->nullable();
            $table->string('karyawan_npwp15')->nullable();
            $table->string('karyawan_npwp16')->nullable();
            $table->string('kategori_ptkp_id')->nullable();
            $table->string('tarif_efektif_id')->nullable();
            $table->decimal('karyawan_beginning_netto', 30, 2)->nullable();
            $table->decimal('karyawan_pph21paid', 30, 2)->nullable();
            $table->string('kategori_karyawan_kode')->nullable();
            $table->string('karyawan_status_kewajiban')->nullable();
            $table->integer('karyawan_jml_tanggungan')->nullable();
            $table->integer('karyawan_jml_extra_tanggungan_for_bpjskes')->nullable();
            $table->date('karyawan_tgl_resign')->nullable();
            $table->tinyInteger('karyawan_is_resign')->default(0);
            $table->date('karyawan_tgl_aktif')->nullable();
            $table->string('karyawan_metodetax')->nullable();
            $table->string('karyawan_jenispajak')->nullable();
            $table->string('karyawan_header_id')->nullable();
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
        Schema::dropIfExists('karyawan');
    }
};
