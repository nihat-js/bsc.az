<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRole = auth("admin")->user()->role;
        $userPermissions = $userRole->permissions->pluck('name');
        // echo "birbri";
        // if (!auth('admin')->check()) {
        //     return response()->json([
        //         'message' => 'Unauthorized'
        //     ], 401);
        // }
        // return $next($request);
    }
}
