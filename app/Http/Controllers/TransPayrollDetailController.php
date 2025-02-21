<?php

namespace App\Http\Controllers;

use App\Models\TransPayrollDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class TransPayrollDetailController extends Controller
{
    public function index()
    {
        $data = TransPayrollDetail::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'trans_payroll_detail_id' => 'required|unique:trans_payroll_detail,trans_payroll_detail_id|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'trans_payroll_detail_status' => 'required|string|max:255',
                'trans_payroll_detail_keterangan' => '',
                'trans_payroll_detail_tgl_transfer' => 'required|date',
                'trans_payroll_detail_is_generate_pph21' => '',
                'trans_payroll_detail_tgl_kirim_payslip' => '',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayrollDetail::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayrollDetail::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = TransPayrollDetail::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'trans_payroll_id' => 'required|string|max:255',
                'karyawan_id' => 'required|string|max:255',
                'trans_payroll_detail_status' => 'required|string|max:255',
                'trans_payroll_detail_keterangan' => '',
                'trans_payroll_detail_tgl_transfer' => 'required|date',
                'trans_payroll_detail_is_generate_pph21' => '',
                'trans_payroll_detail_tgl_kirim_payslip' => '',
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
        $TransPayroll = TransPayrollDetail::find($id);

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

    public function Get_trans_payroll_detail_by_id($id)
    {
        try {

            $data = DB::select("SELECT dtl.trans_payroll_detail_id,
                                dtl.trans_payroll_id,
                                dtl.karyawan_id,
                                ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                ISNULL(level.karyawan_level_nama, '') AS LEVEL,
                                isnull(tempbruto.bruto, 0) AS penghasilanbruto,
                                isnull(temppph21.pph21, 0) AS pph21,
                                ISNULL(dtl.trans_payroll_detail_keterangan, '') AS trans_payroll_detail_keterangan,
                                ISNULL(dtl.trans_payroll_detail_status, '') AS trans_payroll_detail_status,
                                ISNULL(dtl.trans_payroll_detail_is_generate_pph21, 0) AS trans_payroll_detail_is_generate_pph21
                            FROM trans_payroll_detail dtl
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
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                                                AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                                            UNION SELECT trans_payroll_detail_id,
                                                                -1 * trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                                            AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                                            UNION SELECT a.trans_payroll_detail_id,
                                                                        a.trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2 a
                                                            LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                                            AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                                            AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                                            UNION SELECT a.trans_payroll_detail_id,
                                                            CASE
                                                                WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                                ELSE trans_payroll_detail2_totalvalue
                                                            END AS trans_payroll_detail2_totalvalue
                                                            FROM trans_payroll_detail2 a
                                                            LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                                                AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                                        GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                            LEFT JOIN
                            (SELECT trans_payroll_detail_id,
                                    trans_payroll_detail2_totalvalue AS pph21
                            FROM trans_payroll_detail2
                            WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                            WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$id'
                            ORDER BY ISNULL(divisi.karyawan_divisi_nama, ''),
                                    ISNULL(karyawan.karyawan_nama, '') ASC");
            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_trans_payroll_detail_temp_by_id($id)
    {
        try {

            $data = DB::select("SELECT dtl.trans_payroll_detail_id,
                                    dtl.trans_payroll_id,
                                    dtl.karyawan_id,
                                    ISNULL(karyawan.karyawan_nama, '') AS karyawan_nama,
                                    ISNULL(divisi.karyawan_divisi_nama, '') AS divisi,
                                    ISNULL(level.karyawan_level_nama, '') AS LEVEL,
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
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                            AND tunjangan_nama IN ('BASIC_SALARY','UANG_SHIFT_MALAM','UANG_LEMBUR_PER_JAM','UANG_LEMBUR_HARI_LIBUR_PER_JAM')
                                        UNION SELECT trans_payroll_detail_id,
                                                -1 * trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                        AND tunjangan_nama IN ('POTONGAN_TERLAMBAT')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                     a.trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                        AND b.tunjangan_jenistunjangan = 'MENAMBAH PENDAPATAN'
                                        AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')
                                        UNION SELECT a.trans_payroll_detail_id,
                                                    CASE
                                                        WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                        ELSE trans_payroll_detail2_totalvalue
                                                    END AS trans_payroll_detail2_totalvalue
                                        FROM trans_payroll_detail2_temp a
                                        LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                        WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                            AND b.tunjangan_dasarbayar IN ('TETAP','TIDAK TETAP','KEHADIRAN')) tempbruto
                                    GROUP BY trans_payroll_detail_id) tempbruto ON dtl.trans_payroll_detail_id = tempbruto.trans_payroll_detail_id
                                LEFT JOIN
                                (SELECT trans_payroll_detail_id,
                                        trans_payroll_detail2_totalvalue AS pph21
                                FROM trans_payroll_detail2_temp
                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                    AND tunjangan_nama = 'PPH21') temppph21 ON dtl.trans_payroll_detail_id = temppph21.trans_payroll_detail_id
                                WHERE CONVERT(nvarchar(36), dtl.trans_payroll_id) = '$id'
                                ORDER BY ISNULL(divisi.karyawan_divisi_nama, ''),
                                        ISNULL(karyawan.karyawan_nama, '') ASC");
            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function Delete_trans_payroll_detail_temp_by_id($id)
    {
        $TransPayroll = DB::table("trans_payroll_detail_temp")->where('trans_payroll_id', $id)->get();

        if (count($TransPayroll) == 0) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            DB::table("trans_payroll_detail_temp")->where('trans_payroll_id', $id)->delete();
            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_trans_payroll_detail_by_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trans_payroll_id' => 'required|string',
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

        $trans_payroll_id = $request->input('trans_payroll_id');
        $search = $request->input('search');
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $sort_by = $request->input('sort_by');
        $sort_order = $request->input('sort_order');

        $query = DB::table('trans_payroll_detail as dtl')
            ->leftJoin('trans_payroll as hdr', 'hdr.trans_payroll_id', '=', 'dtl.trans_payroll_id')
            ->leftJoin('karyawan', 'karyawan.karyawan_id', '=', 'dtl.karyawan_id')
            ->leftJoin('karyawan_divisi as divisi', 'divisi.karyawan_divisi_id', '=', 'karyawan.karyawan_divisi_id')
            ->leftJoin('karyawan_level as level', 'level.karyawan_level_id', '=', 'karyawan.karyawan_level_id')
            ->leftJoin('trans_payroll_detail2 as tempbruto', 'tempbruto.trans_payroll_detail_id', '=', 'dtl.trans_payroll_detail_id')
            ->leftJoin('trans_payroll_detail2 as temppph21', 'temppph21.trans_payroll_detail_id', '=', 'dtl.trans_payroll_detail_id')
            ->where('dtl.trans_payroll_id', '=', $trans_payroll_id);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('divisi', 'like', "%{$search}%")
                    ->orWhere('karyawan_nama', 'like', "%{$search}%")
                    ->orWhere('karyawan_level_nama', 'like', "%{$search}%")
                    ->orWhere('trans_payroll_detail_keterangan', 'like', "%{$search}%")
                    ->orWhere('trans_payroll_detail_status', 'like', "%{$search}%");
            });
        }

        if ($sort_by && $sort_order) {
            $query->orderBy($sort_by, $sort_order);
        } else {
            $query->orderBy('divisi');
            $query->orderBy('karyawan_nama');
        }

        $total = $query->count();
        $orders = $query->offset(($page - 1) * $size)
            ->limit($size)
            ->get();

        return response()->json([
            'data' => $orders,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'size' => $size,
                'last_page' => ceil($total / $size),
            ],
        ]);
    }
}
