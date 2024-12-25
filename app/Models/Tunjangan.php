<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tunjangan extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'tunjangan'; // Nama tabel tanpa plural
    protected $primaryKey = 'tunjangan_id';

    protected $fillable = [
        'tunjangan_id',
        'perusahaan_id',
        'depo_id',
        'kategori_tunjangan_id',
        'tunjangan_kode',
        'tunjangan_nama',
        'tunjangan_keterangan',
        'tunjangan_jenistunjangan',
        'tunjangan_dasarbayar',
        'tunjangan_dibayar_oleh',
        'tunjangan_dibayar_kepada',
        'tunjangan_print_slip',
        'tunjangan_nama_print',
        'tunjangan_is_aktif',
        'tunjangan_who_create',
        'tunjangan_tgl_create',
        'tunjangan_who_update',
        'tunjangan_tgl_update',
        'tunjangan_flag_pph',
        'tunjangan_khusus'
    ];

    protected $nullable = [
        'perusahaan_id',
        'depo_id',
        'kategori_tunjangan_id',
        'tunjangan_kode',
        'tunjangan_nama',
        'tunjangan_keterangan',
        'tunjangan_jenistunjangan',
        'tunjangan_dasarbayar',
        'tunjangan_dibayar_oleh',
        'tunjangan_dibayar_kepada',
        'tunjangan_print_slip',
        'tunjangan_nama_print',
        'tunjangan_is_aktif',
        'tunjangan_who_create',
        'tunjangan_tgl_create',
        'tunjangan_who_update',
        'tunjangan_tgl_update',
        'tunjangan_flag_pph',
        'tunjangan_khusus'
    ];
}
