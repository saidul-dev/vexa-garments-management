<?php

namespace App\Http\Controllers;

use App\Helpers\HasUploader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class MaanUserController extends Controller
{
    use HasUploader;

    public function __construct()
    {
        $this->middleware('permission:users-create')->only('create', 'store');
        $this->middleware('permission:users-read')->only('index', 'show');
        $this->middleware('permission:users-update')->only('edit', 'update');
        $this->middleware('permission:users-delete')->only('destroy');
    }

    public function index()
    {
        $users = User::where('role', '!=', 'superadmin')
                    ->when(request('users'), function ($q) {
                        $q->where('role', request('users'));
                    })
                    ->latest()
                    ->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    /** filter the table list */
    public function maanFilter(Request $request)
    {
        $users = User::where('role', '!=', 'superadmin')->where('role', $request->role)
                    ->when($request->per_page, function($q) use ($request) {
                        $q->where('role', $request->role);
                    })
                    ->when($request->search, function ($query) use ($request) {
                        $query->where(function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%')
                                ->orWhere('phone', 'like', '%' . $request->search . '%')
                                ->orWhere('country', 'like', '%' . $request->search . '%');
                        });
                    })
                    ->latest()
                    ->paginate($request->per_page ?? 10);

        if ($request->ajax()) {
            return response()->json([
                'data' => view('pages.users.datas', compact('users'))->render()
            ]);
        }
        return redirect(url()->previous());
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'superadmin')->latest()->get();
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);
        return view('pages.users.create', compact('countries', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'country' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $user = User::create($request->except('image', 'password') + [
            'image' => $request->image ? $this->upload($request, 'image') : null,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->sync($role->id);

        sendNotification($user->id, route('users.index', ['users' => $request->role]), __(ucfirst($request->role).' has been created.'), 'action', null, null, true);
        return response()->json([
            'message' => __(ucfirst($request->role) . ' created successfully'),
            'redirect' => route('users.index', ['users' => $request->role])
        ]);
    }

    public function edit(User $user)
    {
        if ($user->role == 'superadmin' && auth()->user()->role != 'superadmin') {
            abort(403);
        }
        $roles = Role::latest()->get();
        $countries = base_path('lang/countrylist.json');
        $countries = json_decode(file_get_contents($countries), true);
        return view('pages.users.edit', compact('countries', 'user', 'roles'));

    }

    public function update(Request $request, User $user)
    {
        if ($user->role == 'superadmin' && auth()->user()->role != 'superadmin') {
            return response()->json(__('You can not update a superadmin.'), 400);
        }
        $request->validate([
            'role' => 'required|string',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|confirmed',
            'image' => 'nullable|image',
        ]);

        $role = Role::where('name', $request->role)->first();
        $user->roles()->sync($role->id);
        $user->update($request->except('image', 'password') + [
            'image' => $request->image ? $this->upload($request, 'image', $user->image) : $user->image,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return response()->json([
            'message' => __(ucfirst($request->role) . ' updated successfully'),
            'redirect' => route('users.index', ['users' => $request->role])
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->role == 'superadmin') {
            return response()->json(__('You can not delete a superadmin.'), 400);
        }
        $type = $user->role;
        if (file_exists($user->image)) {
            Storage::delete($user->image);
        }
        $user->delete();
        return response()->json([
            'message' => __(ucfirst($type) . ' deleted successfully'),
            'redirect' => route('users.index', ['users' => $type])
        ]);
    }
}
