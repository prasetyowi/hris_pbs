<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class VariableGlobalController extends Controller
{


    public function get_newid()
    {
        // dd('Route berhasil diakses');

        try {
            $data = DB::select("select newid() as newid");

            if (count($data) == 0) {
                return response()->json(['status' => '204', 'message' => 'No data found'], 204);
            } else {
                return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Failed to retrieve data', 'error' => $e->getMessage()], 500);
        }
    }

    public function attendance_timesheet(Request $request)
    {
        $column = DB::select("EXEC generate_kolom_attendance_final '$request->attendance', '$request->who'");
        $data = DB::select("EXEC generate_attendance_final '$request->attendance', '$request->who'");

        $result = [];
        if ($data) {
            $searchEmployee = $request->search;
            $result = collect($data)->filter(function ($item) use ($searchEmployee) {
                return stripos($item->karyawan_nama, $searchEmployee) !== false;
            })->values();
        }

        $total = count($result);
        $perPage = count($result);
        $page = 1;
        $orders = $result;

        return response()->json([
            'data' => $orders,
            'columnData' => $column,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'size' => $perPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }
}
