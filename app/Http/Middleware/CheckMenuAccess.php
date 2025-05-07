<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            abort(403, 'Unauthorized access');
        }

        $routeName = $request->route()->getName();


        $allowedRoutes = $admin->role->menus()->pluck('route')->toArray();


        foreach ($allowedRoutes as $allowedRoute) {
            if (str_starts_with($routeName, $allowedRoute)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access');

    }
}
