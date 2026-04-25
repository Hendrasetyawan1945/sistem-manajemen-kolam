<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sesi;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Sesi::with(['kelas', 'coach']);
        
        // Filter by kelas_id
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }
        
        $sesi = $query->orderBy('tanggal', 'desc')
                     ->orderBy('waktu_mulai', 'desc')
                     ->paginate(20);
        
        $kelasList = Kelas::where('is_active', true)->orderBy('nama')->get();
        
        return view('admin.sesi.index', compact('sesi', 'kelasList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kelasList = Kelas::where('is_active', true)
                         ->with('coach')
                         ->orderBy('nama')
                         ->get();
        
        $selectedKelasId = $request->get('kelas_id');
        
        return view('admin.sesi.create', compact('kelasList', 'selectedKelasId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date|after_or_equal:today|before_or_equal:' . Carbon::now()->addDays(7)->format('Y-m-d'),
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'catatan' => 'nullable|string|max:500',
        ], [
            'kelas_id.required' => 'Kelas wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh sebelum hari ini',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari 7 hari ke depan',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
        ]);
        
        // Get coach_id from kelas
        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $validated['coach_id'] = $kelas->coach_id;
        
        // Create sesi
        $sesi = Sesi::create($validated);
        
        // Auto-generate kehadiran records dengan status "alpha"
        $siswaList = Siswa::where('kelas_id', $kelas->id)
                         ->where('status', 'aktif')
                         ->get();
        
        foreach ($siswaList as $siswa) {
            Kehadiran::create([
                'sesi_id' => $sesi->id,
                'siswa_id' => $siswa->id,
                'status' => 'alpha',
            ]);
        }
        
        return redirect()->route('admin.sesi.index')
                        ->with('success', 'Sesi latihan berhasil dibuat dan kehadiran telah di-generate');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sesi $sesi)
    {
        $sesi->load(['kelas', 'coach', 'kehadiran.siswa']);
        
        // Calculate attendance statistics
        $totalSiswa = $sesi->kehadiran->count();
        $hadir = $sesi->kehadiran->where('status', 'hadir')->count();
        $izin = $sesi->kehadiran->where('status', 'izin')->count();
        $sakit = $sesi->kehadiran->where('status', 'sakit')->count();
        $alpha = $sesi->kehadiran->where('status', 'alpha')->count();
        $persentaseHadir = $totalSiswa > 0 ? ($hadir / $totalSiswa) * 100 : 0;
        
        return view('admin.sesi.show', compact('sesi', 'totalSiswa', 'hadir', 'izin', 'sakit', 'alpha', 'persentaseHadir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sesi $sesi)
    {
        $kelasList = Kelas::where('is_active', true)
                         ->with('coach')
                         ->orderBy('nama')
                         ->get();
        
        return view('admin.sesi.edit', compact('sesi', 'kelasList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sesi $sesi)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'catatan' => 'nullable|string|max:500',
        ], [
            'kelas_id.required' => 'Kelas wajib dipilih',
            'tanggal.required' => 'Tanggal wajib diisi',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai',
        ]);
        
        // Get coach_id from kelas
        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $validated['coach_id'] = $kelas->coach_id;
        
        $sesi->update($validated);
        
        return redirect()->route('admin.sesi.index')
                        ->with('success', 'Sesi latihan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sesi $sesi)
    {
        $sesi->delete();
        
        return redirect()->route('admin.sesi.index')
                        ->with('success', 'Sesi latihan berhasil dihapus');
    }
    
    /**
     * Show attendance form for a session
     */
    public function attendance(Sesi $sesi)
    {
        $sesi->load(['kelas', 'kehadiran.siswa']);
        
        return view('admin.sesi.attendance', compact('sesi'));
    }
    
    /**
     * Update attendance for a session
     */
    public function updateAttendance(Request $request, Sesi $sesi)
    {
        $validated = $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*' => 'required|in:hadir,izin,sakit,alpha',
        ]);
        
        foreach ($validated['kehadiran'] as $kehadiranId => $status) {
            Kehadiran::where('id', $kehadiranId)
                    ->where('sesi_id', $sesi->id)
                    ->update(['status' => $status]);
        }
        
        return redirect()->route('admin.sesi.show', $sesi->id)
                        ->with('success', 'Kehadiran berhasil diperbarui');
    }
}
