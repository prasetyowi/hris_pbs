<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriTunjangan extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'kategori_tunjangan'; // Nama tabel tanpa plural
    protected $primaryKey = 'kategori_tunjangan_id';

    protected $fillable = [
        'perusahaan_id',
        'depo_id',
        'kategori_tunjangan_kode',
        'kategori_tunjangan_nama',
        'kategori_tunjangan_keterangan',
        'kategori_tunjangan_is_aktif',
        'kategori_tunjangan_who_create',
        'kategori_tunjangan_tgl_create',
        'kategori_tunjangan_who_update',
        'kategori_tunjangan_tgl_update'
    ];

    protected $nullable = [
        'perusahaan_id',
        'depo_id',
        'kategori_tunjangan_kode',
        'kategori_tunjangan_nama',
        'kategori_tunjangan_keterangan',
        'kategori_tunjangan_is_aktif',
        'kategori_tunjangan_who_create',
        'kategori_tunjangan_tgl_create',
        'kategori_tunjangan_who_update',
        'kategori_tunjangan_tgl_update'
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID for new records
        static::creating(function ($model) {
            if (!$model->kategori_tunjangan_id) {
                $model->kategori_tunjangan_id = (string) Str::uuid();
            }
        });
    }
}
