<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Buku;

class BukuController extends Controller
{
    //method untuk menampilkan semua data buku
    public function index() 
    {
        $bukus = Buku::all();
        
        if(count($bukus) > 0 ) 
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $bukus
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $buku = Buku::find($id);

        if(!is_null($buku)) 
        {
            return response([
                'message' => 'Retrieve Buku Success',
                'data' => $buku
            ], 200);
        }

        return response([
            'message' => 'Buku Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request) 
    {
        $storeBuku = $request->all();
        $validate = Validator::make($storeBuku, [
            'judul_buku' => 'required|max:100',
            'pengarang' => 'required', 
            'penerbit' => 'required', 
            'thn_terbit' => 'required|numeric', 
            'jenis_buku' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $buku = Buku::create($storeBuku);
        return response([
            'message' => 'Add Buku Success',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if(is_null($buku))
        {
            return response([
                'message' => 'Buku Not Found',
                'data' => null
            ], 404);
        }

        if($buku->delete()) 
        {
            return response([
                'message' => 'Delete Buku Success',
                'data' => $buku
            ], 200);
        }

        return response([
            'message' => 'Delete Buku Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);
        if(is_null($buku))
        {
            return response([
                'message' => 'Buku Not Found',
                'data' => null
            ], 404);
        }

        $updateBuku = $request->all();
        $validate = Validator::make($updateBuku, [
            'judul_buku' => 'required|max:100',
            'pengarang' => 'required', 
            'penerbit' => 'required', 
            'thn_terbit' => 'required|numeric', 
            'jenis_buku' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $buku->judul_buku = $updateBuku['judul_buku'];
        $buku->pengarang = $updateBuku['pengarang'];
        $buku->penerbit = $updateBuku['penerbit'];
        $buku->thn_terbit = $updateBuku['thn_terbit'];
        $buku->jenis_buku = $updateBuku['jenis_buku'];

        if($buku->save())
        {
            return response([
                'message' => 'Update Buku Success',
                'data' => $buku
            ], 200);
        }

        return response([
            'message' => 'Update Book Failed',
            'data' => null
        ], 400);
    }
}
