<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $adminPermissions = config('adminPermissions');
        // $admin = auth()->user();
        // if ($admin->role == "Super Admin") {
        //     return $next($request);
        // }

        // if ($request->is('admin/*')) {
        //     $path = $request->path();
        //     $path = explode('/', $path);
        //     $path = $path[1];
        //     if (!array_key_exists($path, $adminPermissions)) {
        //         return redirect()->route('admin.dashboard');
        //     }
        // }

        return $next($request);
    }
}
