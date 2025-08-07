<?php

namespace App\Http\Controllers;

use App\Models\TransPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class TransPayrollController extends Controller
{

    public function index()
    {
        $data = TransPayroll::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        $trans_payroll_id = $request->input('trans_payroll_id');
        $pengguna_username = $request->input('trans_payroll_who_create');

        try {
            $validated = $request->validate([
                'trans_payroll_id' => 'required|unique:trans_payroll,trans_payroll_id|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_id' => 'required|string|max:255',
                'trans_payroll_status' => 'required|string|max:255',
                'trans_payroll_periode_bln' => 'required|numeric',
                'trans_payroll_periode_thn' => 'required|numeric',
                'karyawan_divisi_id' => 'required|string|max:255',
                'trans_payroll_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayroll::create($validated);

            DB::statement("exec proses_hitung_payroll_temp_ke_asli '$trans_payroll_id','$pengguna_username'");

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayroll::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $trans_payroll_id = $request->input('trans_payroll_id');
        $pengguna_username = $request->input('trans_payroll_who_update');

        $Payroll = TransPayroll::find($id);
        if (!$Payroll) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_id' => 'required|string|max:255',
                'trans_payroll_status' => 'required|string|max:255',
                'trans_payroll_periode_bln' => 'required|numeric',
                'trans_payroll_periode_thn' => 'required|numeric',
                'karyawan_divisi_id' => 'required|string|max:255',
                'trans_payroll_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $Payroll->update($validated);

            DB::statement("exec proses_hitung_payroll_temp_ke_asli '$trans_payroll_id','$pengguna_username'");

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage(), "titip" => $request], 500);
        }
    }

    public function destroy($id)
    {
        $TransPayroll = TransPayroll::find($id);

        if (!$TransPayroll) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $TransPayroll->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_trans_payroll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trans_payroll_status' => 'nullable|string',
            'trans_payroll_periode_bln' => 'nullable|string',
            'trans_payroll_periode_thn' => 'nullable|string',
            'attendance_id' => 'nullable|string',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        $query = DB::table('trans_payroll as a')
            ->leftJoin('attendance as b', 'b.attendance_id', '=', 'a.attendance_id')
            ->select([
                'a.trans_payroll_id',
                'a.perusahaan_id',
                'a.depo_id',
                'a.attendance_id',
                'b.attendance_kode',
                'a.trans_payroll_status',
                DB::raw("DATENAME(MONTH, concat(a.trans_payroll_periode_thn,'-',a.trans_payroll_periode_bln,'-01')) as trans_payroll_periode_bln_nama"),
                'a.trans_payroll_periode_bln',
                'a.trans_payroll_periode_thn',
                'a.trans_payroll_who_create',
                'a.trans_payroll_tgl_create',
                'a.trans_payroll_who_update',
                'a.trans_payroll_tgl_update',
                'a.jenis_pajak',
            ]);

        $query->whereNotNull('a.trans_payroll_id');
        // $query->whereNotIn('a.trans_payroll_status', ["Validation confirmed"]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('b.attendance_kode', 'like', "%{$search}%")
                    ->orWhere('a.trans_payroll_status', 'like', "%{$search}%")
                    ->orWhere('a.trans_payroll_periode_bln', 'like', "%{$search}%")
                    ->orWhereRaw("DATENAME(MONTH, attendance_periode_bln) like ?", ["%{$search}%"])
                    ->orWhere('a.trans_payroll_periode_thn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('trans_payroll_status')) {
            $trans_payroll_status = $request->input('trans_payroll_status');
            $query->where('a.trans_payroll_status', '=', $trans_payroll_status);
        }

        if ($request->filled('trans_payroll_periode_bln')) {
            $trans_payroll_periode_bln = $request->input('trans_payroll_periode_bln');
            $query->where('a.trans_payroll_periode_bln', '=', $trans_payroll_periode_bln);
        }

        if ($request->filled('trans_payroll_periode_thn')) {
            $trans_payroll_periode_thn = $request->input('trans_payroll_periode_thn');
            $query->where('a.trans_payroll_periode_thn', '=', $trans_payroll_periode_thn);
        }

        if ($request->filled('attendance_id')) {
            $attendance_id = $request->input('attendance_id');
            $query->where('a.attendance_id', '=', $attendance_id);
        }

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('a.trans_payroll_id');
        }

        $total = $query->count();
        $perPage = $request->input('size', 10);
        $page = $request->input('page', 1);
        $orders = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'data' => $orders,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'size' => $perPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }

    public function Get_periode_payroll_by_perusahaan(Request $request)
    {

        $perusahaan = $request->query('perusahaan_id');
        $depo_id = $request->query('depo_id');

        try {

            $data = DB::select("SELECT
                                attendance_id,
                                attendance_kode,
                                perusahaan_id,
                                depo_id,
                                attendance_thn_awal,
                                attendance_bln_awal,
                                attendance_tgl_awal,
                                attendance_thn_akhir,
                                attendance_bln_akhir,
                                attendance_tgl_akhir,
                                attendance_who_create,
                                attendance_tgl_create,
                                attendance_who_update,
                                attendance_tgl_update,
                                attendance_is_aktif,
                                attendance_is_generate_pph21
                                FROM attendance
                                WHERE CONVERT(NVARCHAR(36),perusahaan_id) = '$perusahaan'
                                AND CONVERT(NVARCHAR(36),depo_id) = '$depo_id'
                                AND attendance_is_aktif = '1'
                                AND ISNULL(attendance_is_generate_pph21, '0') = '0'
                                AND attendance_id not in (select ISNULL(attendance_id, NEWID()) from trans_payroll where CONVERT(NVARCHAR(36), perusahaan_id) = '$perusahaan' AND CONVERT(NVARCHAR(36), depo_id) = '$depo_id')
                                ORDER BY attendance_thn_awal, attendance_bln_awal ASC");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_periode_payroll_by_perusahaan_edit($id)
    {
        try {

            $data = DB::select("SELECT
                                attendance_id,
                                attendance_kode,
                                perusahaan_id,
                                depo_id,
                                attendance_thn_awal,
                                attendance_bln_awal,
                                attendance_tgl_awal,
                                attendance_thn_akhir,
                                attendance_bln_akhir,
                                attendance_tgl_akhir,
                                attendance_who_create,
                                attendance_tgl_create,
                                attendance_who_update,
                                attendance_tgl_update,
                                attendance_is_aktif,
                                attendance_is_generate_pph21
                                FROM attendance
                                WHERE CONVERT(NVARCHAR(36), attendance_id) = '$id'
                                ORDER BY attendance_thn_awal, attendance_bln_awal ASC");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_total_penghasilan_bruto_karyawan_by_id($trans_payroll_id, $trans_payroll_detail_id)
    {
        try {

            $data = DB::select("SELECT trans_payroll_detail_id,
                                    SUM(trans_payroll_detail2_totalvalue) AS bruto
                                FROM
                                (SELECT trans_payroll_detail_id,
                                        trans_payroll_detail2_totalvalue,
                                        ISNULL(tunjangan.tunjangan_nama, 'BASIC_SALARY') AS tunjangan_nama,
                                        ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
                                FROM trans_payroll_detail2
                                LEFT JOIN tunjangan ON trans_payroll_detail2.tunjangan_id = tunjangan.tunjangan_id
                                WHERE CONVERT(NVARCHAR(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND trans_payroll_detail2.tunjangan_nama IN ('BASIC_SALARY')
                                UNION SELECT a.trans_payroll_detail_id,
                                                CASE
                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                    ELSE trans_payroll_detail2_totalvalue
                                                END AS trans_payroll_detail2_totalvalue,
                                                b.tunjangan_nama,
                                                b.tunjangan_flag_pph
                                FROM trans_payroll_detail2 a
                                LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                WHERE CONVERT(NVARCHAR(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND ISNULL(b.tunjangan_flag_pph, 0) = 1
                                    AND ISNULL(b.tunjangan_khusus, 0) = 0) tempbruto
                                WHERE trans_payroll_detail_id = '$trans_payroll_detail_id'
                                GROUP BY trans_payroll_detail_id");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_total_penghasilan_bruto_karyawan_by_id_temp($trans_payroll_id, $trans_payroll_detail_id)
    {
        try {

            $data = DB::select("SELECT trans_payroll_detail_id,
                                    SUM(trans_payroll_detail2_totalvalue) AS bruto
                                FROM
                                (SELECT trans_payroll_detail_id,
                                        trans_payroll_detail2_totalvalue,
                                        ISNULL(tunjangan.tunjangan_nama, 'BASIC_SALARY') AS tunjangan_nama,
                                        ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
                                FROM trans_payroll_detail2_temp
                                LEFT JOIN tunjangan ON trans_payroll_detail2_temp.tunjangan_id = tunjangan.tunjangan_id
                                WHERE CONVERT(NVARCHAR(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND trans_payroll_detail2_temp.tunjangan_nama IN ('BASIC_SALARY')
                                UNION SELECT a.trans_payroll_detail_id,
                                                CASE
                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                    ELSE trans_payroll_detail2_totalvalue
                                                END AS trans_payroll_detail2_totalvalue,
                                                b.tunjangan_nama,
                                                b.tunjangan_flag_pph
                                FROM trans_payroll_detail2_temp a
                                LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                WHERE CONVERT(NVARCHAR(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND ISNULL(b.tunjangan_flag_pph, 0) = 1
                                    AND ISNULL(b.tunjangan_khusus, 0) = 0) tempbruto
                                WHERE trans_payroll_detail_id = '$trans_payroll_detail_id'
                                GROUP BY trans_payroll_detail_id");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Proses_simpan_hasil_hitung_payroll_temp_by_karyawan($trans_payroll_id, $karyawan_id, $pengguna_username)
    {
        try {

            $data = DB::select("exec proses_simpan_hasil_hitung_payroll_temp_by_karyawan '$trans_payroll_id','$karyawan_id','$pengguna_username'");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Proses_simpan_hasil_hitung_payroll_temp(Request $request)
    {
        $attendance_id = $request->input('attendance_id');
        $trans_payroll_id = $request->input('trans_payroll_id');
        $pengguna_username = $request->input('pengguna_username');
        $karyawan_divisi_id = $request->input('karyawan_divisi_id');

        try {
            DB::statement("exec proses_simpan_hasil_hitung_payroll_temp '$attendance_id','$trans_payroll_id','$karyawan_divisi_id','$pengguna_username'");

            return response()->json(['status' => '200', 'message' => 'proses_simpan_hasil_hitung_payroll_temp successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_summary_trans_payroll_detail_temp(Request $request)
    {
        $search_str = "";
        $sort_by_str = "";
        $perPage = 0;
        $page = 0;
        $offset = 0;

        $trans_payroll_id = $request->input('trans_payroll_id');
        $attendance_id = $request->input('attendance_id');

        $validator = Validator::make($request->all(), [
            'trans_payroll_id' => 'nullable|string',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        try {

            if ($request->filled('search')) {
                $search = $request->input('search');
                $search_str = "AND (divisi like '%$search%' or karyawan_nama like '%$search%' or karyawan_level_nama like '%$search%' or trans_payroll_detail_keterangan like '%$search%' or trans_payroll_detail_status like '%$search%')";
            }

            if ($request->filled('sort_by') && $request->filled('sort_order')) {
                $sort_by_str = "order by " . $request->input('sort_by') . " " . $request->input('sort_order');
            } else {
                $sort_by_str = "order by divisi, karyawan_nama asc";
            }

            $data_asli = DB::select("SELECT dtl.trans_payroll_detail_id,
                                        dtl.trans_payroll_id,
                                        '$attendance_id' as attendance_id,
                                        dtl.karyawan_id,
                                        ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                        ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                        ISNULL(level.karyawan_level_nama, '') AS karyawan_level_nama,
                                        isnull(tempbruto.bruto, 0) AS penghasilanbruto,
                                        isnull(temppph21.pph21, 0) AS pph21,
                                        ISNULL(dtl.trans_payroll_detail_keterangan, '') AS trans_payroll_detail_keterangan,
                                        ISNULL(dtl.trans_payroll_detail_status, '') AS trans_payroll_detail_status,
                                        ISNULL(dtl.trans_payroll_detail_is_generate_pph21, 0) AS trans_payroll_detail_is_generate_pph21
                                    FROM trans_payroll_detail_temp dtl
                                    LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                    LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                    LEFT JOIN karyawan_level LEVEL ON level.karyawan_level_id = karyawan.karyawan_level_id
                                    LEFT JOIN
                                    (SELECT trans_payroll_detail_id,
                                            sum(trans_payroll_detail2_totalvalue) AS bruto
                                    FROM
                                        (SELECT trans_payroll_detail_id,
                                                trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                            AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                        UNION SELECT trans_payroll_detail_id,
                                                -1 * trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                     a.trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                    CASE
                                                        WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                        ELSE trans_payroll_detail2_totalvalue
                                                    END AS trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                            AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                    GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                                    LEFT JOIN
                                    (SELECT trans_payroll_detail_id,
                                            trans_payroll_detail2_totalvalue AS pph21
                                    FROM trans_payroll_detail2_temp
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                                    WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$trans_payroll_id'");

            $total = count($data_asli);

            if ($request->input('size') >= 0) {
                $perPage = $request->input('size', 10);
                $page = $request->input('page', 1);
                $offset = ($page - 1) * $perPage;
            } else {
                $perPage = $total;
                $page = 1;
                $offset = 0;
            }

            $data = DB::select("SELECT
                                    trans_payroll_detail_id,
                                    trans_payroll_id,
                                    attendance_id,
                                    karyawan_id,
                                    karyawan_nama,
                                    divisi,
                                    karyawan_level_nama,
                                    penghasilanbruto,
                                    pph21,
                                    trans_payroll_detail_keterangan,
                                    trans_payroll_detail_status,
                                    trans_payroll_detail_is_generate_pph21
                                FROM (SELECT dtl.trans_payroll_detail_id,
                                        dtl.trans_payroll_id,
                                        '$attendance_id' as attendance_id,
                                        dtl.karyawan_id,
                                        ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                        ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                        ISNULL(level.karyawan_level_nama, '') AS karyawan_level_nama,
                                        FLOOR(isnull(tempbruto.bruto, 0)) AS penghasilanbruto,
                                        FLOOR(isnull(temppph21.pph21, 0)) AS pph21,
                                        ISNULL(dtl.trans_payroll_detail_keterangan, '') AS trans_payroll_detail_keterangan,
                                        ISNULL(dtl.trans_payroll_detail_status, '') AS trans_payroll_detail_status,
                                        ISNULL(dtl.trans_payroll_detail_is_generate_pph21, 0) AS trans_payroll_detail_is_generate_pph21
                                    FROM trans_payroll_detail_temp dtl
                                    LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                    LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                    LEFT JOIN karyawan_level LEVEL ON level.karyawan_level_id = karyawan.karyawan_level_id
                                    LEFT JOIN
                                    (SELECT trans_payroll_detail_id,
                                            sum(trans_payroll_detail2_totalvalue) AS bruto
                                    FROM
                                        (SELECT trans_payroll_detail_id,
                                                trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                            AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                        UNION SELECT trans_payroll_detail_id,
                                                -1 * trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                     a.trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                    CASE
                                                        WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                        ELSE trans_payroll_detail2_totalvalue
                                                    END AS trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                            AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                    GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                                    LEFT JOIN
                                    (SELECT trans_payroll_detail_id,
                                            trans_payroll_detail2_totalvalue AS pph21
                                    FROM trans_payroll_detail2_temp
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                                    WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$trans_payroll_id') a
                                WHERE trans_payroll_id = '$trans_payroll_id'
                                " . $search_str . "
                                " . $sort_by_str . "
                                OFFSET " . $offset . " ROWS
                                FETCH NEXT " . $perPage . " ROWS ONLY");

            return response()->json([
                'data' => $data,
                'meta' => [
                    'total' => $total,
                    'page' => $page,
                    'size' => $perPage,
                    'last_page' => ceil($total / $perPage)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_summary_trans_payroll_detail(Request $request)
    {
        $search_str = "";
        $sort_by_str = "";
        $perPage = 0;
        $page = 0;
        $offset = 0;

        $trans_payroll_id = $request->input('trans_payroll_id');

        $validator = Validator::make($request->all(), [
            'trans_payroll_id' => 'nullable|string',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        try {

            if ($request->filled('search')) {
                $search = $request->input('search');
                $search_str = "AND (divisi like '%$search%' or karyawan_nama like '%$search%' or karyawan_level_nama like '%$search%' or trans_payroll_detail_keterangan like '%$search%' or trans_payroll_detail_status like '%$search%')";
            }

            if ($request->filled('sort_by') && $request->filled('sort_order')) {
                $sort_by_str = "order by " . $request->input('sort_by') . " " . $request->input('sort_order');
            } else {
                $sort_by_str = "order by divisi, karyawan_nama asc";
            }

            $data_asli = DB::select("SELECT dtl.trans_payroll_detail_id,
                                    dtl.trans_payroll_id,
                                    hdr.attendance_id,
                                    dtl.karyawan_id,
                                    ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                    ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                    ISNULL(level.karyawan_level_nama, '') AS karyawan_level_nama,
                                    FLOOR(isnull(tempbruto.bruto, 0)) AS penghasilanbruto,
                                    FLOOR(isnull(temppph21.pph21, 0)) AS pph21,
                                    ISNULL(dtl.trans_payroll_detail_keterangan, '') AS trans_payroll_detail_keterangan,
                                    ISNULL(dtl.trans_payroll_detail_status, '') AS trans_payroll_detail_status,
                                    ISNULL(dtl.trans_payroll_detail_is_generate_pph21, 0) AS trans_payroll_detail_is_generate_pph21
                                FROM trans_payroll_detail dtl
                                LEFT JOIN trans_payroll hdr ON hdr.trans_payroll_id = dtl.trans_payroll_id
                                LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                LEFT JOIN karyawan_level LEVEL ON level.karyawan_level_id = karyawan.karyawan_level_id
                                LEFT JOIN
                                (SELECT trans_payroll_detail_id,
                                        sum(trans_payroll_detail2_totalvalue) AS bruto
                                FROM
                                    (SELECT trans_payroll_detail_id,
                                            trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                    UNION SELECT trans_payroll_detail_id,
                                            -1 * trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                    UNION SELECT a.trans_payroll_detail_id,
                                                     a.trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2 a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                    UNION SELECT a.trans_payroll_detail_id,
                                                CASE
                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                    ELSE trans_payroll_detail2_totalvalue
                                                END AS trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2 a
                                    LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                                LEFT JOIN
                                (SELECT trans_payroll_detail_id,
                                        trans_payroll_detail2_totalvalue AS pph21
                                FROM trans_payroll_detail2
                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                                WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$trans_payroll_id'");

            $total = count($data_asli);

            if ($request->input('size') >= 0) {
                $perPage = $request->input('size', 10);
                $page = $request->input('page', 1);
                $offset = ($page - 1) * $perPage;
            } else {
                $perPage = $total;
                $page = 1;
                $offset = 0;
            }

            $data = DB::select("SELECT
                                    trans_payroll_detail_id,
                                    trans_payroll_id,
                                    attendance_id,
                                    karyawan_id,
                                    karyawan_nama,
                                    divisi,
                                    karyawan_level_nama,
                                    penghasilanbruto,
                                    pph21,
                                    trans_payroll_detail_keterangan,
                                    trans_payroll_detail_status,
                                    trans_payroll_detail_is_generate_pph21
                                FROM (SELECT dtl.trans_payroll_detail_id,
                                    dtl.trans_payroll_id,
                                    hdr.attendance_id,
                                    dtl.karyawan_id,
                                    ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                    ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                    ISNULL(level.karyawan_level_nama, '') AS karyawan_level_nama,
                                    FLOOR(isnull(tempbruto.bruto, 0)) AS penghasilanbruto,
                                    FLOOR(isnull(temppph21.pph21, 0)) AS pph21,
                                    ISNULL(dtl.trans_payroll_detail_keterangan, '') AS trans_payroll_detail_keterangan,
                                    ISNULL(dtl.trans_payroll_detail_status, '') AS trans_payroll_detail_status,
                                    ISNULL(dtl.trans_payroll_detail_is_generate_pph21, 0) AS trans_payroll_detail_is_generate_pph21
                                FROM trans_payroll_detail dtl
                                LEFT JOIN trans_payroll hdr ON hdr.trans_payroll_id = dtl.trans_payroll_id
                                LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                LEFT JOIN karyawan_level LEVEL ON level.karyawan_level_id = karyawan.karyawan_level_id
                                LEFT JOIN
                                (SELECT trans_payroll_detail_id,
                                        sum(trans_payroll_detail2_totalvalue) AS bruto
                                FROM
                                    (SELECT trans_payroll_detail_id,
                                            trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                    UNION SELECT trans_payroll_detail_id,
                                            -1 * trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                    UNION SELECT a.trans_payroll_detail_id,
                                                     a.trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2 a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                    UNION SELECT a.trans_payroll_detail_id,
                                                CASE
                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                    ELSE trans_payroll_detail2_totalvalue
                                                END AS trans_payroll_detail2_totalvalue
                                    FROM trans_payroll_detail2 a
                                    LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                                LEFT JOIN
                                (SELECT trans_payroll_detail_id,
                                        trans_payroll_detail2_totalvalue AS pph21
                                FROM trans_payroll_detail2
                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                    AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                                WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$trans_payroll_id') a
                                WHERE trans_payroll_id = '$trans_payroll_id'
                                " . $search_str . "
                                " . $sort_by_str . "
                                OFFSET " . $offset . " ROWS
                                FETCH NEXT " . $perPage . " ROWS ONLY");

            return response()->json([
                'data' => $data,
                'meta' => [
                    'total' => $total,
                    'page' => $page,
                    'size' => $perPage,
                    'last_page' => ceil($total / $perPage)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function proses_hitung_payroll_asli_ke_temp(Request $request)
    {
        $trans_payroll_id = $request->input('trans_payroll_id');
        $pengguna_username = $request->input('pengguna_username');

        try {
            DB::statement("exec proses_hitung_payroll_asli_ke_temp '$trans_payroll_id','$pengguna_username'");

            return response()->json(['status' => '200', 'message' => 'proses_hitung_payroll_asli_ke_temp successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_list_payroll_paginate(Request $request)
    {
        $filter_divisi = "";
        $filter_bank = "";
        $search_str = "";
        $sort_by_str = "";
        $perPage = 0;
        $page = 0;
        $offset = 0;

        $trans_payroll_id = $request->input('trans_payroll_id');

        $validator = Validator::make($request->all(), [
            'trans_payroll_id' => 'nullable|string',
            'divisi' => 'nullable|string',
            'bank' => 'nullable|string',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        try {

            if ($request->filled('search')) {
                $search = $request->input('search');

                if ($search != "") {
                    $search_str = "AND (karyawan_divisi_nama like '%$search%' or karyawan_nama like '%$search%' or karyawan_level_nama like '%$search%' or karyawan_nip like '%$search%' or karyawan_bank like '%$search%' or jenisbank_nama like '%$search%' or karyawan_no_rek like '%$search%' or karyawan_nama_rek like '%$search%')";
                } else {
                    $search_str = "";
                }
            }

            if ($request->filled('divisi')) {
                $search = $request->input('divisi');
                $filter_divisi = "AND karyawan_divisi_id = '$search'";
            }

            if ($request->filled('bank')) {
                $search = $request->input('bank');
                $filter_bank = "AND karyawan_bank = '$search'";
            }


            if ($request->filled('sort_by') && $request->filled('sort_order')) {
                $sort_by_str = "order by " . $request->input('sort_by') . " " . $request->input('sort_order');
            } else {
                $sort_by_str = "order by karyawan_divisi_nama, karyawan_nama asc";
            }

            $data_asli = DB::select("SELECT
                                        trans_payroll_id,
                                        trans_payroll_detail_id,
                                        trans_payroll_periode_bln,
                                        trans_payroll_periode_thn,
                                        perusahaan_id,
                                        perusahaan_nama,
                                        karyawan_id,
                                        karyawan_nama,
                                        karyawan_nip,
                                        karyawan_divisi_id,
                                        karyawan_divisi_nama,
                                        karyawan_level_id,
                                        karyawan_level_nama,
                                        karyawan_bank,
                                        jenisbank_nama,
                                        karyawan_no_rek,
                                        karyawan_nama_rek,
                                        trans_payroll_detail_tgl_transfer,
                                        trans_payroll_detail_tgl_kirim_payslip,
                                        trans_payroll_detail2_totalvalue
                                    FROM (SELECT
                                        hdr.trans_payroll_id,
                                        dtl.trans_payroll_detail_id,
                                        hdr.trans_payroll_periode_bln,
                                        hdr.trans_payroll_periode_thn,
                                        hdr.perusahaan_id,
                                        perusahaan.perusahaan_nama,
                                        dtl.karyawan_id,
                                        karyawan.karyawan_nama,
                                        karyawan.karyawan_nip,
                                        karyawan.karyawan_divisi_id,
                                        divisi.karyawan_divisi_nama,
                                        karyawan.karyawan_level_id,
                                        level.karyawan_level_nama,
                                        karyawan.karyawan_bank,
                                        bank.jenisbank_nama,
                                        karyawan.karyawan_no_rek,
                                        karyawan.karyawan_nama_rek,
                                        ISNULL(FORMAT(dtl.trans_payroll_detail_tgl_transfer, 'dd-MM-yyyy HH:mm:ss'), '') AS trans_payroll_detail_tgl_transfer,
                                        ISNULL(FORMAT(dtl.trans_payroll_detail_tgl_kirim_payslip, 'dd-MM-yyyy HH:mm:ss'), '') AS trans_payroll_detail_tgl_kirim_payslip,
                                        FLOOR(isnull(hasil.bruto, 0)) AS trans_payroll_detail2_totalvalue
                                    FROM trans_payroll hdr
                                    LEFT JOIN trans_payroll_detail dtl ON dtl.trans_payroll_id = hdr.trans_payroll_id
                                    LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                    LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                    LEFT JOIN karyawan_level level ON level.karyawan_level_id = karyawan.karyawan_level_id
                                    LEFT JOIN perusahaan ON perusahaan.perusahaan_id = hdr.perusahaan_id
                                    LEFT JOIN getjenisbank() bank ON bank.jenisbank_kode = karyawan.karyawan_bank
                                    LEFT JOIN (SELECT trans_payroll_detail_id,
                                                                    sum(trans_payroll_detail2_totalvalue) AS bruto
                                                            FROM
                                                                (SELECT trans_payroll_detail_id,
                                                                    trans_payroll_detail2_totalvalue
                                                                FROM trans_payroll_detail2
                                                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                    AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                                                UNION SELECT trans_payroll_detail_id,
                                                                    -1 * trans_payroll_detail2_totalvalue
                                                                FROM trans_payroll_detail2
                                                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                                                UNION SELECT a.trans_payroll_detail_id,
                                                                            a.trans_payroll_detail2_totalvalue
                                                                FROM trans_payroll_detail2 a
                                                                LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                                                AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                                                UNION SELECT a.trans_payroll_detail_id,
                                                                CASE
                                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                                    ELSE trans_payroll_detail2_totalvalue
                                                                END AS trans_payroll_detail2_totalvalue
                                                                FROM trans_payroll_detail2 a
                                                                LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                    AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                                            GROUP BY trans_payroll_detail_id) hasil ON dtl.trans_payroll_detail_id = hasil.trans_payroll_detail_id
                                    WHERE CONVERT(nvarchar(36), hdr.trans_payroll_id) = '$trans_payroll_id') utama
                                    WHERE trans_payroll_id IS NOT NULL
                                    " . $filter_divisi . "
                                    " . $filter_bank . "");

            $total = count($data_asli);

            if ($request->input('size') >= 0) {
                $perPage = $request->input('size', 10);
                $page = $request->input('page', 1);
                $offset = ($page - 1) * $perPage;
            } else {
                $perPage = $total;
                $page = 1;
                $offset = 0;
            }

            $data = DB::select("SELECT
                                    trans_payroll_id,
                                    trans_payroll_detail_id,
                                    trans_payroll_periode_bln,
                                    trans_payroll_periode_thn,
                                    perusahaan_id,
                                    perusahaan_nama,
                                    karyawan_id,
                                    karyawan_nama,
                                    karyawan_nip,
                                    karyawan_divisi_id,
                                    karyawan_divisi_nama,
                                    karyawan_level_id,
                                    karyawan_level_nama,
                                    karyawan_bank,
                                    jenisbank_nama,
                                    karyawan_no_rek,
                                    karyawan_nama_rek,
                                    trans_payroll_detail_tgl_transfer,
                                    trans_payroll_detail_tgl_kirim_payslip,
                                    trans_payroll_detail2_totalvalue
                                FROM (SELECT
                                    hdr.trans_payroll_id,
                                    dtl.trans_payroll_detail_id,
                                    hdr.trans_payroll_periode_bln,
                                    hdr.trans_payroll_periode_thn,
                                    hdr.perusahaan_id,
                                    perusahaan.perusahaan_nama,
                                    dtl.karyawan_id,
                                    karyawan.karyawan_nama,
                                    karyawan.karyawan_nip,
                                    karyawan.karyawan_divisi_id,
                                    divisi.karyawan_divisi_nama,
                                    karyawan.karyawan_level_id,
                                    level.karyawan_level_nama,
                                    karyawan.karyawan_bank,
                                    bank.jenisbank_nama,
                                    karyawan.karyawan_no_rek,
                                    karyawan.karyawan_nama_rek,
                                    ISNULL(FORMAT(dtl.trans_payroll_detail_tgl_transfer, 'dd-MM-yyyy HH:mm:ss'), '') AS trans_payroll_detail_tgl_transfer,
                                    ISNULL(FORMAT(dtl.trans_payroll_detail_tgl_kirim_payslip, 'dd-MM-yyyy HH:mm:ss'), '') AS trans_payroll_detail_tgl_kirim_payslip,
                                    FLOOR(isnull(hasil.bruto, 0)) AS trans_payroll_detail2_totalvalue
                                FROM trans_payroll hdr
                                LEFT JOIN trans_payroll_detail dtl ON dtl.trans_payroll_id = hdr.trans_payroll_id
                                LEFT JOIN karyawan ON karyawan.karyawan_id = dtl.karyawan_id
                                LEFT JOIN karyawan_divisi divisi ON divisi.karyawan_divisi_id = karyawan.karyawan_divisi_id
                                LEFT JOIN karyawan_level level ON level.karyawan_level_id = karyawan.karyawan_level_id
                                LEFT JOIN perusahaan ON perusahaan.perusahaan_id = hdr.perusahaan_id
                                LEFT JOIN getjenisbank() bank ON bank.jenisbank_kode = karyawan.karyawan_bank
                                LEFT JOIN (SELECT trans_payroll_detail_id,
                                                                sum(trans_payroll_detail2_totalvalue) AS bruto
                                                        FROM
                                                            (SELECT trans_payroll_detail_id,
                                                                trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                                            UNION SELECT trans_payroll_detail_id,
                                                                -1 * trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                            AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                                            UNION SELECT a.trans_payroll_detail_id,
                                                                        a.trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2 a
                                                            LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                            AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                                            AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                                            UNION SELECT a.trans_payroll_detail_id,
                                                            CASE
                                                                WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                                ELSE trans_payroll_detail2_totalvalue
                                                            END AS trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2 a
                                                            LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$trans_payroll_id'
                                                                AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                                        GROUP BY trans_payroll_detail_id) hasil ON dtl.trans_payroll_detail_id = hasil.trans_payroll_detail_id
                                WHERE CONVERT(nvarchar(36), hdr.trans_payroll_id) = '$trans_payroll_id') utama
                                WHERE trans_payroll_id IS NOT NULL
                                " . $filter_divisi . "
                                " . $filter_bank . "
                                " . $search_str . "
                                " . $sort_by_str . "
                                OFFSET " . $offset . " ROWS
                                FETCH NEXT " . $perPage . " ROWS ONLY");

            return response()->json([
                'data' => $data,
                'meta' => [
                    'total' => $total,
                    'page' => $page,
                    'size' => $perPage,
                    'last_page' => ceil($total / $perPage)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_bank_aktif()
    {

        try {

            $data = DB::select("SELECT * FROM getjenisbank() where jenisbank_is_aktif = 1 order by jenisbank_kode asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }


    public function Get_payroll_list()
    {

        try {

            $data = DB::select("SELECT
                                    [trans_payroll_id],
                                    [perusahaan_id],
                                    [depo_id],
                                    [attendance_id],
                                    [trans_payroll_status],
                                    [trans_payroll_periode_bln],
                                    [trans_payroll_periode_thn],
                                    [trans_payroll_who_create],
                                    [trans_payroll_tgl_create],
                                    [trans_payroll_who_update],
                                    [trans_payroll_tgl_update],
                                    [jenis_pajak],
                                    DATENAME(MONTH, DATEFROMPARTS([trans_payroll_periode_thn], [trans_payroll_periode_bln], 1)) AS trans_payroll_periode_bln_name
                                FROM [HRIS_PBS].[dbo].[trans_payroll]
                                WHERE trans_payroll_status = 'Validation confirmed'
                                ORDER BY trans_payroll_periode_thn, trans_payroll_periode_bln");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_pre_payroll_list()
    {
        try {

            $data = DB::select("SELECT * from trans_payroll where trans_payroll_status not in ('Validation confirmed') order by trans_payroll_periode_thn, trans_payroll_periode_bln DESC");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }
}
