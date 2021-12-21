<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VerifikasiController extends Controller
{

    
    public function verifikasi($data)
    {
        
        $user = User::where('remember_token',$data)->first();
        if($user){
            $user->email_verified_at = date("Y-m-d H:i:s");
            $user->save();
            echo '<center><h1>Verifikasi Berhasil.<h1></center><meta http-equiv="refresh" content="3;url=http://uas.vocarito.com:8080/" />';
        }else{
            echo '<center>Verifikasi GAGAL.</center><meta http-equiv="refresh" content="3;url=http://uas.vocarito.com:8080/" />';
        }
    }

    

    
}
