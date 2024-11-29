<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PrivilegeController extends Controller
{
    public function givePermission()
    {

    }

    public function createRole()
    {
        $role = auth()->user()->role;
        if ($role  != "Super Admin"){
            return response()->json(["message" => "You are not allowed to create a role"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string|unique:roles,name",
            "permissions" => "nullable|array"
        ]);

        Role::create(["name" => $validated["name"], "guard_name" => "admin",]);
    }
    public function assignPermissionToRole()
    {   

    }

    public function removeRole()
    {

    }

    public function all()
    {
        $roles = Role::with("permissions")->get();
        return response()->json(["message" => "OK", "data" => $roles]);
    }


    

    public function permissions()
    {
        $permissions = Permission::all()->pluck('name');
        return response()->json(["message" => "OK", "data" => $permissions]);
    }
}
