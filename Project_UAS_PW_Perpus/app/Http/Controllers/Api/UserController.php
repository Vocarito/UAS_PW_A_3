<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    // method untuk menampilkan semua data product (read)
    public function index()
    {
        $users = User::all(); //mengambil semua data course

        if(count($users)> 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $users
            ], 200);
        } // return data semua course dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data course kosong

    }

    // method untuk menampilkan 1 data course (search)
    public function show($id)
    {
        $user = User::find($id); //mencari data course berdasarkan id

        if(!is_null($user)) {
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        } // return data course yang ditemukan dalam bentuk json

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404); //return message saat data course tidak ditemukan
    }

    //method untuk menambah 1 data course baru (create)
    public function store(Request $request)
    {
        $storeData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($storeData, [
            'nama_pengunjung' =>'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'username' => 'required|max:10',
            'password' => 'required',
            'status' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
            //tambahi
            $storeData['password'] = bcrypt($request->password); //enkripsi password
            $user = User::create($storeData);
            return response([
                'message' => 'Add User Success',
                'data' => $user
            ], 200); // return data course baru dalam bentuk json
    }

    //method untuk menghapus 1 data product (delete)
    public function destroy($id)
    {
        $user = User::find($id); //mencari data product berdasarkan id
        
        if (is_null($user)) {
            return response([
                'message' =>'User Not Found',
                'data' => null
            ], 404);
        }// return message saat data course tidak ditemukan

        if($user->delete()) {
            return response([
                'message' =>'Delete User Success',
                'data' => $user
            ], 200); 
        } // return message saat berhasil menghapus data course

        return response([
            'message' => 'Delete User Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data course

    }

    //method untuk mengubah 1 data course (update)
    public function update(Request $request, $id)
    {
        $user = User::find($id); //mencari data course berdasarkan id
        if (is_null($user)) {
            return response([
                'message' =>'User Not Found',
                'data' => null
            ], 404);
        }// return message saat data course tidak ditemukan

    
        $updateData = $request->all(); //mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            'nama_pengunjung' =>'required|max:60',
            'email' => ['required','email:rfc,dns', Rule::unique('users')->ignore($user)],
            'username' => 'required|max:10',
            'password' => 'required',
            'status' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
        
        $user->nama_pengunjung = $updateData['nama_pengunjung']; //edit nama kelas
        $user->email = $updateData['email']; //edit kode
        $user->username = $updateData['username']; //edit kode
        //tambahan
        $user->password = $updateData['password'] = bcrypt($request->password);
        $user->status = $updateData['status']; //edit kode

        if($user->save()) {
            return response([
                'message' => 'Update User Success',
                'data' =>$user
            ], 200);
        } //return data course yang telah di edit dalam bentuk json
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ], 400); // return message daat course gagal di edit
    }
}
