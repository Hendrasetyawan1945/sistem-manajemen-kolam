<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::with(['coach', 'siswa'])
                     ->withCount('siswa')
                     ->orderBy('nama')
                     ->paginate(20);
        
        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $coaches = User::where('role', 'coach')
                      ->where('is_active', true)
                      ->orderBy('name')
                      ->get();
        
        return view('admin.kelas.create', compact('coaches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100|unique:kelas,nama',
            'jadwal' => 'required|string|max:200',
            'kapasitas' => 'required|integer|min:1',
            'biaya_bulanan' => 'required|numeric|min:0',
            'coach_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama kelas wajib diisi',
            'nama.unique' => 'Nama kelas sudah digunakan',
            'jadwal.required' => 'Jadwal kelas wajib diisi',
            'kapasitas.required' => 'Kapasitas kelas wajib diisi',
            'kapasitas.min' => 'Kapasitas minimal 1 siswa',
            'biaya_bulanan.required' => 'Biaya bulanan wajib diisi',
            'biaya_bulanan.min' => 'Biaya bulanan tidak boleh negatif',
            'coach_id.required' => 'Coach wajib dipilih',
            'coach_id.exists' => 'Coach tidak valid',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        Kelas::create($validated);
        
        return redirect()->route('admin.kelas.index')
                        ->with('success', 'Kelas berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kela)
    {
        $kela->load(['coach', 'siswa.user', 'sesi' => function($query) {
            $query->orderBy('tanggal', 'desc')->limit(10);
        }]);
        
        // Calculate class statistics
        $totalSesi = $kela->sesi()->count();
        $totalSiswa = $kela->siswa()->count();
        $siswaAktif = $kela->siswa()->where('status', 'aktif')->count();
        $kapasitasTerisi = $totalSiswa > 0 ? ($totalSiswa / $kela->kapasitas) * 100 : 0;
        
        return view('admin.kelas.show', compact('kela', 'totalSesi', 'totalSiswa', 'siswaAktif', 'kapasitasTerisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kela)
    {
        $coaches = User::where('role', 'coach')
                      ->where('is_active', true)
                      ->orderBy('name')
                      ->get();
        
        return view('admin.kelas.edit', compact('kela', 'coaches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', Rule::unique('kelas', 'nama')->ignore($kela->id)],
            'jadwal' => 'required|string|max:200',
            'kapasitas' => 'required|integer|min:1',
            'biaya_bulanan' => 'required|numeric|min:0',
            'coach_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama kelas wajib diisi',
            'nama.unique' => 'Nama kelas sudah digunakan',
            'jadwal.required' => 'Jadwal kelas wajib diisi',
            'kapasitas.required' => 'Kapasitas kelas wajib diisi',
            'kapasitas.min' => 'Kapasitas minimal 1 siswa',
            'biaya_bulanan.required' => 'Biaya bulanan wajib diisi',
            'biaya_bulanan.min' => 'Biaya bulanan tidak boleh negatif',
            'coach_id.required' => 'Coach wajib dipilih',
            'coach_id.exists' => 'Coach tidak valid',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $kela->update($validated);
        
        return redirect()->route('admin.kelas.index')
                        ->with('success', 'Kelas berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kela)
    {
        // Check if kelas has students
        if ($kela->siswa()->count() > 0) {
            return redirect()->route('admin.kelas.index')
                            ->with('error', 'Tidak dapat menghapus kelas yang masih memiliki siswa. Pindahkan siswa terlebih dahulu.');
        }
        
        $kela->delete();
        
        return redirect()->route('admin.kelas.index')
                        ->with('success', 'Kelas berhasil dihapus');
    }
}
