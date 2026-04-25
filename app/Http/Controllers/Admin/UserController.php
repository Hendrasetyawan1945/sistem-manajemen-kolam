<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q
                ->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
            );
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $users = $query->orderByRaw("CASE role WHEN 'admin' THEN 0 WHEN 'coach' THEN 1 ELSE 2 END")
                       ->orderBy('name')
                       ->paginate(20);

        $totalAdmin = User::where('role', 'admin')->count();
        $totalCoach = User::where('role', 'coach')->count();
        $totalSiswa = User::where('role', 'siswa')->count();

        return view('admin.users.index', compact('users', 'totalAdmin', 'totalCoach', 'totalSiswa'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,coach',
            'password' => ['required', 'confirmed', Password::min(8)],
            'telepon'  => 'nullable|string|min:10|max:15|regex:/^[0-9]+$/',
        ], [
            'name.required'     => 'Nama wajib diisi',
            'name.min'          => 'Nama minimal 3 karakter',
            'email.required'    => 'Email wajib diisi',
            'email.unique'      => 'Email sudah digunakan',
            'role.required'     => 'Role wajib dipilih',
            'password.required' => 'Password wajib diisi',
            'password.confirmed'=> 'Konfirmasi password tidak cocok',
            'password.min'      => 'Password minimal 8 karakter',
            'telepon.min'       => 'Telepon minimal 10 digit',
            'telepon.regex'     => 'Telepon hanya boleh berisi angka',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
            'telepon'   => $validated['telepon'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$validated['role']} berhasil dibuat untuk {$validated['name']}");
    }

    public function show(User $user)
    {
        $user->load('kelasAsCoach');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Tidak bisa edit akun sendiri dari sini
        if ($user->id === Auth::id()) {
            return redirect()->route('profile.edit')
                ->with('info', 'Untuk mengedit akun sendiri, gunakan halaman Profile.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'    => 'required|string|min:3|max:100',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|min:10|max:15|regex:/^[0-9]+$/',
        ], [
            'name.required'  => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique'   => 'Email sudah digunakan',
            'telepon.min'    => 'Telepon minimal 10 digit',
            'telepon.regex'  => 'Telepon hanya boleh berisi angka',
        ]);

        $user->update([
            'name'    => $validated['name'],
            'email'   => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
        ]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    /** Reset password pengguna */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.required'  => 'Password baru wajib diisi',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.min'       => 'Password minimal 8 karakter',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "Password {$user->name} berhasil direset");
    }

    /** Toggle aktif/nonaktif */
    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->name} berhasil {$status}");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }

        if ($user->role === 'siswa' && $user->siswa) {
            return back()->with('error', 'Akun siswa tidak bisa dihapus dari sini. Hapus melalui menu Siswa.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$user->name} berhasil dihapus");
    }
}
