<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $data = Attendance::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'attendance_id' => 'required|unique:attendance,attendance_id|string|max:255',
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_kode' => 'required|unique:attendance,attendance_kode|string|max:255',
                'attendance_thn_awal' => 'required|numeric',
                'attendance_bln_awal' => 'required|numeric',
                'attendance_tgl_awal' => 'required|date',
                'attendance_thn_akhir' => 'required|numeric',
                'attendance_bln_akhir' => 'required|numeric',
                'attendance_tgl_akhir' => 'required|date',
                'attendance_who_create' => '',
                'attendance_tgl_create' => '',
                'attendance_who_update' => '',
                'attendance_tgl_update' => '',
                'attendance_is_aktif' => 'required',
                'attendance_is_generate_pph21' => '',
                'attendance_periode_bln' => 'required|numeric',
                'attendance_periode_thn' => 'required|numeric',
                // Tambahkan validasi lain sesuai kebutuhan
            ]);

            $data = Attendance::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = Attendance::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = Attendance::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        try {
            $validated = $request->validate([
                'perusahaan_id' => '',
                'depo_id' => '',
                'attendance_kode' => 'required|string|max:255',
                'attendance_thn_awal' => 'required|numeric',
                'attendance_bln_awal' => 'required|numeric',
                'attendance_tgl_awal' => 'required|date',
                'attendance_thn_akhir' => 'required|numeric',
                'attendance_bln_akhir' => 'required|numeric',
                'attendance_tgl_akhir' => 'required|date',
                'attendance_who_create' => '',
                'attendance_tgl_create' => '',
                'attendance_who_update' => '',
                'attendance_tgl_update' => '',
                'attendance_is_aktif' => 'required',
                'attendance_is_generate_pph21' => '',
                'attendance_periode_bln' => 'required|numeric',
                'attendance_periode_thn' => 'required|numeric',
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
        $Attendance = Attendance::find($id);

        if (!$Attendance) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Attendance->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function Get_attendance_detail2_by_attendance_id($attendance_id)
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::select("select
                                    dtl2.attendance_detail2_id,
                                    dtl2.attendance_id,
                                    hdr.attendance_kode,
                                    dtl2.karyawan_id,
                                    k.karyawan_nama,
                                    dtl2.attendance_detail2_thn,
                                    dtl2.attendance_detail2_bln,
                                    dtl2.attendance_detail2_tgl,
                                    dtl2.attendance_detail2_status
                                from attendance_detail2 dtl2
                                left join attendance hdr
                                on hdr.attendance_id = dtl2.attendance_id
                                left join karyawan k
                                on k.karyawan_id=dtl2.karyawan_id
                                where dtl2.attendance_id = '$attendance_id'");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }


    public function attendance_active()
    {
        $data = Attendance::where('attendance_is_aktif', '1')->get();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function Get_paginate_attendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attendance_periode_thn' => 'nullable|string',
            'attendance_periode_bln' => 'nullable|string',
            'attendance_is_aktif' => 'nullable|string|max:255',
            'search' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
            'sort_by' => 'nullable|string|max:255',
            'sort_order' => 'nullable|string|max:255',
            'status' => 'nullable',
            'status_type' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Bad request',
                'error' => $validator->errors(),
            ], 400);
        }

        $query = DB::table('attendance')
            ->select([
                DB::raw('ROW_NUMBER() OVER (ORDER BY attendance_periode_thn, attendance_periode_bln) AS RowNum'),
                'attendance_id',
                'perusahaan_id',
                'depo_id',
                'attendance_kode',
                'attendance_thn_awal',
                DB::raw('DATENAME(MONTH, attendance_bln_awal) AS attendance_bln_awal'),
                'attendance_tgl_awal',
                'attendance_thn_akhir',
                DB::raw('DATENAME(MONTH, attendance_bln_akhir) AS attendance_bln_akhir'),
                'attendance_tgl_akhir',
                'attendance_who_create',
                'attendance_tgl_create',
                'attendance_who_update',
                'attendance_tgl_update',
                'attendance_is_aktif',
                'attendance_is_generate_pph21',
                DB::raw('DATENAME(MONTH, attendance_periode_bln) AS attendance_periode_bln_nama'),
                'attendance_periode_bln',
                'attendance_periode_thn'
            ]);

        $query->whereNotNull('attendance_id');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('attendance_kode', 'like', "%{$search}%")
                    ->orWhere('attendance_thn_awal', 'like', "%{$search}%")
                    ->orWhereRaw("DATENAME(MONTH, attendance_bln_awal) like ?", ["%{$search}%"])
                    ->orWhereRaw("DATENAME(MONTH, attendance_bln_akhir) like ?", ["%{$search}%"])
                    ->orWhereRaw("DATENAME(MONTH, attendance_periode_bln) like ?", ["%{$search}%"])
                    ->orWhere('attendance_tgl_awal', 'like', "%{$search}%")
                    ->orWhere('attendance_tgl_akhir', 'like', "%{$search}%")
                    ->orWhere('attendance_thn_awal', 'like', "%{$search}%")
                    ->orWhere('attendance_thn_akhir', 'like', "%{$search}%")
                    ->orWhere('attendance_periode_thn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('attendance_is_aktif')) {
            $attendance_is_aktif = $request->input('attendance_is_aktif');
            $query->where(function ($q) use ($attendance_is_aktif) {
                $q->whereRaw("ISNULL(attendance.attendance_is_aktif, 0) = ?", [$attendance_is_aktif]);
            });
        }

        if ($request->filled('attendance_periode_thn')) {
            $attendance_periode_thn = $request->input('attendance_periode_thn');
            $query->where(function ($q) use ($attendance_periode_thn) {
                $q->where('attendance_periode_thn', '=', "$attendance_periode_thn");
            });
        }

        if ($request->filled('attendance_periode_bln')) {
            $attendance_periode_bln = $request->input('attendance_periode_bln');
            $query->where(function ($q) use ($attendance_periode_bln) {
                $q->where('attendance_periode_bln', '=', "$attendance_periode_bln");
            });
        }


        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('attendance_periode_thn')
                ->orderBy('attendance_periode_bln');
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

    public function attendanceRecap(Request $request){
        try {
            $checkPeriodePayroll = Attendance::where('attendance_id', $request->attendance)->first();
            if(!$checkPeriodePayroll) return response()->json(['status' => '400', 'message' => 'Periode payroll is not found'], 400);

            if($checkPeriodePayroll->attendance_is_aktif != '1') return response()->json(['status' => '400', 'message' => 'Periode payroll is not active'], 400);

            DB::select("EXEC generate_attendance_final '$request->attendance', '$request->who'");

            return response()->json(['status' => '200', 'message' => "Attendance Recap Periode {$checkPeriodePayroll->attendance_kode} successfly"], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => '500', 'message' => 'Attendance recap failed', 'data' => $th->getMessage() ], 500);
        }
    }

    public function attendanceList(Request $request)
    {
        $attendance = Attendance::where('attendance_id', $request->attendance)->first();

        $startDate = Carbon::parse($attendance->attendance_tgl_awal);
        $endDate = Carbon::parse($attendance->attendance_tgl_akhir);

        $countPeriode = $startDate->diffInDays($endDate) + 1;
        $arrayDate = [];
        $counter = 1;

        while ($startDate->lte($endDate)) {
            $arrayDate[] = [
                'display' => $startDate->format('d/m/Y'),
                'nameAs'  => 'A' . $counter
            ];

            $startDate->addDay();
            $counter++;
        }

        $dynamicColumns = [];
        for ($i = 1; $i <= $countPeriode; $i++) {
            $dynamicColumns[] = DB::raw("b.A$i");
        }

        $query = DB::table('attendance as a')
            ->leftJoin('attendance_detail as b', 'a.attendance_id', '=', 'b.attendance_id')
            ->leftJoin('karyawan as c', 'b.karyawan_id', '=', 'c.karyawan_id')
            ->select(array_merge([
                'c.karyawan_nama',
                'c.karyawan_nip',
                'b.attendance_detail_masuk',
                'b.attendance_detail_dinas',
                'b.attendance_detail_cuti',
                'b.attendance_detail_ijin',
                'b.attendance_detail_off',
                'b.attendance_detail_alpha',
                'b.attendance_detail_libur',
            ], $dynamicColumns))
            ->where('a.attendance_id', $attendance->attendance_id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('c.karyawan_nama', 'like', "%{$search}%")
                    ->orWhere('c.karyawan_nip', 'like', "%{$search}%");
            });
        }


        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->input('sort_by'), $request->input('sort_order'));
        } else {
            $query->orderBy('c.karyawan_nama');
        }

        $total = $query->count();

        $perPage = $request->input('size', 10);
        $page = $request->input('page', 1);
        $orders = $query->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();

        return response()->json([
            'data' => $orders,
            'columnData' => $arrayDate,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'size' => $perPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);

        return response()->json($countPeriode);
    }
}
