<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KaryawanDivisi extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'karyawan_divisi'; // Nama tabel tanpa plural
    protected $primaryKey = 'karyawan_divisi_id';
    // protected $table = 'karyawan';

    // Tambahkan semua kolom yang diizinkan untuk mass assignment
    protected $fillable = [
        'karyawan_divisi_id',
        'karyawan_divisi_kode',
        'karyawan_divisi_nama',
        'karyawan_divisi_reff_id',
        'karyawan_divisi_level',
        'perusahaan_id',
        'karyawan_divisi_is_aktif',
        'karyawan_divisi_is_deleted'
    ];

    // Tambahkan semua kolom yang diizinkan untuk nullable
    protected $nullable = [
        'karyawan_divisi_kode',
        'karyawan_divisi_nama',
        'karyawan_divisi_reff_id',
        'karyawan_divisi_level',
        'perusahaan_id',
        'karyawan_divisi_is_aktif',
        'karyawan_divisi_is_deleted'
    ];
}
