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
                                        trans_payroll_detail2_totalvalue,
                                        isnull(tunjangan.tunjangan_nama, 'BASIC_SALARY') AS tunjangan_nama,
                                        isnull(tunjangan.tunjangan_flag_pph, 1) AS tunjangan_flag_pph
                                FROM trans_payroll_detail2
                                LEFT JOIN tunjangan ON trans_payroll_detail2.tunjangan_id = tunjangan.tunjangan_id
                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                    AND trans_payroll_detail2.tunjangan_nama IN ('BASIC_SALARY')
                                UNION SELECT a.trans_payroll_detail_id,
                                            CASE
                                                WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                ELSE trans_payroll_detail2_totalvalue
                                            END AS trans_payroll_detail2_totalvalue ,
                                            b.tunjangan_nama,
                                            b.tunjangan_flag_pph
                                FROM trans_payroll_detail2 a
                                LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                    AND isnull(b.tunjangan_flag_pph, 0) = 1
                                    AND isnull(b.tunjangan_khusus, 0) = 0) tempbruto
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
                                            trans_payroll_detail2_totalvalue,
                                            isnull(tunjangan.tunjangan_nama, 'BASIC_SALARY') AS tunjangan_nama,
                                            isnull(tunjangan.tunjangan_flag_pph, 1) AS tunjangan_flag_pph
                                    FROM trans_payroll_detail2_temp
                                    LEFT JOIN tunjangan ON trans_payroll_detail2_temp.tunjangan_id = tunjangan.tunjangan_id
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                        AND trans_payroll_detail2_temp.tunjangan_nama IN ('BASIC_SALARY')
                                    UNION SELECT a.trans_payroll_detail_id,
                                                CASE
                                                    WHEN b.tunjangan_jenistunjangan = 'MENGURANGI PENDAPATAN' THEN -trans_payroll_detail2_totalvalue
                                                    ELSE trans_payroll_detail2_totalvalue
                                                END AS trans_payroll_detail2_totalvalue ,
                                                b.tunjangan_nama,
                                                b.tunjangan_flag_pph
                                    FROM trans_payroll_detail2_temp a
                                    LEFT JOIN tunjangan b ON a.tunjangan_id = b.tunjangan_id
                                    WHERE CONVERT(nvarchar(36), trans_payroll_id) = '$id'
                                        AND isnull(b.tunjangan_flag_pph, 0) = 1
                                        AND isnull(b.tunjangan_khusus, 0) = 0) tempbruto
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
}
