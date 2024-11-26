<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function givePermission(){

    }
    
    public function createRole(){

    }
    public function assignRole(){

    }

    public function removeRole(){
        
    }
    public function permissions(){
        // $permission = Permission
    }

    public function roles(){
        $roles = Role::all()->pluck('name');
    } 
}
