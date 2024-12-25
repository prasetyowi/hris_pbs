<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Karyawan extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing key
    protected $keyType = 'string'; // Key type is string
    protected $table = 'karyawan'; // Nama tabel tanpa plural
    protected $primaryKey = 'karyawan_id';

    // protected $table = 'karyawan';

    // Tambahkan semua kolom yang diizinkan untuk mass assignment
    protected $fillable = [
        'karyawan_id',
        'perusahaan_id',
        'unit_mandiri_id',
        'depo_id',
        'karyawan_nama',
        'karyawan_telepon',
        'karyawan_email',
        'karyawan_tanggal_lahir',
        'karyawan_divisi_id',
        'karyawan_level_id',
        'karyawan_supervisor_id',
        'karyawan_is_perusahaan',
        'karyawan_foto',
        'karyawan_digital_signature',
        'karyawan_is_deleted',
        'karyawan_is_aktif',
        'karyawan_is_dewa',
        'karyawan_quote',
        'karyawan_nip',
        'karyawan_nik',
        'karyawan_tempat_lahir',
        'karyawan_jenis_kelamin',
        'karyawan_agama',
        'karyawan_basic_salary',
        'karyawan_basic_bpjs',
        'karyawan_bank',
        'karyawan_no_rek',
        'karyawan_nama_rek',
        'karyawan_npwp15',
        'karyawan_npwp16',
        'kategori_ptkp_id',
        'tarif_efektif_id',
        'karyawan_beginning_netto',
        'karyawan_pph21paid',
        'kategori_karyawan_kode',
        'karyawan_status_kewajiban',
        'karyawan_jml_tanggungan',
        'karyawan_jml_extra_tanggungan_for_bpjskes',
        'karyawan_tgl_resign',
        'karyawan_is_resign',
        'karyawan_tgl_aktif',
        'karyawan_metodetax',
        'karyawan_jenispajak',
        'karyawan_header_id',
    ];

    // Tambahkan semua kolom yang diizinkan untuk nullable
    protected $nullable = [
        'perusahaan_id',
        'unit_mandiri_id',
        'depo_id',
        'karyawan_nama',
        'karyawan_telepon',
        'karyawan_email',
        'karyawan_tanggal_lahir',
        'karyawan_divisi_id',
        'karyawan_level_id',
        'karyawan_supervisor_id',
        'karyawan_is_perusahaan',
        'karyawan_foto',
        'karyawan_digital_signature',
        'karyawan_is_deleted',
        'karyawan_is_aktif',
        'karyawan_is_dewa',
        'karyawan_quote',
        'karyawan_nip',
        'karyawan_nik',
        'karyawan_tempat_lahir',
        'karyawan_jenis_kelamin',
        'karyawan_agama',
        'karyawan_basic_salary',
        'karyawan_basic_bpjs',
        'karyawan_bank',
        'karyawan_no_rek',
        'karyawan_nama_rek',
        'karyawan_npwp15',
        'karyawan_npwp16',
        'kategori_ptkp_id',
        'tarif_efektif_id',
        'karyawan_beginning_netto',
        'karyawan_pph21paid',
        'kategori_karyawan_kode',
        'karyawan_status_kewajiban',
        'karyawan_jml_tanggungan',
        'karyawan_jml_extra_tanggungan_for_bpjskes',
        'karyawan_tgl_resign',
        'karyawan_is_resign',
        'karyawan_tgl_aktif',
        'karyawan_metodetax',
        'karyawan_jenispajak',
        'karyawan_header_id',
    ];
}
