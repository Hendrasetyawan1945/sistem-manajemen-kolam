<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Angsuran;
use App\Models\Siswa;
use App\Models\MetodePembayaran;
use App\Models\DetailAngsuran;
use Illuminate\Http\Request;

class AngsuranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Angsuran::with(['siswa.kelas', 'dibuatOleh']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $angsuran = $query->orderBy('created_at', 'desc')
                         ->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        // Calculate totals
        $totalAktif = Angsuran::where('status', 'aktif')->sum('sisa');
        $totalLunas = Angsuran::where('status', 'lunas')->sum('total_tagihan');
        
        return view('admin.angsuran.index', compact('angsuran', 'siswaList', 'totalAktif', 'totalLunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        return view('admin.angsuran.create', compact('siswaList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'keterangan' => 'required|string|max:200',
            'total_tagihan' => 'required|numeric|min:0.01',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 200 karakter',
            'total_tagihan.required' => 'Total tagihan wajib diisi',
            'total_tagihan.min' => 'Total tagihan harus lebih dari 0',
        ]);
        
        // Add additional fields
        $validated['total_dibayar'] = 0;
        $validated['sisa'] = $validated['total_tagihan'];
        $validated['status'] = 'aktif';
        $validated['dibuat_oleh'] = auth()->id();
        
        Angsuran::create($validated);
        
        return redirect()->route('admin.angsuran.index')
                        ->with('success', 'Angsuran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Angsuran $angsuran)
    {
        $angsuran->load(['siswa.kelas', 'dibuatOleh', 'detailAngsuran.metodePembayaran', 'detailAngsuran.dibuatOleh']);
        
        $metodePembayaran = MetodePembayaran::orderBy('nama')->get();
        
        return view('admin.angsuran.show', compact('angsuran', 'metodePembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Angsuran $angsuran)
    {
        $siswaList = Siswa::where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();
        
        return view('admin.angsuran.edit', compact('angsuran', 'siswaList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Angsuran $angsuran)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'keterangan' => 'required|string|max:200',
            'total_tagihan' => 'required|numeric|min:0.01',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'keterangan.required' => 'Keterangan wajib diisi',
            'keterangan.max' => 'Keterangan maksimal 200 karakter',
            'total_tagihan.required' => 'Total tagihan wajib diisi',
            'total_tagihan.min' => 'Total tagihan harus lebih dari 0',
        ]);
        
        $angsuran->update($validated);
        
        // Recalculate sisa
        $angsuran->updateSisa();
        
        return redirect()->route('admin.angsuran.index')
                        ->with('success', 'Angsuran berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Angsuran $angsuran)
    {
        $angsuran->delete();
        
        return redirect()->route('admin.angsuran.index')
                        ->with('success', 'Angsuran berhasil dihapus');
    }

    /**
     * Add payment to angsuran
     */
    public function addPayment(Request $request, Angsuran $angsuran)
    {
        $validated = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0.01|max:' . $angsuran->sisa,
            'tanggal_bayar' => 'required|date|before_or_equal:today',
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
            'catatan' => 'nullable|string|max:255',
        ], [
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi',
            'jumlah_bayar.min' => 'Jumlah bayar harus lebih dari 0',
            'jumlah_bayar.max' => 'Jumlah bayar tidak boleh melebihi sisa tagihan',
            'tanggal_bayar.required' => 'Tanggal bayar wajib diisi',
            'tanggal_bayar.before_or_equal' => 'Tanggal bayar tidak boleh lebih dari hari ini',
            'metode_pembayaran_id.required' => 'Metode pembayaran wajib dipilih',
        ]);
        
        // Create detail angsuran
        DetailAngsuran::create([
            'angsuran_id' => $angsuran->id,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'tanggal_bayar' => $validated['tanggal_bayar'],
            'metode_pembayaran_id' => $validated['metode_pembayaran_id'],
            'catatan' => $validated['catatan'] ?? null,
            'dibuat_oleh' => auth()->id(),
        ]);
        
        // Update angsuran (will be handled by DetailAngsuran boot event)
        
        return redirect()->route('admin.angsuran.show', $angsuran)
                        ->with('success', 'Pembayaran berhasil ditambahkan');
    }

    /**
     * Delete payment from angsuran
     */
    public function deletePayment(Angsuran $angsuran, DetailAngsuran $detail)
    {
        // Verify detail belongs to angsuran
        if ($detail->angsuran_id !== $angsuran->id) {
            return redirect()->route('admin.angsuran.show', $angsuran)
                            ->with('error', 'Detail pembayaran tidak valid');
        }
        
        $detail->delete();
        
        return redirect()->route('admin.angsuran.show', $angsuran)
                        ->with('success', 'Pembayaran berhasil dihapus');
    }
}
