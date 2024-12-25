<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriAbsensi extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'kategori_absensi'; // Nama tabel tanpa plural
    protected $primaryKey = 'kategori_absensi_id';
    public $timestamps = false;

    protected $fillable = [
        'kategori_absensi_id',
        'perusahaan_id',
        'depo_id',
        'kategori_absensi_kode',
        'kategori_absensi_nama',
        'kategori_absensi_keterangan',
        'kategori_absensi_is_unlimited_saldo',
        'kategori_absensi_saldo_hari',
        'kategori_absensi_maks_request',
        'kategori_absensi_allow_exceed',
        'kategori_absensi_max_exceed',
        'kategori_absensi_is_cutitahunan',
        'kategori_absensi_is_potongcutitahunan',
        'kategori_absensi_is_alpha',
        'kategori_absensi_is_hadir',
        'kategori_absensi_is_aktif',
        'kategori_absensi_who_create',
        'kategori_absensi_tgl_create',
        'kategori_absensi_who_update',
        'kategori_absensi_tgl_update'
    ];

    protected $nullable = [
        'perusahaan_id',
        'depo_id',
        'kategori_absensi_kode',
        'kategori_absensi_nama',
        'kategori_absensi_keterangan',
        'kategori_absensi_is_unlimited_saldo',
        'kategori_absensi_saldo_hari',
        'kategori_absensi_maks_request',
        'kategori_absensi_allow_exceed',
        'kategori_absensi_max_exceed',
        'kategori_absensi_is_cutitahunan',
        'kategori_absensi_is_potongcutitahunan',
        'kategori_absensi_is_alpha',
        'kategori_absensi_is_hadir',
        'kategori_absensi_is_aktif',
        'kategori_absensi_who_create',
        'kategori_absensi_tgl_create',
        'kategori_absensi_who_update',
        'kategori_absensi_tgl_update'
    ];
}
