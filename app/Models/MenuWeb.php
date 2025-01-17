<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuWeb extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'menu_web'; // Nama tabel tanpa plural
    protected $primaryKey = 'menu_web_id';

    protected $fillable = [
        'menu_id',
        'menu_kode',
        'menu_link',
        'menu_name',
        'menu_class',
        'menu_parent',
        'menu_order',
        'menu_c',
        'menu_r',
        'menu_u',
        'menu_d',
        'tipe',
        'menu_application',
        'menu_is_detail',
        'menu_color',
        'menu_position',
        'menu_coordinate',
        'menu_order_kode'
    ];

    protected $nullable = [
        'menu_link',
        'menu_name',
        'menu_class',
        'menu_parent',
        'menu_order',
        'menu_c',
        'menu_r',
        'menu_u',
        'menu_d',
        'tipe',
        'menu_application',
        'menu_is_detail',
        'menu_color',
        'menu_position',
        'menu_coordinate',
        'menu_order_kode'
    ];
}
