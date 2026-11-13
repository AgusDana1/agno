<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view(
            'admin.users.create',
            compact('roles')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make(
                $validated['password']
            ),
            'role_id' => $validated['role_id'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil dibuat.'
            );
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        return view(
            'admin.users.edit',
            compact('user', 'roles')
        );
    }

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        /*
         * Jangan izinkan admin menghapus
         * atau menurunkan role dirinya sendiri
         * secara sembarangan.
         */
        if (
            $user->id === $request->user()->id &&
            (int) $validated['role_id'] !==
            (int) $user->role_id
        ) {
            return back()
                ->withErrors([
                    'role_id' => 'Anda tidak dapat mengubah role akun sendiri.',
                ])
                ->withInput();
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] =
                Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil diperbarui.'
            );
    }

    public function destroy(
        Request $request,
        User $user
    ) {
        if (
            $user->id === $request->user()->id
        ) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }

        $user->delete();

        return back()->with(
            'success',
            'User berhasil dihapus.'
        );
    }
}
