<?php

namespace App\Http\Controllers;

use App\Models\MenuWeb;
use Illuminate\Http\Request;
use Exception;

class MenuWebController extends Controller
{
    public function index()
    {
        $data = MenuWeb::all();
        return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'menu_id' => 'required|unique:menu_web,menu_id|string|max:255',
                'menu_kode' => 'required|unique:menu_web,menu_kode|string|max:255',
                'menu_link' => 'required|unique:menu_web,menu_link|string|max:255',
                'menu_name' => 'required|unique:menu_web,menu_name|string|max:255',
                'menu_class' => '',
                'menu_parent' => '',
                'menu_order' => '',
                'menu_c' => '',
                'menu_r' => '',
                'menu_u' => '',
                'menu_d' => '',
                'tipe' => '',
                'menu_application' => '',
                'menu_is_detail' => '',
                'menu_color' => '',
                'menu_position' => '',
                'menu_coordinate' => '',
                'menu_order_kode' => ''
            ]);

            $data = MenuWeb::create($validated);

            return response()->json(['status' => '200', 'message' => 'Data created successfully', 'validated' => $validated, 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data creation failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $data = MenuWeb::find($id);
        if ($data) {
            return response()->json(['status' => '200', 'message' => 'Data retrieved successfully', 'data' => $data], 200);
        }

        return response()->json(['status' => '404', 'message' => 'Data not found'], 404);
    }

    public function update(Request $request, $id)
    {
        $karyawan = MenuWeb::find($id);
        if (!$karyawan) {
            return response()->json(['message' => 'Trans Payroll not found'], 404);
        }

        try {
            $validated = $request->validate([
                'menu_id' => 'required|string|max:255',
                'menu_kode' => 'required|string|max:255',
                'menu_link' => 'required|string|max:255',
                'menu_name' => 'required|string|max:255',
                'menu_class' => '',
                'menu_parent' => '',
                'menu_order' => '',
                'menu_c' => '',
                'menu_r' => '',
                'menu_u' => '',
                'menu_d' => '',
                'tipe' => '',
                'menu_application' => '',
                'menu_is_detail' => '',
                'menu_color' => '',
                'menu_position' => '',
                'menu_coordinate' => '',
                'menu_order_kode' => ''
            ]);

            $karyawan->update($validated);

            return response()->json(['status' => '200', 'message' => 'Data updated successfully', 'validated' => $validated, 'data' => $karyawan], 200);
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Data update failed', 'data' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $Pengguna = MenuWeb::find($id);

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
