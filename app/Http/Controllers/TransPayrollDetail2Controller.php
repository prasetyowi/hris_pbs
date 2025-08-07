<?php

namespace App\Http\Controllers;

use App\Models\TransPayrollDetail2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class TransPayrollDetail2Controller extends Controller
{
    public function index()
    {
        $data = TransPayrollDetail2::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'trans_payroll_detail2_id' => 'required|unique:trans_payroll_detail2,trans_payroll_detail2_id|string|max:255',
                'trans_payroll_detail_id' => 'required|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'tunjangan_id' => '',
                'tunjangan_nama' => '',
                'trans_payroll_detail2_multiplier' => 'required|',
                'trans_payroll_detail2_value' => 'required|numeric',
                'trans_payroll_detail2_totalvalue' => 'required|numeric',
                'trans_payroll_detail2_urut' => 'required|numeric',
                'trans_payroll_detail2_autogen' => 'required',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = TransPayrollDetail2::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = TransPayrollDetail2::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = TransPayrollDetail2::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'trans_payroll_detail_id' => 'required|string|max:255',
                'trans_payroll_id' => 'required|string|max:255',
                'tunjangan_id' => '',
                'tunjangan_nama' => '',
                'trans_payroll_detail2_multiplier' => 'required|',
                'trans_payroll_detail2_value' => 'required|numeric',
                'trans_payroll_detail2_totalvalue' => 'required|numeric',
                'trans_payroll_detail2_urut' => 'required|numeric',
                'trans_payroll_detail2_autogen' => 'required',
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
        $TransPayroll = TransPayrollDetail2::find($id);

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

    public function Get_trans_payroll_detail2_by_dtl_id($id)
    {
        try {

            $data = DB::select("select
										dtl1.karyawan_id,
										dtl.trans_payroll_detail2_id,
										dtl.trans_payroll_detail_id,
										dtl.trans_payroll_id,
										ISNULL(CONVERT(nvarchar(36),dtl.tunjangan_id),'') AS tunjangan_id,
										ISNULL(tunjangan.tunjangan_kode,'') AS tunjangan_kode,
										ISNULL(dtl.tunjangan_nama,'') AS tunjangan_nama,
										ISNULL(dtl.trans_payroll_detail2_multiplier,0) AS trans_payroll_detail2_multiplier,
										ISNULL(dtl.trans_payroll_detail2_value,0) AS trans_payroll_detail2_value,
										ISNULL(dtl.trans_payroll_detail2_totalvalue,0) AS trans_payroll_detail2_totalvalue,
										ISNULL(dtl.trans_payroll_detail2_urut,0) AS trans_payroll_detail2_urut,
										ISNULL(dtl.trans_payroll_detail2_autogen,0) AS trans_payroll_detail2_autogen,
										ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
									from trans_payroll_detail2 dtl
									left join trans_payroll_detail dtl1
									on dtl1.trans_payroll_detail_id = dtl.trans_payroll_detail_id
									left join tunjangan
									on tunjangan.tunjangan_id = dtl.tunjangan_id
									WHERE CONVERT(nvarchar(36), dtl.trans_payroll_detail_id) = '$id'
									AND ISNULL(tunjangan.tunjangan_khusus, '0') = '0'
									order by dtl1.karyawan_id,ISNULL(dtl.trans_payroll_detail2_urut,0) asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found', 'data' => []], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_trans_payroll_detail2_temp_by_dtl_id($id)
    {
        try {

            $data = DB::select("select
										dtl1.karyawan_id,
										dtl.trans_payroll_detail2_id,
										dtl.trans_payroll_detail_id,
										dtl.trans_payroll_id,
										ISNULL(CONVERT(nvarchar(36),dtl.tunjangan_id),'') AS tunjangan_id,
										ISNULL(tunjangan.tunjangan_kode,'') AS tunjangan_kode,
										ISNULL(dtl.tunjangan_nama,'') AS tunjangan_nama,
										ISNULL(dtl.trans_payroll_detail2_multiplier,0) AS trans_payroll_detail2_multiplier,
										ISNULL(dtl.trans_payroll_detail2_value,0) AS trans_payroll_detail2_value,
										ISNULL(dtl.trans_payroll_detail2_totalvalue,0) AS trans_payroll_detail2_totalvalue,
										ISNULL(dtl.trans_payroll_detail2_urut,0) AS trans_payroll_detail2_urut,
										ISNULL(dtl.trans_payroll_detail2_autogen,0) AS trans_payroll_detail2_autogen,
										ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
									from trans_payroll_detail2_temp dtl
									left join trans_payroll_detail_temp dtl1
									on dtl1.trans_payroll_detail_id = dtl.trans_payroll_detail_id
									left join tunjangan
									on tunjangan.tunjangan_id = dtl.tunjangan_id
									WHERE CONVERT(nvarchar(36), dtl.trans_payroll_detail_id) = '$id'
									AND ISNULL(tunjangan.tunjangan_khusus, '0') = '0'
									order by dtl1.karyawan_id,ISNULL(dtl.trans_payroll_detail2_urut,0) asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found', 'data' => []], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Insert_trans_payroll_detail2_temp(Request $request)
    {
        $transPayrollId = $request->trans_payroll_id;
        $transPayrollDetailId = $request->trans_payroll_detail_id;
        $arrTransPayrollDetail2 = $request->dataTabelPayrollDetail;

        DB::beginTransaction();
        try {
            if ($arrTransPayrollDetail2) {
                DB::delete('delete trans_payroll_detail2_temp where trans_payroll_detail_id = ?', [$transPayrollDetailId]);
                $validator = Validator::make($arrTransPayrollDetail2, [
                    '*.trans_payroll_detail2_id' => 'required|unique:trans_payroll_detail2_temp,trans_payroll_detail2_id|string|max:255',
                    '*.trans_payroll_detail_id' => 'required|string|max:255',
                    '*.trans_payroll_id' => 'required|string|max:255',
                    '*.trans_payroll_detail2_multiplier' => 'required|numeric',
                    '*.trans_payroll_detail2_value' => 'required|numeric',
                    '*.trans_payroll_detail2_totalvalue' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors(), "titip" => $arrTransPayrollDetail2], 422);
                }
                foreach ($arrTransPayrollDetail2 as $key => $value) {
                    $no_urut = (int) $key + 1;
                    $autogen = (int) $value['trans_payroll_detail2_autogen'] == 1 ? "1" : "0";
                    DB::insert('INSERT INTO trans_payroll_detail2_temp (trans_payroll_detail2_id, trans_payroll_detail_id, trans_payroll_id, tunjangan_id, tunjangan_nama,trans_payroll_detail2_multiplier,trans_payroll_detail2_value,trans_payroll_detail2_totalvalue,trans_payroll_detail2_urut,trans_payroll_detail2_autogen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                        $value['trans_payroll_detail2_id'],
                        $transPayrollDetailId,
                        $transPayrollId,
                        $value['tunjangan_id'],
                        $value['tunjangan_nama'],
                        $value['trans_payroll_detail2_multiplier'],
                        $value['trans_payroll_detail2_value'],
                        $value['trans_payroll_detail2_totalvalue'],
                        $no_urut,
                        $autogen
                    ]);
                }
            }
            DB::commit();
            return response()->json(['status' => '200', 'message' => 'Data created successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function Update_transPayroll_detail2_temp_by_transPayroll_detail_id(Request $request)
    {
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'trans_payroll_detail2_id' => 'required|unique:trans_payroll_detail2,trans_payroll_detail2_id|string|max:255',
                'trans_payroll_detail2_multiplier' => 'required|numeric',
                'trans_payroll_detail2_value' => 'required|numeric',
                'trans_payroll_detail2_totalvalue' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            DB::update('UPDATE trans_payroll_detail2_temp SET trans_payroll_detail2_multiplier = ?, trans_payroll_detail2_value = ?, trans_payroll_detail2_totalvalue = ? WHERE trans_payroll_detail2_id = ?', [$request->input('trans_payroll_detail2_multiplier'), $request->input('trans_payroll_detail2_value'), $request->input('trans_payroll_detail2_totalvalue'), $request->input('trans_payroll_detail2_id')]);

            DB::commit();
            return response()->json(['status' => '200', 'message' => 'Data created successfully'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }


    public function Delete_trans_payroll_detail2_temp_by_dtl_id($id)
    {
        $TransPayroll = DB::table("trans_payroll_detail2_temp")->where('trans_payroll_detail_id', $id)->get();

        if (count($TransPayroll) == 0) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            DB::table("trans_payroll_detail2_temp")->where('trans_payroll_detail_id', $id)->delete();
            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Delete_trans_payroll_detail2_temp_by_dtl2_id($id)
    {
        $TransPayroll = DB::table("trans_payroll_detail2_temp")->where('trans_payroll_detail2_id', $id)->get();

        if (count($TransPayroll) == 0) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            DB::table("trans_payroll_detail2_temp")->where('trans_payroll_detail_id', $id)->delete();
            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_paginate_trans_payroll_detail2_temp_by_trans_payroll_detail_id(Request $request)
    {
        try {

            $trans_payroll_detail_id = $request->input('trans_payroll_detail_id');

            $data = DB::select("select
										dtl1.karyawan_id,
										dtl.trans_payroll_detail2_id,
										dtl.trans_payroll_detail_id,
										dtl.trans_payroll_id,
										ISNULL(CONVERT(nvarchar(36),dtl.tunjangan_id),'') AS tunjangan_id,
										ISNULL(tunjangan.tunjangan_kode,'') AS tunjangan_kode,
										ISNULL(dtl.tunjangan_nama,'') AS tunjangan_nama,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_multiplier,0)) AS trans_payroll_detail2_multiplier,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_value,0)) AS trans_payroll_detail2_value,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_totalvalue,0)) AS trans_payroll_detail2_totalvalue,
										ISNULL(dtl.trans_payroll_detail2_urut,0) AS trans_payroll_detail2_urut,
										ISNULL(dtl.trans_payroll_detail2_autogen,0) AS trans_payroll_detail2_autogen,
										ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
									from trans_payroll_detail2_temp dtl
									left join trans_payroll_detail_temp dtl1
									on dtl1.trans_payroll_detail_id = dtl.trans_payroll_detail_id
									left join tunjangan
									on tunjangan.tunjangan_id = dtl.tunjangan_id
									WHERE CONVERT(nvarchar(36), dtl.trans_payroll_detail_id) = '$trans_payroll_detail_id'
									AND ISNULL(tunjangan.tunjangan_khusus, '0') = '0'
									order by dtl1.karyawan_id,ISNULL(dtl.trans_payroll_detail2_urut,0) asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found', 'data' => []], 204);
            } else {
                $total = count($data);
                $perPage = count($data);
                $page = 1;

                return response()->json([
                    'status' => '200',
                    'message' => 'Data retrieved successfully',
                    'data' => $data,
                    'meta' => [
                        'total' => $total,
                        'page' => $page,
                        'size' => $perPage,
                        'last_page' => $page
                    ]
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_trans_payroll_detail2_temp_by_trans_payroll_detail_id(Request $request)
    {
        try {

            $trans_payroll_detail_id = $request->input('trans_payroll_detail_id');

            $data = DB::select("select
										dtl1.karyawan_id,
										dtl.trans_payroll_detail2_id,
										dtl.trans_payroll_detail_id,
										dtl.trans_payroll_id,
										ISNULL(CONVERT(nvarchar(36),dtl.tunjangan_id),'') AS tunjangan_id,
										ISNULL(tunjangan.tunjangan_kode,'') AS tunjangan_kode,
										ISNULL(dtl.tunjangan_nama,'') AS tunjangan_nama,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_multiplier,0)) AS trans_payroll_detail2_multiplier,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_value,0)) AS trans_payroll_detail2_value,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_totalvalue,0)) AS trans_payroll_detail2_totalvalue,
										ISNULL(dtl.trans_payroll_detail2_urut,0) AS trans_payroll_detail2_urut,
										ISNULL(dtl.trans_payroll_detail2_autogen,0) AS trans_payroll_detail2_autogen,
										ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
									from trans_payroll_detail2_temp dtl
									left join trans_payroll_detail_temp dtl1
									on dtl1.trans_payroll_detail_id = dtl.trans_payroll_detail_id
									left join tunjangan
									on tunjangan.tunjangan_id = dtl.tunjangan_id
									WHERE CONVERT(nvarchar(36), dtl.trans_payroll_detail_id) = '$trans_payroll_detail_id'
									AND ISNULL(tunjangan.tunjangan_khusus, '0') = '0'
									order by dtl1.karyawan_id,ISNULL(dtl.trans_payroll_detail2_urut,0) asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found', 'data' => []], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_trans_payroll_detail2_by_trans_payroll_detail_id(Request $request)
    {
        try {

            $trans_payroll_detail_id = $request->input('trans_payroll_detail_id');

            $data = DB::select("select
										dtl1.karyawan_id,
										dtl.trans_payroll_detail2_id,
										dtl.trans_payroll_detail_id,
										dtl.trans_payroll_id,
										ISNULL(CONVERT(nvarchar(36),dtl.tunjangan_id),'') AS tunjangan_id,
										ISNULL(tunjangan.tunjangan_kode,'') AS tunjangan_kode,
										ISNULL(dtl.tunjangan_nama,'') AS tunjangan_nama,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_multiplier,0)) AS trans_payroll_detail2_multiplier,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_value,0)) AS trans_payroll_detail2_value,
										FLOOR(ISNULL(dtl.trans_payroll_detail2_totalvalue,0)) AS trans_payroll_detail2_totalvalue,
										ISNULL(dtl.trans_payroll_detail2_urut,0) AS trans_payroll_detail2_urut,
										ISNULL(dtl.trans_payroll_detail2_autogen,0) AS trans_payroll_detail2_autogen,
										ISNULL(tunjangan.tunjangan_flag_pph, 0) AS tunjangan_flag_pph
									from trans_payroll_detail2 dtl
									left join trans_payroll_detail dtl1
									on dtl1.trans_payroll_detail_id = dtl.trans_payroll_detail_id
									left join tunjangan
									on tunjangan.tunjangan_id = dtl.tunjangan_id
									WHERE CONVERT(nvarchar(36), dtl.trans_payroll_detail_id) = '$trans_payroll_detail_id'
									AND ISNULL(tunjangan.tunjangan_khusus, '0') = '0'
									order by dtl1.karyawan_id,ISNULL(dtl.trans_payroll_detail2_urut,0) asc");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found', 'data' => []], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
