<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PrivilegeController extends Controller
{

    public function roles()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to view roles"], 403);
        }
        $roles = Role::all();
        return response()->json(["message" => "OK", "data" => $roles]);
    }
    public function addRole()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to create a role"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string|unique:roles,name",
            "permissions" => "nullable|array"
        ]);

        Role::create(["name" => $validated["name"], "guard_name" => "admin",]);
        $role = Role::where("name", $validated["name"])->first();
        // $role->givePermissionTo($validated["permissions"]);
        // Permission::whereIn("name", $validated["permissions"])->get()->each(function ($permission) use ($validated) {
        //     $role->givePermissionTo($permission);
        // });
    }

    public function deleteRole()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to delete a role"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
        ]);

        $role = Role::where("name", $validated["name"])->first();
        if (!$role) {
            return response()->json(["message" => "Role not found"], 404);
        }
        $role->delete();
    }

    public function editRole()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to edit a role"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
            "new_name" => "required|string",
            "permissions" => "nullable|array"
        ]);

        $role = Role::where("name", $validated["name"])->first();
        if (!$role) {
            return response()->json(["message" => "Role not found"], 404);
        }
        $role->name = $validated["new_name"];
        $role->save();
        // $role->syncPermissions($validated["permissions"]);
        // Permission::whereIn("name", $validated["permissions"])->get()->each(function ($permission) use ($role) {
        //     $role->givePermissionTo($permission);
        // });
    }

    public function permissions(){
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to view permissions"], 403);
        }
        $permissions = Permission::all();
        return response()->json(["message" => "OK", "data" => $permissions]);
    }

    public function addPermission(){
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to create a permission"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string|unique:permissions,name",
        ]);

        Permission::create(["name" => $validated["name"], "guard_name" => "admin",]);
    }

    public function deletePermission(){
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to delete a permission"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
        ]);

        $permission = Permission::where("name", $validated["name"])->first();
        if (!$permission) {
            return response()->json(["message" => "Permission not found"], 404);
        }
        $permission->delete();
    }

    public function editPermission(){
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to edit a permission"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
            "new_name" => "required|string",
        ]);

        $permission = Permission::where("name", $validated["name"])->first();
        if (!$permission) {
            return response()->json(["message" => "Permission not found"], 404);
        }
        $permission->name = $validated["new_name"];
        $permission->save();
    }

    public function assignRole(){
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["message" => "You are not allowed to assign a role"], 403);
        }
        $validated = request()->validate([
            "role" => "required|string",
            "user_id" => "required|integer",
        ]);

        $user = Admin::find($validated["user_id"]);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $user->assignRole($validated["role"]);
    }

    

}
