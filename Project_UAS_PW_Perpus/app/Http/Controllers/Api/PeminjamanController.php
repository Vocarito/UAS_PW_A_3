<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index() 
    {
        $peminjamans = Peminjaman::all();
        
        if(count($peminjamans) > 0 ) 
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $peminjamans
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::find($id);

        if(!is_null($peminjaman)) 
        {
            return response([
                'message' => 'Retrieve Peminjaman Success',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Peminjaman Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request) 
    {
        $storePeminjaman = $request->all();
        $validate = Validator::make($storePeminjaman, [
            'nama_peminjam' => 'required|max:60',
            'judul_buku' => 'required|max:100', 
            'tanggal_pinjam' => 'required|date_format:Y-m-d', 
            'tanggal_kembali' => 'required|date_format:Y-m-d', 
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $peminjaman = Peminjaman::create($storePeminjaman);
        return response([
            'message' => 'Add Peminjaman Success',
            'data' => $peminjaman
        ], 200);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);

        if(is_null($peminjaman))
        {
            return response([
                'message' => 'Peminjaman Not Found',
                'data' => null
            ], 404);
        }

        if($peminjaman->delete()) 
        {
            return response([
                'message' => 'Delete Peminjaman Success',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Delete Peminjaman Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);
        if(is_null($peminjaman))
        {
            return response([
                'message' => 'Peminjaman Not Found',
                'data' => null
            ], 404);
        }

        $updatePeminjaman = $request->all();
        $validate = Validator::make($updatePeminjaman, [
            'nama_peminjam' => 'required|max:60',
            'judul_buku' => 'required|max:100', 
            'tanggal_pinjam' => 'required|date_format:Y-m-d', 
            'tanggal_kembali' => 'required|date_format:Y-m-d', 
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $peminjaman->nama_peminjam = $updatePeminjaman['nama_peminjam'];
        $peminjaman->judul_buku = $updatePeminjaman['judul_buku'];
        $peminjaman->tanggal_pinjam = $updatePeminjaman['tanggal_pinjam'];
        $peminjaman->tanggal_kembali = $updatePeminjaman['tanggal_kembali'];

        if($peminjaman->save())
        {
            return response([
                'message' => 'Update Peminjaman Success',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Update Peminjaman Failed',
            'data' => null
        ], 400);
    }
}
