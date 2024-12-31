<?php

namespace App\Http\Controllers;

use App\Models\MenuAccessWeb;
use Illuminate\Http\Request;
use Exception;

class MenuAccessWebController extends Controller
{
    public function index()
    {
        $data = MenuAccessWeb::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'menu_access_id' => 'required|unique:menu_access_web,menu_access_id|string|max:255',
                'menu_id' => 'required|string|max:255',
                'pengguna_grup_id' => 'required|string|max:255',
                'menu_kode' => 'required|string|max:255',
                'status_c' => 'required|numeric',
                'status_r' => 'required|numeric',
                'status_u' => 'required|numeric',
                'status_d' => 'required|numeric'
            ]);

            $data = MenuAccessWeb::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = MenuAccessWeb::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = MenuAccessWeb::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'menu_id' => 'required|string|max:255',
                'pengguna_grup_id' => 'required|string|max:255',
                'menu_kode' => 'required|string|max:255',
                'status_c' => 'required|numeric',
                'status_r' => 'required|numeric',
                'status_u' => 'required|numeric',
                'status_d' => 'required|numeric'
            ]);

            $karyawan->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $karyawan], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $Pengguna = MenuAccessWeb::find($id);

        if (!$Pengguna) {
            return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
        }

        try {
            $Pengguna->delete();

            return response()->json(['status' => '200', 'message' => 'Data deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
