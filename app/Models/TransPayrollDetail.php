<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransPayrollDetail extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'trans_payroll_detail'; // Nama tabel tanpa plural
    protected $primaryKey = 'trans_payroll_detail_id';

    protected $fillable = [
        'trans_payroll_detail_id',
        'trans_payroll_id',
        'karyawan_id',
        'trans_payroll_detail_status',
        'trans_payroll_detail_keterangan',
        'trans_payroll_detail_tgl_transfer',
        'trans_payroll_detail_is_generate_pph21',
        'trans_payroll_detail_tgl_kirim_payslip',
    ];

    protected $nullable = [
        'trans_payroll_id',
        'karyawan_id',
        'trans_payroll_detail_status',
        'trans_payroll_detail_keterangan',
        'trans_payroll_detail_tgl_transfer',
        'trans_payroll_detail_is_generate_pph21',
        'trans_payroll_detail_tgl_kirim_payslip',
    ];
}
