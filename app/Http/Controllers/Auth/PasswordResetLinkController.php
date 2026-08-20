<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

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
