<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LiburNasional extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'libur_nasional'; // Nama tabel tanpa plural
    protected $primaryKey = 'libur_nasional_id';

    protected $fillable = [
        'libur_nasional_id',
        'libur_nasional_tahun',
        'libur_nasional_tanggal',
        'libur_nasional_nama',
        'libur_nasional_is_aktif',
        'libur_nasional_who_create',
        'libur_nasional_tgl_create',
        'libur_nasional_who_update',
        'libur_nasional_tgl_update'
    ];

    protected $nullable = [
        'libur_nasional_tahun',
        'libur_nasional_tanggal',
        'libur_nasional_nama',
        'libur_nasional_is_aktif',
        'libur_nasional_who_create',
        'libur_nasional_tgl_create',
        'libur_nasional_who_update',
        'libur_nasional_tgl_update'
    ];
}
