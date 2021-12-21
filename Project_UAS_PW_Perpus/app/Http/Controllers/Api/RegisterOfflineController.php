<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\RegisterOffline;

use function PHPUnit\Framework\isNull;

class RegisterOfflineController extends Controller
{
    public function index()
    {
        $registers = RegisterOffline::all();

        if(count($registers) > 0)
        {
            return response([
                'message' => 'Retrieve All Register Data Success',
                'data' => $registers
            ], 200);
        }
        return response([
            'message' => 'Empty Register Data',
            'data' => null
        ], 400);
    }

    public function show($id) 
    {
        $register = RegisterOffline::find($id);

        if(!is_null($register))
        {
            return response([
                'message' => 'Retrieve Register User Success',
                'data' => $register
            ], 200);
        }

        return response([
            'message' => 'Register Data Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeRegister = $request->all();
        $validate = Validator::make($storeRegister, [
            'nama' => 'required|max:60',
            'lokasi' => 'required',
            'tanggal_masuk' => 'required|date_format:Y-m-d'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $register = RegisterOffline::create($storeRegister);
        return response([
            'message' => 'Register Offline Success',
            'data' => $register
        ], 200);
    }

    public function destroy($id)
    {
        $register = RegisterOffline::find($id);
        
        if(is_null($register))
        {
            return response([
                'message' => 'Data Register Not Found',
                'data' => null
            ], 404);
        }

        if($register->delete())
        {
            return response([
                'message' => 'Delete Data Register Success',
                'data' => $register
            ], 200);
        }

        return response([
            'message' => 'Delete Data Register Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $register = RegisterOffline::find($id);
        
        if(is_null($register))
        {
            return response([
                'message' => 'Data Register Not Found',
                'data' => null
            ], 404);
        }

        $updateRegister = $request->all();
        $validate = Validator::make($updateRegister, [
            'nama' => 'required|max:60',
            'lokasi' => 'required',
            'tanggal_masuk' => 'required|date_format:Y-m-d'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $register->nama = $updateRegister['nama'];
        $register->lokasi = $updateRegister['lokasi'];
        $register->tanggal_masuk = $updateRegister['tanggal_masuk'];

        if($register->save())
        {
            return response([
                'message' => 'Update Data Register Success',
                'data' => $register
            ], 200);
        }
    }
}
