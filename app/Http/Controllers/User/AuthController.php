<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $validatedData["password"] = Hash::make($validatedData["password"]);

        $user = User::create($validatedData);
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ], 201);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['Email və ya şifrə yanlışdır'],
            ]);
        }


        $token = $user->createToken('User Token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }
    public function test()
    {
        return "gelmisem";

        // if (Auth::guard('user')->check()) {
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
                'status' => 'ok',
                'message' => 'User logged out successfully'
            ]
        );
    }

    public function status(Request $request){
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            // 'message' => '',
            'data' => $request->user(),
        ]);
    }
}
