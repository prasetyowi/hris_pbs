<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KaryawanKeluarga extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'karyawan_keluarga'; // Nama tabel tanpa plural
    protected $primaryKey = 'karyawan_keluarga_id';

    protected $fillable = [
        'karyawan_id',
        'karyawan_keluarga_nama',
        'karyawan_keluarga_tanggal_lahir',
        'karyawan_keluarga_hub_keluarga',
        'karyawan_keluarga_jenis_kelamin',
        'karyawan_keluarga_agama',
        'karyawan_keluarga_pendidikan',
        'karyawan_keluarga_is_aktif',
    ];

    protected $nullable = [
        'karyawan_id',
        'karyawan_keluarga_nama',
        'karyawan_keluarga_tanggal_lahir',
        'karyawan_keluarga_hub_keluarga',
        'karyawan_keluarga_jenis_kelamin',
        'karyawan_keluarga_agama',
        'karyawan_keluarga_pendidikan',
        'karyawan_keluarga_is_aktif',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for new records
        static::creating(function ($model) {
            if (!$model->karyawan_keluarga_id) {
                $model->karyawan_keluarga_id = (string) Str::uuid();
            }
        });
    }
}