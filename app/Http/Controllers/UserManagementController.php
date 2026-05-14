<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $rolesQuery = Role::orderBy('name');
        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->where('name', '!=', 'superadmin');
        }
        $roles = $rolesQuery->get();

        $query = User::query()
            ->with(['roles', 'branch'])
            ->withCount('driver')
            ->latest();

        if (auth()->check() && !auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
            $query->where('branch_id', auth()->user()->branch_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->paginate(15)->withQueryString();

        return view('user-management.index', compact('users', 'roles'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create user'), 403);

        $rolesQuery = Role::orderBy('name');
        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->where('name', '!=', 'superadmin');
        }
        $roles = $rolesQuery->get();
        $branches = \App\Models\Branch::orderBy('name')->get();

        return view('user-management.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create user'), 403);

        $validated = $request->validate([
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email|max:255|unique:users,email',
            'users.*.password' => 'required|string|min:8|confirmed',
            'users.*.role' => 'required|exists:roles,name',
            'users.*.branch_id' => 'nullable|exists:branches,id',
        ], [
            'users.*.name.required' => 'Nama wajib diisi',
            'users.*.email.required' => 'Email wajib diisi',
            'users.*.email.unique' => 'Email sudah digunakan',
            'users.*.password.required' => 'Password wajib diisi',
            'users.*.password.confirmed' => 'Konfirmasi password tidak cocok',
            'users.*.role.required' => 'Role wajib dipilih',
        ]);

        foreach ($validated['users'] as $data) {
            // Security check: non-superadmin cannot assign superadmin role
            if (!auth()->user()->hasRole('superadmin') && $data['role'] === 'superadmin') {
                return back()->with('error', 'Anda tidak memiliki akses untuk memberikan role SuperAdmin.')->withInput();
            }

            if (!auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'branch_id' => $data['branch_id'] ?? null,
            ]);

            $user->assignRole($data['role']);
        }

        return redirect()->route('user-management.index')
            ->with('success', count($validated['users']) . ' User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_unless(auth()->user()->can('edit user'), 403);

        $rolesQuery = Role::orderBy('name');
        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->where('name', '!=', 'superadmin');
        }
        $roles = $rolesQuery->get();
        $branches = \App\Models\Branch::orderBy('name')->get();
        $user->load('roles');

        return view('user-management.edit', compact('user', 'roles', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(auth()->user()->can('edit user'), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Security check: non-superadmin cannot assign superadmin role
        if (!auth()->user()->hasRole('superadmin') && $data['role'] === 'superadmin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk memberikan role SuperAdmin.')->withInput();
        }

        // Security check: non-superadmin cannot edit a superadmin user
        if (!auth()->user()->hasRole('superadmin') && $user->hasRole('superadmin')) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah data SuperAdmin.');
        }

        if (!auth()->user()->hasRole('superadmin') && auth()->user()->branch_id) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'branch_id' => $data['branch_id'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        $user->syncRoles([$data['role']]);

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil diperbarui.');
    }
    public function destroy(User $user)
    {
        abort_unless(auth()->user()->can('delete user'), 403);
        
        // Security check: non-superadmin cannot delete a superadmin user
        if (!auth()->user()->hasRole('superadmin') && $user->hasRole('superadmin')) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus data SuperAdmin.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        try {
            $user->delete();
        } catch (QueryException $exception) {
            return back()->with('error', 'User tidak dapat dihapus karena sudah digunakan pada data transaksi atau log.');
        }

        return redirect()->route('user-management.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
