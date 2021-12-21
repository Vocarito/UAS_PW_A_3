<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Staff;

class StaffController extends Controller
{
    
    public function index()
    {
        $staffs = Staff::all();
        
        if(count($staffs) > 0)
        {
            return response([
                'message' => 'Retrieve All Staff Success',
                'data' => $staffs
            ], 200);
        }

        return response([
            'message' => 'Empty Staff',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $staff = Staff::find($id);

        if(!is_null($staff))
        {
            return response([
                'message' => 'Retrieve Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Staff Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeStaff = $request->all();
        $validate = Validator::make($storeStaff, [
            'nomor_pegawai' => 'required|numeric|unique:staff',
            'nama_pegawai' => 'required|max:60',
            'role' => 'required', 
            'status' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $staff = Staff::create($storeStaff);
        return response([
            'message' => 'Add Staff Success',
            'data' => $staff
        ], 200);
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);

        if(is_null($staff))
        {
            return response([
                'message' => 'Staff Not Found',
                'data' => null
            ], 404);
        }

        if($staff->delete())
        {
            return response([
                'message' => 'Delete Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Delete Staff Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);

        if(is_null($staff))
        {
            return response([
                'message' => 'Staff Not Found',
                'data' => null
            ], 404);
        }

        $updateStaff = $request->all();
        $validate = Validator::make($updateStaff, [
            'nomor_pegawai' =>  ['required', 'numeric', Rule::unique('staff')->ignore($staff)],
            'nama_pegawai' => 'required|max:60',
            'role' => 'required', 
            'status' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        

        $staff->nomor_pegawai = $updateStaff['nomor_pegawai'];
        $staff->nama_pegawai = $updateStaff['nama_pegawai'];
        $staff->role = $updateStaff['role'];
        $staff->status = $updateStaff['status'];

        if($staff->save())
        {
            return response([
                'message' => 'Update Staff Success',
                'data' => $staff
            ], 200);
        }

        return response([
            'message' => 'Update Staff Failed',
            'data' => null
        ], 400);


    }

}
