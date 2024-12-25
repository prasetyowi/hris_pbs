<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KaryawanLevel extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'karyawan_level'; // Nama tabel tanpa plural
    protected $primaryKey = 'karyawan_level_id';

    protected $fillable = [
        'karyawan_level_id',
        'karyawan_divisi_id',
        'karyawan_level_kode',
        'karyawan_level_nama',
        'karyawan_level_is_aktif',
        'karyawan_level_is_deleted',
        'posisi_urutan'
    ];

    protected $nullable = [
        'karyawan_divisi_id',
        'karyawan_level_kode',
        'karyawan_level_nama',
        'karyawan_level_is_aktif',
        'karyawan_level_is_deleted',
        'posisi_urutan'
    ];
}
