<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = auth()->user();
                $role = Role::where('name', $user->role)->first(); // get auth user role
                $first_role = $role->permissions->pluck('name')->all()[0]; // get auth user first page permission.
                $pattern = '/-\w+$/';
                $page = preg_replace($pattern, '', $first_role);
                return redirect($page)->withSuccess('Signed in');
            }
        }

        return $next($request);
    }
}
