<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkemaTunjanganDetail extends Model
{
    use HasFactory;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'skema_tunjangan_detail'; // Nama tabel tanpa plural
    protected $primaryKey = 'skema_tunjangan_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'skema_tunjangan_detail_id',
        'skema_tunjangan_id',
        'tunjangan_id',
        'skema_tunjangan_jenis',
        'skema_tunjangan_detail_value',
        'skema_tunjangan_detail_flag_autogen',
    ];

    protected $nullable = [
        'tunjangan_id',
        'skema_tunjangan_jenis',
        'skema_tunjangan_detail_value',
        'skema_tunjangan_detail_flag_autogen',
    ];
}
