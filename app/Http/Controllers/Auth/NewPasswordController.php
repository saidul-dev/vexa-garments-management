<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);

    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
//            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', 'string', 'min:4'],

        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
//        return $status == Password::PASSWORD_RESET
//                    ? redirect()->route('login')->with('status', __($status))
//                    : back()->withInput($request->only('email'))
//                            ->withErrors(['email' => __($status)]);

        $user = auth()->user();
        $role = Role::where('name', $user->role)->first();
        $first_role = $role->permissions->pluck('name')->all()[0]; // get auth user first page permission.
        $page = explode('-', $first_role);
        if ($page[0] == 'reports') {
            $first_role = $role->permissions->pluck('name')->all()[1];
            $page = explode('-', $first_role);
        }

        return response()->json([
            'message' => __($status),
            'redirect' => url($page[0]),
        ]);
    }
}
