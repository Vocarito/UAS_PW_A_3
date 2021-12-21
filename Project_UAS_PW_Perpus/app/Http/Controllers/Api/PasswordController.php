<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\MatchOldPass;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;
use Illuminate\Validation\Rule;


class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        // return view('changePassword');
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message' => 'User Product Success',
                'data' => $user
            ],200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ],404);
    }

    public function store(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => $user
            ],404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'nama_pengunjung' => 'required|max:60',
            'email' => ['required','email:rfc,dns', Rule::unique('users')->ignore($user)],
            'oldPass' => ['required', new MatchOldPass],
            'newPass' => ['required'],
            'confirmPass' => 'same:newPass|required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $user->nama_pengunjung = $updateData['nama_pengunjung'];
        $user->email = $updateData['email'];
            
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->newPass)]);
   
        if($user->save()){
            return response([
                'message' => 'Password and Profil change successfully.',
                'data' => $user
            ],200);
        }
    }

}
