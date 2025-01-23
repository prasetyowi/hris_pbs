<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'absensi'; // Nama tabel tanpa plural
    protected $primaryKey = 'absensi_id';

    protected $fillable = [
        'absensi_id',
        'attendance_id',
        'karyawan_id',
        'tgl_check_in',
        'tgl_check_out',
        'absensi_who_create',
        'absensi_tgl_create',
        'absensi_who_update',
        'absensi_tgl_update',
        'perusahaan_id'
    ];

    protected $nullable = [
        'attendance_id',
        'karyawan_id',
        'tgl_check_in',
        'tgl_check_out',
        'absensi_who_create',
        'absensi_tgl_create',
        'absensi_who_update',
        'absensi_tgl_update',
        'perusahaan_id'
    ];
}
