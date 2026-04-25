<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanLatihan;
use App\Models\Siswa;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanLatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CatatanLatihan::with(['siswa.kelas', 'sesi.kelas', 'coach']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by date range
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('sesi', function($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }
        
        // Filter by gaya_renang
        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }
        
        // Filter by jarak
        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }
        
        $catatanLatihan = $query->orderBy('created_at', 'desc')
                               ->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-latihan.index', compact('catatanLatihan', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sesiList = Sesi::with('kelas')
                       ->orderBy('tanggal', 'desc')
                       ->orderBy('waktu_mulai', 'desc')
                       ->get();
        
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-latihan.create', compact('sesiList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'sesi_id' => 'required|exists:sesi,id',
            'gaya_renang' => 'required|string|max:50',
            'jarak' => 'required|integer|min:1',
            'catatan_waktu' => 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'catatan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'sesi_id.required' => 'Sesi latihan wajib dipilih',
            'gaya_renang.required' => 'Gaya renang wajib dipilih',
            'jarak.required' => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex' => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
        ]);
        
        // Generate nomor_latihan
        $validated['nomor_latihan'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';
        
        // Set coach_id from authenticated user
        $validated['coach_id'] = auth()->id();
        
        CatatanLatihan::create($validated);
        
        return redirect()->route('admin.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(CatatanLatihan $catatanLatihan)
    {
        $catatanLatihan->load(['siswa.kelas', 'sesi.kelas', 'coach']);
        
        return view('admin.catatan-latihan.show', compact('catatanLatihan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CatatanLatihan $catatanLatihan)
    {
        $sesiList = Sesi::with('kelas')
                       ->orderBy('tanggal', 'desc')
                       ->orderBy('waktu_mulai', 'desc')
                       ->get();
        
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-latihan.edit', compact('catatanLatihan', 'sesiList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatatanLatihan $catatanLatihan)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'sesi_id' => 'required|exists:sesi,id',
            'gaya_renang' => 'required|string|max:50',
            'jarak' => 'required|integer|min:1',
            'catatan_waktu' => 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'catatan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'sesi_id.required' => 'Sesi latihan wajib dipilih',
            'gaya_renang.required' => 'Gaya renang wajib dipilih',
            'jarak.required' => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex' => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
        ]);
        
        // Generate nomor_latihan
        $validated['nomor_latihan'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';
        
        $catatanLatihan->update($validated);
        
        return redirect()->route('admin.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatatanLatihan $catatanLatihan)
    {
        $catatanLatihan->delete();
        
        return redirect()->route('admin.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil dihapus');
    }

    /**
     * Display analytics and trends
     */
    public function analytics(Request $request)
    {
        $siswaId = $request->input('siswa_id');
        $gayaRenang = $request->input('gaya_renang');
        $jarak = $request->input('jarak');
        
        if (!$siswaId || !$gayaRenang || !$jarak) {
            return redirect()->route('admin.catatan-latihan.index')
                           ->with('error', 'Silakan pilih siswa, gaya renang, dan jarak untuk melihat analitik');
        }
        
        // Get training history
        $catatanLatihan = CatatanLatihan::where([
            'siswa_id' => $siswaId,
            'gaya_renang' => $gayaRenang,
            'jarak' => $jarak,
        ])->with('sesi')
          ->orderBy('created_at', 'asc')
          ->get();
        
        // Calculate average time
        $totalSeconds = 0;
        foreach ($catatanLatihan as $catatan) {
            $totalSeconds += $catatan->waktu_in_seconds;
        }
        $averageSeconds = $catatanLatihan->count() > 0 ? $totalSeconds / $catatanLatihan->count() : 0;
        
        // Convert back to MM:SS.MS format
        $minutes = floor($averageSeconds / 60);
        $seconds = floor($averageSeconds % 60);
        $milliseconds = round(($averageSeconds - floor($averageSeconds)) * 100);
        $averageTime = sprintf('%02d:%02d.%02d', $minutes, $seconds, $milliseconds);
        
        // Get siswa info
        $siswa = Siswa::find($siswaId);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-latihan.analytics', compact(
            'catatanLatihan', 
            'averageTime', 
            'siswa', 
            'gayaRenang', 
            'jarak',
            'siswaList',
            'gayaRenangList',
            'jarakList'
        ));
    }
}
