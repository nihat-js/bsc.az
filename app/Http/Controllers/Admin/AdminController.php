<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Hash;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{


    public function __construct()
    {
        // $role = auth()->user()->role;
        // if ($role != "Super Admin"){
        //     return response()->json([
        //         'message' => 'You are not authorized to access this route'
        //     ], 403);
        // }
    }

    public function all()
    {

        $admins = Admin::with('roles')->get()->map(function ($admin) {
            $admin->role = $admin->roles->first()->name ?? null;
            unset($admin->roles);
            return $admin;
        });
        return response()->json($admins);
    }

    public function add(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string',
            "role" => "required|string"
        ]);

        if (Role::where("name", $request->role)->count() == 0) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        $admin->assignRole($request->role);
        return response()->json([
            "status" => "ok",
            'message' => 'Admin created successfully',
            'data' => $admin
        ], 201);
    }

    public function one($id)
    {
        $admin = Admin::findOrFail($id)->with("roles")->get();

        return response()->json(
            [
                "status" => "ok",
                'data' => $admin
            ],
            200
        );
    }

    public function delete($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->roles->first()->name == "Super Admin") {
            return response()->json([
                "status" => "error",
                'message' => 'Əsas admin silinə bilməz'
            ], 403);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ], 200);
    }

    public function edit(Request $request, $id)
    {

    $admin = Admin::findOrFail($id);

    $validatedData = $request->validate([
        'name' => 'sometimes|string',
        'email' => 'sometimes|email',
        'password' => 'sometimes|string', 
        'role' => 'sometimes|string'  
    ]);

    if (!empty($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    } else {
        unset($validatedData['password']);
    }

    $admin->update($validatedData);

    if (isset($validatedData['role'])) {
        $role = Role::where("name", $validatedData["role"])->firstOrFail();
        $admin->syncRoles([$role->name]);  //
    }

    $admin->role = $admin->roles->first()->name ?? null;

    return response()->json([
        "status" => "ok",
        'message' => 'Admin updated successfully',
        'data' => $admin
    ], 200);

    }
}
// .75x1.7=1.275+.23