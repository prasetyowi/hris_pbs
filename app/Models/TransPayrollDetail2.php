<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransPayrollDetail2 extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'trans_payroll_detail2'; // Nama tabel tanpa plural
    protected $primaryKey = 'trans_payroll_detail2_id';

    protected $fillable = [
        'trans_payroll_detail2_id',
        'trans_payroll_detail_id',
        'trans_payroll_id',
        'tunjangan_id',
        'tunjangan_nama',
        'trans_payroll_detail2_multiplier',
        'trans_payroll_detail2_value',
        'trans_payroll_detail2_totalvalue',
        'trans_payroll_detail2_urut',
        'trans_payroll_detail2_autogen',
    ];

    protected $nullable = [
        'trans_payroll_detail_id',
        'trans_payroll_id',
        'tunjangan_id',
        'tunjangan_nama',
        'trans_payroll_detail2_multiplier',
        'trans_payroll_detail2_value',
        'trans_payroll_detail2_totalvalue',
        'trans_payroll_detail2_urut',
        'trans_payroll_detail2_autogen',
    ];
}
