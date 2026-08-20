<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class DemoMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (env('DEMO_MODE', false)) {
            $disabledRoutes = [
                'system-settings.store',
            ];

            $except_role = ['superadmin', 'admin', 'manager', 'merchandiser', 'commercial', 'accountant', 'production', 'buyer'];

            $except_email = ['superadmin@superadmin.com', 'admin@admin.com', 'manager@manager.com', 'merchandiser@merchandiser.com', 'commercial@commercial.com', 'accountant@accountant.com', 'production@production.com', 'buyer@buyer.com'];

            $response = response()->json(['message' => 'This action is disabled in demo mode.'], 403);

            if (in_array(Route::currentRouteName(), $disabledRoutes)) {
                return $response;
            } elseif (in_array(Route::currentRouteName(), ['user-profile.update', 'users.update', 'users.destroy'])) {
                if ($userId = $request->route('user_profile')) {
                    $user = User::findOrFail($userId);
                } else {
                    $user = $request->route('user');
                }

                if (in_array($user->email, $except_email)) {
                    return $response;
                }
            } elseif (Route::currentRouteName() == 'roles.update') {
                $role = $request->route('role')->name;

                if (in_array($role, $except_role)) {
                    return $response;
                }
            }
        }

        return $next($request);
    }
}
