<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{


    public function register(Request $request)
    {

      
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:6',
        ]);

        // return response()->json([
        //     'message' => 'You are in register'
        // ]);

        $user = Admin::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
    public function login(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = Admin::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['Email və ya şifrə yanlışdır'],
            ]);
        }
        // 660/12=340
        // 
        // $user->tokens()->delete();
        // 
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'name' => $user->name,
            'token' => $user->createToken('Admin Token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'status' => 'ok',
                'message' => 'User logged out successfully'
            ]
        );
    }

    public function status(){
        $user = auth()->user();
        return response()->json([
            'status' => 'ok',
            'message' => 'User is logged in',
            "data" => $user
        ]);
    }

    public function test()
    {
        $name = auth()->user()->password;
        return response()->json([
            'message' => 'You are in',
            "name" => $name
        ]);

    }

   
}
