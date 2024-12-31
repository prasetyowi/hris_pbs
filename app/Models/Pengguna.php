<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'pengguna'; // Nama tabel tanpa plural
    protected $primaryKey = 'pengguna_id';

    protected $fillable = [
        'pengguna_id',
        'pengguna_kode',
        'pengguna_nama',
        'pengguna_alamat',
        'pengguna_no_telpon',
        'pengguna_email',
        'pengguna_username',
        'pengguna_password',
        'pengguna_tmpt_lahir',
        'pengguna_tgl_lahir',
        'pengguna_grup_id',
        'pengguna_is_aktif',
        'pengguna_pic',
        'pengguna_who_create',
        'pengguna_who_create_id',
        'pengguna_date_create',
        'pengguna_who_update',
        'pengguna_who_update_id',
        'pengguna_date_update',
        'region_id',
        'karyawan_id',
        'pengguna_default_bahasa',
        'pengguna_fingerprint',
        'pengguna_aplikasi'
    ];

    protected $nullable = [
        'pengguna_kode',
        'pengguna_nama',
        'pengguna_alamat',
        'pengguna_no_telpon',
        'pengguna_email',
        'pengguna_username',
        'pengguna_password',
        'pengguna_tmpt_lahir',
        'pengguna_tgl_lahir',
        'pengguna_grup_id',
        'pengguna_is_aktif',
        'pengguna_pic',
        'pengguna_who_create',
        'pengguna_who_create_id',
        'pengguna_date_create',
        'pengguna_who_update',
        'pengguna_who_update_id',
        'pengguna_date_update',
        'region_id',
        'karyawan_id',
        'pengguna_default_bahasa',
        'pengguna_fingerprint',
        'pengguna_aplikasi'
    ];
}
