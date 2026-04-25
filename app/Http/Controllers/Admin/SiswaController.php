<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['kelas', 'user']);
        
        // Search by nama atau nama_orang_tua
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nama_orang_tua', 'like', "%{$search}%");
            });
        }
        
        // Filter by kelas_id
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $siswa = $query->orderBy('nama')->paginate(20);
        $kelasList = Kelas::orderBy('nama')->get();
        
        return view('admin.siswa.index', compact('siswa', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        return view('admin.siswa.create', compact('kelasList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:100',
            'telepon_orang_tua' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:aktif,cuti,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama siswa wajib diisi',
            'nama.min' => 'Nama siswa minimal 3 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'alamat.required' => 'Alamat wajib diisi',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi',
            'telepon_orang_tua.required' => 'Telepon orang tua wajib diisi',
            'telepon_orang_tua.min' => 'Telepon orang tua minimal 10 digit',
            'telepon_orang_tua.max' => 'Telepon orang tua maksimal 15 digit',
            'telepon_orang_tua.regex' => 'Telepon orang tua hanya boleh berisi angka',
            'status.required' => 'Status wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Generate unique email
        $baseEmail = strtolower(str_replace(' ', '.', $validated['nama'])) . '@siswa.com';
        $email = $baseEmail;
        $counter = 1;
        while (User::where('email', $email)->exists()) {
            $email = strtolower(str_replace(' ', '.', $validated['nama'])) . $counter . '@siswa.com';
            $counter++;
        }

        // Create user account for siswa
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $email,
            'password' => bcrypt('password123'),
            'role' => 'siswa',
            'is_active' => $validated['status'] === 'aktif',
        ]);

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $fotoPath = $file->storeAs('siswa', $filename, 'public');
        }

        Siswa::create([
            'user_id'        => $user->id,
            'kelas_id'       => $validated['kelas_id'] ?? null,
            'nama'           => $validated['nama'],
            'tanggal_lahir'  => $validated['tanggal_lahir'],
            'jenis_kelamin'  => $validated['jenis_kelamin'],
            'alamat'         => $validated['alamat'],
            'nama_ortu'      => $validated['nama_orang_tua'],
            'telepon_ortu'   => $validated['telepon_orang_tua'],
            'status'         => $validated['status'],
            'foto'           => $fotoPath,
            'tanggal_daftar' => now()->toDateString(),
        ]);

        return redirect()->route('admin.siswa.index')
                        ->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'user', 'kehadiran.sesi', 'iuranRutin', 'iuranInsidentil', 'personalBest']);
        
        // Calculate attendance percentage
        $totalSesi = $siswa->kehadiran()->count();
        $hadirCount = $siswa->kehadiran()->where('status', 'hadir')->count();
        $attendancePercentage = $totalSesi > 0 ? ($hadirCount / $totalSesi) * 100 : 0;
        
        return view('admin.siswa.show', compact('siswa', 'attendancePercentage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        return view('admin.siswa.edit', compact('siswa', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:255',
            'nama_orang_tua' => 'required|string|max:100',
            'telepon_orang_tua' => 'required|string|min:10|max:15|regex:/^[0-9]+$/',
            'kelas_id' => 'nullable|exists:kelas,id',
            'status' => 'required|in:aktif,cuti,nonaktif',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama.required' => 'Nama siswa wajib diisi',
            'nama.min' => 'Nama siswa minimal 3 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'alamat.required' => 'Alamat wajib diisi',
            'nama_orang_tua.required' => 'Nama orang tua wajib diisi',
            'telepon_orang_tua.required' => 'Telepon orang tua wajib diisi',
            'telepon_orang_tua.min' => 'Telepon orang tua minimal 10 digit',
            'telepon_orang_tua.max' => 'Telepon orang tua maksimal 15 digit',
            'telepon_orang_tua.regex' => 'Telepon orang tua hanya boleh berisi angka',
            'status.required' => 'Status wajib dipilih',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);
        
        // Update user account
        $siswa->user->update([
            'name' => $validated['nama'],
            'is_active' => $validated['status'] === 'aktif',
        ]);

        // Handle foto upload
        $updateData = [
            'kelas_id'      => $validated['kelas_id'] ?? null,
            'nama'          => $validated['nama'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat'        => $validated['alamat'],
            'nama_ortu'     => $validated['nama_orang_tua'],
            'telepon_ortu'  => $validated['telepon_orang_tua'],
            'status'        => $validated['status'],
        ];

        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $updateData['foto'] = $file->storeAs('siswa', $filename, 'public');
        }

        $siswa->update($updateData);

        return redirect()->route('admin.siswa.index')
                        ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // Delete foto if exists
        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }
        
        // Delete user account
        $siswa->user->delete();
        
        // Delete siswa (cascade will handle related records)
        $siswa->delete();
        
        return redirect()->route('admin.siswa.index')
                        ->with('success', 'Data siswa berhasil dihapus');
    }
}
