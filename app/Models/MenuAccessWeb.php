<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAccessWeb extends Model
{
    use HasFactory;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'menu_access_web'; // Nama tabel tanpa plural
    protected $primaryKey = 'menu_access_id';

    protected $fillable = [
        'menu_access_id',
        'menu_id',
        'pengguna_grup_id',
        'menu_kode',
        'status_c',
        'status_r',
        'status_u',
        'status_d'
    ];

    protected $nullable = [
        'status_c',
        'status_r',
        'status_u',
        'status_d'
    ];
}
