<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Cache;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    public function resetFilterCache(){
        Cache::forget('filter');



        return redirect()->back()->with('success', 'Cache reset successfully');
    }
}
