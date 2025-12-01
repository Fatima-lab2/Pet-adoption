<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DepartmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $allowedDepartments)
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();

        // Join employee to get department_id
        $employee = DB::table('employee')->where('user_id', $user->user_id)->first();

        if (!$employee) {
            abort(403, 'Unauthorized - Not an employee');
        }

        $allowedDepartmentsArray = explode('|', $allowedDepartments);

        if (!in_array($employee->department_id, $allowedDepartmentsArray)) {
            abort(403, 'Unauthorized - Department restriction');
        }

        return $next($request);
    }
}
