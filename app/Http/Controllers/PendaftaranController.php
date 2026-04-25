<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PendaftaranController extends Controller
{
    /** Halaman form pendaftaran publik */
    public function create()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        return view('pendaftaran.create', compact('kelasList'));
    }

    /** Simpan pendaftaran */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|min:3|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'required|string|max:500',
            'nama_ortu'     => 'required|string|min:3|max:100',
            'telepon_ortu'  => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'email_ortu'    => 'nullable|email|max:100',
            'kelas_id'      => 'nullable|exists:kelas,id',
            'email'         => 'required|email|max:100|unique:users,email|unique:pendaftaran,email',
            'password'      => ['required', 'confirmed', Password::min(8)],
        ], [
            'nama.required'          => 'Nama lengkap wajib diisi',
            'nama.min'               => 'Nama minimal 3 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'alamat.required'        => 'Alamat wajib diisi',
            'nama_ortu.required'     => 'Nama orang tua wajib diisi',
            'telepon_ortu.required'  => 'Nomor telepon orang tua wajib diisi',
            'telepon_ortu.min'       => 'Nomor telepon minimal 10 digit',
            'telepon_ortu.regex'     => 'Nomor telepon hanya boleh berisi angka',
            'email.required'         => 'Email wajib diisi',
            'email.unique'           => 'Email sudah terdaftar, gunakan email lain',
            'password.required'      => 'Password wajib diisi',
            'password.confirmed'     => 'Konfirmasi password tidak cocok',
            'password.min'           => 'Password minimal 8 karakter',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status']   = 'menunggu';

        Pendaftaran::create($validated);

        return redirect()->route('pendaftaran.sukses');
    }

    /** Halaman sukses setelah daftar */
    public function sukses()
    {
        return view('pendaftaran.sukses');
    }

    /** Cek status pendaftaran */
    public function cekStatus(Request $request)
    {
        $pendaftaran = null;

        if ($request->filled('email')) {
            $request->validate(['email' => 'required|email'], [
                'email.required' => 'Email wajib diisi',
                'email.email'    => 'Format email tidak valid',
            ]);
            $pendaftaran = Pendaftaran::where('email', $request->email)->first();
        }

        return view('pendaftaran.cek-status', compact('pendaftaran'));
    }
}
