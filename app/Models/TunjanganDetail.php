<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TunjanganDetail extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'tunjangan_detail'; // Nama tabel tanpa plural
    protected $primaryKey = 'tunjangan_detail_id';

    protected $fillable = [
        'tunjangan_detail_id',
        'tunjangan_id',
        'kategori_absensi_id'
    ];

    protected $nullable = [
        'tunjangan_id',
        'kategori_absensi_id'
    ];
}
