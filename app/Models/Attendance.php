<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'attendance'; // Nama tabel tanpa plural
    protected $primaryKey = 'attendance_id';

    protected $fillable = [
        'attendance_id',
        'perusahaan_id',
        'depo_id',
        'attendance_kode',
        'attendance_thn_awal',
        'attendance_bln_awal',
        'attendance_tgl_awal',
        'attendance_thn_akhir',
        'attendance_bln_akhir',
        'attendance_tgl_akhir',
        'attendance_who_create',
        'attendance_tgl_create',
        'attendance_who_update',
        'attendance_tgl_update',
        'attendance_is_aktif',
        'attendance_is_generate_pph21',
        'attendance_periode_bln',
        'attendance_periode_thn',
    ];

    protected $nullable = [
        'perusahaan_id',
        'depo_id',
        'attendance_kode',
        'attendance_thn_awal',
        'attendance_bln_awal',
        'attendance_tgl_awal',
        'attendance_thn_akhir',
        'attendance_bln_akhir',
        'attendance_tgl_akhir',
        'attendance_who_create',
        'attendance_tgl_create',
        'attendance_who_update',
        'attendance_tgl_update',
        'attendance_is_aktif',
        'attendance_is_generate_pph21',
        'attendance_periode_bln',
        'attendance_periode_thn',
    ];
}
