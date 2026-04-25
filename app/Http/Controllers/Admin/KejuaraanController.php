<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kejuaraan;
use App\Models\IuranKejuaraan;
use Illuminate\Http\Request;

class KejuaraanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kejuaraan = Kejuaraan::withCount('iuranKejuaraan')
                             ->orderBy('tanggal_mulai', 'desc')
                             ->paginate(20);
        
        return view('admin.kejuaraan.index', compact('kejuaraan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kejuaraan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:200',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama kejuaraan wajib diisi',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'lokasi.required' => 'Lokasi wajib diisi',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi',
            'biaya_pendaftaran.min' => 'Biaya pendaftaran tidak boleh negatif',
        ]);
        
        Kejuaraan::create($validated);
        
        return redirect()->route('admin.kejuaraan.index')
                        ->with('success', 'Kejuaraan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kejuaraan $kejuaraan)
    {
        $kejuaraan->load(['iuranKejuaraan.siswa.kelas', 'iuranKejuaraan.metodePembayaran']);
        
        // Calculate statistics
        $iuranKejuaraan = $kejuaraan->iuranKejuaraan;
        $totalPeserta = $iuranKejuaraan->count();
        $totalLunas = $iuranKejuaraan->where('status_bayar', 'lunas')->count();
        $totalBelum = $iuranKejuaraan->where('status_bayar', 'belum')->count();
        $totalTerkumpul = $iuranKejuaraan->where('status_bayar', 'lunas')->sum('jumlah');
        $totalBelumBayar = $iuranKejuaraan->where('status_bayar', 'belum')->sum('jumlah');
        
        return view('admin.kejuaraan.show', compact(
            'kejuaraan',
            'iuranKejuaraan',
            'totalPeserta',
            'totalLunas',
            'totalBelum',
            'totalTerkumpul',
            'totalBelumBayar'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kejuaraan $kejuaraan)
    {
        return view('admin.kejuaraan.edit', compact('kejuaraan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kejuaraan $kejuaraan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:200',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:200',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ], [
            'nama.required' => 'Nama kejuaraan wajib diisi',
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai',
            'lokasi.required' => 'Lokasi wajib diisi',
            'biaya_pendaftaran.required' => 'Biaya pendaftaran wajib diisi',
            'biaya_pendaftaran.min' => 'Biaya pendaftaran tidak boleh negatif',
        ]);
        
        $kejuaraan->update($validated);
        
        return redirect()->route('admin.kejuaraan.index')
                        ->with('success', 'Kejuaraan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kejuaraan $kejuaraan)
    {
        $kejuaraan->delete();
        
        return redirect()->route('admin.kejuaraan.index')
                        ->with('success', 'Kejuaraan berhasil dihapus');
    }
}
