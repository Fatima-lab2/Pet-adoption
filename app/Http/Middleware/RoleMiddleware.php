<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next, $roles)
{
    if (!auth()->check()) {
        abort(403, 'Unauthorized');
    }

    $user = auth()->user();
    $roles = explode('|', $roles);

    // Fetch active roles from user_role for this user
    $roleObjects = DB::select('
        SELECT role.role_name
        FROM user_role
        JOIN role ON user_role.role_id = role.role_id
        WHERE user_role.user_id = ?
        AND user_role.is_active = 1
    ', [$user->user_id]);

    // If user has no active roles at all, block them
    if (empty($roleObjects)) {
        abort(403, 'Unauthorized - No active roles');
    }

    // Convert result to array of role names
    $activeRoles = array_map(function ($obj) {
        return $obj->role_name;
    }, $roleObjects);

    // If the required roles are not among the active ones, block access
    if (!array_intersect($roles, $activeRoles)) {
        abort(403, 'Unauthorized - Role inactive or not assigned');
    }

    return $next($request);
}


}
