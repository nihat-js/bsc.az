<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');  // Display the admin login view
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
