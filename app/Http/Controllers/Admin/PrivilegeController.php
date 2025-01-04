<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PrivilegeController extends Controller
{

    public function rolesWithPermissions()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(["status" => "ok", "data" => $roles]);
    }
    public function roles()
    {
        // $role = auth()->user()->roles;

        // return response()->json(["message" => "OK", "data" => $role]);

        // dd($role);
        // if ($role != "Super Admin") {
        //     return response()->json(["message" => "You are not allowed to view roles"], 403);
        // }
        $roles = Role::all();
        $roles = $roles->map(function ($role) {
            return $role->name;
        });
        return response()->json(["message" => "OK", "data" => $roles]);
    }
    public function addRole()
    {
        // Ensure the user is a Super Admin
        // $role = auth()->user()->role;
        // auth()->user()->assignRole("Super Admin");
        // return [$role];
        // return response()->json(["message" => "You are a super admin"]);
        // return [
        //     "role" => auth()->user()->role,
        //     "roles" => auth()->user()->roles
        // ];
        // if ($role != "Super Admin") {
        //     return response()->json(["message" => "You are not allowed to create a role"], 403);
        // }

        $validated = request()->validate([
            "name" => "required|string|unique:roles,name",
            "permissions" => "nullable|array",
            "permissions.*" => "string|exists:permissions,name"
        ]);

        DB::beginTransaction();
        $role = Role::create([
            "name" => $validated["name"],
            "guard_name" => "admins",
        ]);

        if (isset($validated["permissions"]) && count($validated["permissions"]) > 0) {
            $permissions = Permission::whereIn("name", $validated["permissions"])->get();
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
        }
        DB::commit();
        return response()->json([
            'status' => 'ok',
            "message" => "Role created successfully",
            'data' => $role->load("permissions")
        ], 201);
    }

    public function editRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $role = Role::with('permissions')->findOrFail($id);

        DB::beginTransaction();
        $role->update($request->all());

        if (@$request["permissions"]) {
            $permissions = Permission::where("guard_name", "admins")
                ->whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }
        DB::commit();
        return response()->json([
            'status' => 'ok',
            'message' => 'Role updated successfully',
            'data' => $role->load("permissions")
        ]);
    }

    public function deleteRole($id)
    {
        // $role = auth()->user()->role;
        // if ($role != "Super Admin") {
        //     return response()->json(["status" => "You are not allowed to delete a role"], 403);
        // }

   

        $role = Role::with("permissions")->findOrFail($id);
        $role->permissions()->detach();
        $role->delete();

        return response()->json([
            'status' => 'ok',
            'message' => 'Role deleted successfully',
            'data' => $role
        ]);
    }


    public function permissions()
    {
        // $role = auth()->user()->role;


        $permissions = Permission::where("guard_name", "admins")->get();
        $permissions = $permissions->map(function ($permission) {
            return $permission->name;
        });
        return response()->json(["status" => "OK", "data" => $permissions]);
    }

    // public function addPermission()
    // {
    //     $role = auth()->user()->role;
    //     if ($role != "Super Admin") {
    //         return response()->json(["status" => "You are not allowed to create a permission"], 403);
    //     }
    //     $validated = request()->validate([
    //         "name" => "required|string|unique:permissions,name",
    //     ]);

    //     Permission::create(["name" => $validated["name"], "guard_name" => "admin",]);
    // }

    public function deletePermission()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["status" => "You are not allowed to delete a permission"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
        ]);

        $permission = Permission::where("name", $validated["name"])->first();
        if (!$permission) {
            return response()->json(["status" => "Permission not found"], 404);
        }
        $permission->delete();
    }

    public function editPermission()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["status" => "You are not allowed to edit a permission"], 403);
        }
        $validated = request()->validate([
            "name" => "required|string",
            "new_name" => "required|string",
        ]);

        $permission = Permission::where("name", $validated["name"])->first();
        if (!$permission) {
            return response()->json(["status" => "Permission not found"], 404);
        }
        $permission->name = $validated["new_name"];
        $permission->save();
    }

    public function assignRole()
    {
        $role = auth()->user()->role;
        if ($role != "Super Admin") {
            return response()->json(["status" => "You are not allowed to assign a role"], 403);
        }
        $validated = request()->validate([
            "role" => "required|string",
            "user_id" => "required|integer",
        ]);

        $user = Admin::find($validated["user_id"]);
        if (!$user) {
            return response()->json(["status" => "User not found"], 404);
        }
        $user->assignRole($validated["role"]);
    }



}
