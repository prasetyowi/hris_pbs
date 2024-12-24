<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KaryawanDetail extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'karyawan_detail'; // Nama tabel tanpa plural
    protected $primaryKey = 'karyawan_detail_id';
    // protected $table = 'karyawan';

    // Tambahkan semua kolom yang diizinkan untuk mass assignment
    protected $fillable = [
        'karyawan_id',
        'karyawan_detail_judul_alamat',
        'karyawan_detail_alamat',
        'karyawan_detail_propinsi',
        'karyawan_detail_kota',
        'karyawan_detail_kecamatan',
        'karyawan_detail_kelurahan',
        'karyawan_detail_kodepos',
        'karyawan_detail_phone',
        'karyawan_detail_latitude',
        'karyawan_detail_longitude',
        'area_id',
        'kelas_jalan_id',
        'karyawan_detail_is_deleted',
        'karyawan_detail_is_aktif',
        'karyawan_detail_alamat_default',
        'kelas_jalan_id2'
    ];

    // Tambahkan semua kolom yang diizinkan untuk nullable
    protected $nullable = [
        'karyawan_id',
        'karyawan_detail_judul_alamat',
        'karyawan_detail_alamat',
        'karyawan_detail_propinsi',
        'karyawan_detail_kota',
        'karyawan_detail_kecamatan',
        'karyawan_detail_kelurahan',
        'karyawan_detail_kodepos',
        'karyawan_detail_phone',
        'karyawan_detail_latitude',
        'karyawan_detail_longitude',
        'area_id',
        'kelas_jalan_id',
        'karyawan_detail_is_deleted',
        'karyawan_detail_is_aktif',
        'karyawan_detail_alamat_default',
        'kelas_jalan_id2'
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for new records
        static::creating(function ($model) {
            if (!$model->karyawan_detail_id) {
                $model->karyawan_detail_id = (string) Str::uuid();
            }
        });
    }
}
