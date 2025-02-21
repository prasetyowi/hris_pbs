<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceImportResult extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'attendance_import_result'; // Nama tabel tanpa plural
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'attendance_code',
        'employee_nip',
        'check_in',
        'check_out',
        'status',
        'status',
        'error',
        'who'
    ];

    protected $nullable = [
        'check_in',
        'check_out',
        'error',
    ];
}
