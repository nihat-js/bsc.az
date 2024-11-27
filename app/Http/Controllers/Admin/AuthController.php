<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

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
        $user = Admin::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['Username or password incorrect'],
            ]);
        }
        // $user->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'name' => $user->name,
            'token' => $user->createToken('Admin Token')->plainTextToken,
        ]);
    }
    public function test()
    {

        // if (Auth::guard('admin')->check()) {
        //     return response()->json([
        //         'message' => 'Finallyyy',
        //     ]);
        // }
        // echo "Qese";
        return response()->json([
            'message' => 'You are in',
        ]);
        // if (Auth::guard('admin')->check()) {
        //     return response()->json([
        //         'message' => 'You are in',
        //     ]);
        // } else {
        //     return response()->json([
        //         'message' => 'You are not authorized',
        //     ]);
        // }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User logged out successfully'
            ]
        );
    }
}
