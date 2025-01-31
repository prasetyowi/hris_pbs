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
        try {
            $validated = $request->validate([
                'trans_payroll_id' => 'required|unique:trans_payroll,trans_payroll_id|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_id' => 'required|string|max:255',
                'trans_payroll_status' => 'required|string|max:255',
                'trans_payroll_periode_bln' => 'required|numeric',
                'trans_payroll_periode_thn' => 'required|numeric',
                'trans_payrolle_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayroll::create($validated);

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
        $karyawan = TransPayroll::find($id);
        if (!$karyawan) {
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
                'trans_payrolle_who_create' => '',
                'trans_payroll_tgl_create' => '',
                'trans_payroll_who_update' => '',
                'trans_payroll_tgl_update' => '',
                'jenis_pajak' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $karyawan->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $karyawan], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
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
                DB::raw('DATENAME(MONTH, a.trans_payroll_periode_bln) as trans_payroll_periode_bln_nama'),
                'a.trans_payroll_periode_bln',
                'a.trans_payroll_periode_thn',
                'a.trans_payroll_who_create',
                'a.trans_payroll_tgl_create',
                'a.trans_payroll_who_update',
                'a.trans_payroll_tgl_update',
                'a.jenis_pajak',
            ]);

        $query->whereNotNull('a.trans_payroll_id');

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

    public function Get_periode_payroll_by_perusahaan()
    {
        $perusahaan = "";
        $depo_id = "";

        try {

            $data = DB::select("SELECT
                                attendance_id,
                                attendance_kode,
                                client_wms_id,
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
                                WHERE CONVERT(NVARCHAR(36),client_wms_id) = '$perusahaan'
                                AND CONVERT(NVARCHAR(36),depo_id) = '$depo_id'
                                AND attendance_is_aktif = '1'
                                AND ISNULL(attendance_is_generate_pph21, '0') = '0'
                                AND attendance_id not in (select ISNULL(attendance_id, NEWID()) from trans_payroll where CONVERT(NVARCHAR(36),client_wms_id) = '$perusahaan' AND CONVERT(NVARCHAR(36),depo_id) = '$depo_id')
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
                                client_wms_id,
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

    public function Proses_simpan_hasil_hitung_payroll_temp($attendance_id, $trans_payroll_id, $pengguna_username)
    {
        try {

            $data = DB::select("exec proses_simpan_hasil_hitung_payroll_temp '$attendance_id','$trans_payroll_id','$pengguna_username'");

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
