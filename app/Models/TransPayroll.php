<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransPayroll extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'trans_payroll'; // Nama tabel tanpa plural
    protected $primaryKey = 'trans_payroll_id';

    protected $fillable = [
        'trans_payroll_id',
        'perusahaan_id',
        'depo_id',
        'attendance_id',
        'trans_payroll_status',
        'trans_payroll_periode_bln',
        'trans_payroll_periode_thn',
        'trans_payrolle_who_create',
        'trans_payroll_tgl_create',
        'trans_payroll_who_update',
        'trans_payroll_tgl_update',
        'jenis_pajak',
    ];

    protected $nullable = [
        'perusahaan_id',
        'depo_id',
        'attendance_id',
        'trans_payroll_status',
        'trans_payroll_periode_bln',
        'trans_payroll_periode_thn',
        'trans_payrolle_who_create',
        'trans_payroll_tgl_create',
        'trans_payroll_who_update',
        'trans_payroll_tgl_update',
        'jenis_pajak',
    ];
}
