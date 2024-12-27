<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    
    public function info(){
        $user = auth()->user();

        return $user;
    }

    public function edit(Request $request){
        $request->validate([
            "email" => "nullable|string|email|unique:users,email,".auth()->id(),
            "name" => "nullable|string",
            "password" => "nullable|string|min:6",
        ]);


        $request->merge([
            "password" => bcrypt($request->password)
        ]);
        

        $user = auth()->user();
        $user->update($request->all());
        return [
            "status" => "ok",
            "message" => "Profile updated successfully",
            "data" => $user
        ];

    }
}
