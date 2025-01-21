<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkemaTunjangan extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'skema_tunjangan'; // Nama tabel tanpa plural
    protected $primaryKey = 'skema_tunjangan_id';
    public $timestamps = false;

    protected $fillable = [
        'skema_tunjangan_id',
        'client_wms_id',
        'depo_id',
        'karyawan_divisi_id',
        'karyawan_level_id',
        'skema_tunjangan_kode',
        'skema_tunjangan_nama',
        'skema_tunjangan_keterangan',
        'skema_tunjangan_is_aktif',
        'skema_tunjangan_who_create',
        'skema_tunjangan_tgl_create',
        'skema_tunjangan_who_update',
        'skema_tunjangan_tgl_update',
    ];

    protected $nullable = [
        'client_wms_id',
        'depo_id',
        'karyawan_divisi_id',
        'karyawan_level_id',
        'skema_tunjangan_kode',
        'skema_tunjangan_nama',
        'skema_tunjangan_keterangan',
        'skema_tunjangan_is_aktif',
        'skema_tunjangan_who_create',
        'skema_tunjangan_tgl_create',
        'skema_tunjangan_who_update',
        'skema_tunjangan_tgl_update',
    ];
}
