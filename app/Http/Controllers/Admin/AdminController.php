<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{


    public function __construct(){
        // $role = auth()->user()->role;
        // if ($role != "Super Admin"){
        //     return response()->json([
        //         'message' => 'You are not authorized to access this route'
        //     ], 403);
        // }
    }

    public function all(){
        return Admin::with('roles')->get();
    }

    public function add(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin
        ], 201);
    }

    public function details($id){
        $admin = Admin::findOrFail($id);
    }

    public function delete($id){


        $admin = Admin::findOrFail($id);
        if ($admin->role == "Super Admin"){
            return response()->json([
                'message' => 'Əsas admin silinə bilməz'
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ], 200);
    }

    public function edit(Request $request,$id){


        $admin = Admin::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            "role" => "string"
        ]);

        $admin->update($validatedData);
        $role = Role::where("name",$validatedData["role"])->firstOrFail();
        $admin->role()->sync($role->id);


        return response()->json([
            'message' => 'Admin updated successfully',
            'admin' => $admin
        ], 200);

    }
}
// .75x1.7=1.275+.23