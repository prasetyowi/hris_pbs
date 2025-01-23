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
}
