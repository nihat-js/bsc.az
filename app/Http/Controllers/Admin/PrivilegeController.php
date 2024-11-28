<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
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

    }
    public function assignRole()
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
