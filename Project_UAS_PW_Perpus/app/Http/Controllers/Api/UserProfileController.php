<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\UserProfile;
use App\Models\User;

class UserProfileController extends Controller{

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
            'image' => 'required|mimes:jpeg,jpg,png,gif',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        if($request->hasFile('image')){    
            $image = $request->file('image');
            $img_name = $image->getClientOriginalName();
            $image->move(public_path("user_profile/"), $img_name);
        }

        $user->image = $img_name;
        
        if($user->save()){
            return response([
                'message' => 'Update Photo Profil Success',
                'data' => $user
            ],200);
        }

        return response([
            'message' => 'Update Photo Profil Failed',
            'data' => null
        ],400);
    }
}