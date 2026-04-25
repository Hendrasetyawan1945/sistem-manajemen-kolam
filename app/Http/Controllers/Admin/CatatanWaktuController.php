<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\Siswa;
use App\Models\Kejuaraan;
use Illuminate\Http\Request;

class CatatanWaktuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CatatanWaktu::with(['siswa.kelas', 'kejuaraan']);
        
        // Filter by kejuaraan_id
        if ($request->filled('kejuaraan_id')) {
            $query->where('kejuaraan_id', $request->kejuaraan_id);
        }
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by gaya_renang
        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }
        
        // Filter by jarak
        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }
        
        $catatanWaktu = $query->orderBy('created_at', 'desc')
                             ->paginate(20);
        
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-waktu.index', compact('catatanWaktu', 'kejuaraanList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-waktu.create', compact('kejuaraanList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'gaya_renang' => 'required|string|max:50',
            'jarak' => 'required|integer|min:1',
            'catatan_waktu' => 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'posisi' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'kejuaraan_id.required' => 'Kejuaraan wajib dipilih',
            'gaya_renang.required' => 'Gaya renang wajib dipilih',
            'jarak.required' => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex' => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
            'posisi.min' => 'Posisi minimal 1',
        ]);
        
        // Generate nomor_lomba
        $validated['nomor_lomba'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';
        
        CatatanWaktu::create($validated);
        
        return redirect()->route('admin.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil ditambahkan dan Personal Best telah diperbarui');
    }

    /**
     * Display the specified resource.
     */
    public function show(CatatanWaktu $catatanWaktu)
    {
        $catatanWaktu->load(['siswa.kelas', 'kejuaraan']);
        
        return view('admin.catatan-waktu.show', compact('catatanWaktu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CatatanWaktu $catatanWaktu)
    {
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        // Gaya renang options
        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        
        // Jarak options
        $jarakList = [50, 100, 200, 400, 800, 1500];
        
        return view('admin.catatan-waktu.edit', compact('catatanWaktu', 'kejuaraanList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CatatanWaktu $catatanWaktu)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'gaya_renang' => 'required|string|max:50',
            'jarak' => 'required|integer|min:1',
            'catatan_waktu' => 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'posisi' => 'nullable|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'kejuaraan_id.required' => 'Kejuaraan wajib dipilih',
            'gaya_renang.required' => 'Gaya renang wajib dipilih',
            'jarak.required' => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex' => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
            'posisi.min' => 'Posisi minimal 1',
        ]);
        
        // Generate nomor_lomba
        $validated['nomor_lomba'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';
        
        $catatanWaktu->update($validated);
        
        return redirect()->route('admin.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil diperbarui dan Personal Best telah diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatatanWaktu $catatanWaktu)
    {
        $catatanWaktu->delete();
        
        return redirect()->route('admin.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil dihapus');
    }
}
