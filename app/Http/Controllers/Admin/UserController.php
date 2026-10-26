<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Tampilkan daftar user internal TVRI Kalsel.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        // Pencarian berdasarkan nama atau username
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan role
        if ($role = $request->input('role')) {
            if (in_array($role, ['admin', 'karyawan'])) {
                $query->where('role', $role);
            }
        }

        // Filter berdasarkan status aktif
        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', (bool) $request->input('status'));
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Statistik user
        $stats = [
            'total'     => User::count(),
            'admin'     => User::where('role', 'admin')->count(),
            'karyawan'  => User::where('role', 'karyawan')->count(),
            'non_aktif' => User::where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Form tambah akun user baru oleh Super Admin.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun user baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', 'unique:users,username'],
            'role'      => ['required', Rule::in(['admin', 'karyawan'])],
            'password'  => ['required', 'string', 'min:6'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda strip (-) dan garis bawah (_).',
            'username.unique'     => 'Username ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal terdiri dari 6 karakter.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['password']  = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('status', "Akun '{$validated['username']}' berhasil ditambahkan.");
    }

    /**
     * Form edit akun user.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui akun user & opsi reset password.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'username'  => ['required', 'string', 'alpha_dash', 'min:3', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'role'      => ['required', Rule::in(['admin', 'karyawan'])],
            'password'  => ['nullable', 'string', 'min:6'],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda strip (-) dan garis bawah (_).',
            'username.unique'     => 'Username ini sudah digunakan oleh akun lain.',
            'password.min'        => 'Password minimal terdiri dari 6 karakter jika ingin diubah.',
        ]);

        // Pencegahan: Super admin tidak boleh menonaktifkan atau mengubah rolenya sendiri menjadi karyawan jika hanya dia sendiri
        if ($user->id === auth()->id()) {
            $validated['role'] = 'admin';
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = $request->boolean('is_active', false);
        }

        // Jika password diisi, update password baru
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('status', "Data akun '{$user->username}' berhasil diperbarui.");
    }

    /**
     * Toggle status aktif/nonaktif user.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('status', "Akun '{$user->username}' berhasil {$statusText}.");
    }

    /**
     * Hapus akun user.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('status', "Akun '{$username}' berhasil dihapus.");
    }
}
