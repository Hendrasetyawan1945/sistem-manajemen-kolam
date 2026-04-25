<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\Sesi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SesiController extends Controller
{
    /**
     * Display a listing of sessions for coach's classes.
     */
    public function index(Request $request)
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');

        $query = Sesi::with(['kelas', 'coach'])
                    ->whereIn('kelas_id', $coachKelasIds);

        if ($request->filled('kelas_id')) {
            // Pastikan kelas_id milik coach
            if ($coachKelasIds->contains($request->kelas_id)) {
                $query->where('kelas_id', $request->kelas_id);
            }
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $sesi = $query->orderBy('tanggal', 'desc')
                     ->orderBy('waktu_mulai', 'desc')
                     ->paginate(20);

        $kelasList = Kelas::where('coach_id', auth()->id())
                         ->where('is_active', true)
                         ->orderBy('nama')
                         ->get();

        return view('coach.sesi.index', compact('sesi', 'kelasList'));
    }

    /**
     * Show the form for creating a new session.
     */
    public function create(Request $request)
    {
        $kelasList = Kelas::where('coach_id', auth()->id())
                         ->where('is_active', true)
                         ->orderBy('nama')
                         ->get();

        $selectedKelasId = $request->get('kelas_id');

        return view('coach.sesi.create', compact('kelasList', 'selectedKelasId'));
    }

    /**
     * Store a newly created session.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'tanggal'      => 'required|date|before_or_equal:' . Carbon::now()->addDays(7)->format('Y-m-d'),
            'waktu_mulai'  => 'required|date_format:H:i',
            'waktu_selesai'=> 'required|date_format:H:i|after:waktu_mulai',
            'catatan'      => 'nullable|string|max:500',
        ], [
            'kelas_id.required'       => 'Kelas wajib dipilih',
            'kelas_id.exists'         => 'Kelas tidak valid',
            'tanggal.required'        => 'Tanggal wajib diisi',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari 7 hari ke depan',
            'waktu_mulai.required'    => 'Waktu mulai wajib diisi',
            'waktu_selesai.required'  => 'Waktu selesai wajib diisi',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai',
        ]);

        // Validasi kelas milik coach
        $kelas = Kelas::where('coach_id', auth()->id())
                     ->find($validated['kelas_id']);

        if (!$kelas) {
            return back()->withInput()
                        ->with('error', 'Anda tidak memiliki akses ke kelas tersebut');
        }

        $validated['coach_id'] = auth()->id();

        $sesi = Sesi::create($validated);

        // Auto-generate kehadiran records dengan status "alpha"
        $siswaList = Siswa::where('kelas_id', $kelas->id)
                         ->where('status', 'aktif')
                         ->get();

        foreach ($siswaList as $siswa) {
            Kehadiran::create([
                'sesi_id'  => $sesi->id,
                'siswa_id' => $siswa->id,
                'status'   => 'alpha',
            ]);
        }

        return redirect()->route('coach.sesi.index')
                        ->with('success', 'Sesi latihan berhasil dibuat dan kehadiran telah di-generate');
    }

    /**
     * Display the specified session with attendance list.
     */
    public function show($id)
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');

        $sesi = Sesi::whereIn('kelas_id', $coachKelasIds)
                   ->findOrFail($id);

        $sesi->load(['kelas', 'coach', 'kehadiran.siswa']);

        $totalSiswa      = $sesi->kehadiran->count();
        $hadir           = $sesi->kehadiran->where('status', 'hadir')->count();
        $izin            = $sesi->kehadiran->where('status', 'izin')->count();
        $sakit           = $sesi->kehadiran->where('status', 'sakit')->count();
        $alpha           = $sesi->kehadiran->where('status', 'alpha')->count();
        $persentaseHadir = $totalSiswa > 0 ? ($hadir / $totalSiswa) * 100 : 0;

        return view('coach.sesi.show', compact('sesi', 'totalSiswa', 'hadir', 'izin', 'sakit', 'alpha', 'persentaseHadir'));
    }

    /**
     * Update attendance for a session.
     */
    public function updateAttendance(Request $request, $id)
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');

        $sesi = Sesi::whereIn('kelas_id', $coachKelasIds)
                   ->findOrFail($id);

        $validated = $request->validate([
            'kehadiran'   => 'required|array',
            'kehadiran.*' => 'required|in:hadir,izin,sakit,alpha',
        ], [
            'kehadiran.required'   => 'Data kehadiran wajib diisi',
            'kehadiran.*.required' => 'Status kehadiran wajib dipilih',
            'kehadiran.*.in'       => 'Status kehadiran tidak valid',
        ]);

        foreach ($validated['kehadiran'] as $kehadiranId => $status) {
            Kehadiran::where('id', $kehadiranId)
                    ->where('sesi_id', $sesi->id)
                    ->update(['status' => $status]);
        }

        return redirect()->route('coach.sesi.show', $sesi->id)
                        ->with('success', 'Kehadiran berhasil diperbarui');
    }
}
