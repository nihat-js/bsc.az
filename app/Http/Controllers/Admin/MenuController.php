<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function list(){
        $menus = Menu::all();
        $userPermission = auth('admin')->user()->role->permissions->pluck('name');
        $menus = $menus->filter(function($menu) use ($userPermission){
            return $userPermission->contains($menu->name);
        });
    }
}
