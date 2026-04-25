<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rapor;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RaporController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Rapor::with(['siswa.kelas', 'coach']);
        
        // Filter by siswa_id
        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter by periode
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $rapor = $query->orderBy('periode', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);
        
        $siswaList = Siswa::where('status', 'aktif')->orderBy('nama')->get();
        
        return view('admin.rapor.index', compact('rapor', 'siswaList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        return view('admin.rapor.create', compact('siswaList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'teknik_renang' => 'required|integer|min:1|max:10',
            'kondisi_fisik' => 'required|integer|min:1|max:10',
            'kedisiplinan' => 'required|integer|min:1|max:10',
            'semangat_berlatih' => 'required|integer|min:1|max:10',
            'catatan_coach' => 'nullable|string',
            'status' => 'required|in:draft,final',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'periode.required' => 'Periode wajib diisi',
            'periode.regex' => 'Format periode harus YYYY-MM (contoh: 2024-01)',
            'teknik_renang.required' => 'Nilai teknik renang wajib diisi',
            'teknik_renang.min' => 'Nilai minimal 1',
            'teknik_renang.max' => 'Nilai maksimal 10',
            'kondisi_fisik.required' => 'Nilai kondisi fisik wajib diisi',
            'kondisi_fisik.min' => 'Nilai minimal 1',
            'kondisi_fisik.max' => 'Nilai maksimal 10',
            'kedisiplinan.required' => 'Nilai kedisiplinan wajib diisi',
            'kedisiplinan.min' => 'Nilai minimal 1',
            'kedisiplinan.max' => 'Nilai maksimal 10',
            'semangat_berlatih.required' => 'Nilai semangat berlatih wajib diisi',
            'semangat_berlatih.min' => 'Nilai minimal 1',
            'semangat_berlatih.max' => 'Nilai maksimal 10',
            'status.required' => 'Status wajib dipilih',
        ]);
        
        // Check for duplicate
        $exists = Rapor::where('siswa_id', $validated['siswa_id'])
                      ->where('periode', $validated['periode'])
                      ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Rapor untuk siswa ini pada periode tersebut sudah ada');
        }
        
        // Set coach_id
        $validated['coach_id'] = auth()->id();
        
        Rapor::create($validated);
        
        return redirect()->route('admin.rapor.index')
                        ->with('success', 'Rapor berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rapor $rapor)
    {
        $rapor->load(['siswa.kelas', 'coach']);
        
        return view('admin.rapor.show', compact('rapor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rapor $rapor)
    {
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        
        return view('admin.rapor.edit', compact('rapor', 'siswaList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rapor $rapor)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'periode' => 'required|regex:/^\d{4}-\d{2}$/',
            'teknik_renang' => 'required|integer|min:1|max:10',
            'kondisi_fisik' => 'required|integer|min:1|max:10',
            'kedisiplinan' => 'required|integer|min:1|max:10',
            'semangat_berlatih' => 'required|integer|min:1|max:10',
            'catatan_coach' => 'nullable|string',
            'status' => 'required|in:draft,final',
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih',
            'periode.required' => 'Periode wajib diisi',
            'periode.regex' => 'Format periode harus YYYY-MM (contoh: 2024-01)',
            'teknik_renang.required' => 'Nilai teknik renang wajib diisi',
            'teknik_renang.min' => 'Nilai minimal 1',
            'teknik_renang.max' => 'Nilai maksimal 10',
            'kondisi_fisik.required' => 'Nilai kondisi fisik wajib diisi',
            'kondisi_fisik.min' => 'Nilai minimal 1',
            'kondisi_fisik.max' => 'Nilai maksimal 10',
            'kedisiplinan.required' => 'Nilai kedisiplinan wajib diisi',
            'kedisiplinan.min' => 'Nilai minimal 1',
            'kedisiplinan.max' => 'Nilai maksimal 10',
            'semangat_berlatih.required' => 'Nilai semangat berlatih wajib diisi',
            'semangat_berlatih.min' => 'Nilai minimal 1',
            'semangat_berlatih.max' => 'Nilai maksimal 10',
            'status.required' => 'Status wajib dipilih',
        ]);
        
        // Check for duplicate (excluding current record)
        $exists = Rapor::where('siswa_id', $validated['siswa_id'])
                      ->where('periode', $validated['periode'])
                      ->where('id', '!=', $rapor->id)
                      ->exists();
        
        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Rapor untuk siswa ini pada periode tersebut sudah ada');
        }
        
        $rapor->update($validated);
        
        return redirect()->route('admin.rapor.index')
                        ->with('success', 'Rapor berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rapor $rapor)
    {
        $rapor->delete();
        
        return redirect()->route('admin.rapor.index')
                        ->with('success', 'Rapor berhasil dihapus');
    }

    /**
     * Export rapor siswa ke PDF
     */
    public function pdf(Rapor $rapor)
    {
        $rapor->load(['siswa.kelas', 'coach']);

        $namaKlub    = config('app.name', 'Klub Renang');
        $tanggalCetak = now()->toDateString();

        $filename = 'rapor-' . str_replace(' ', '-', strtolower($rapor->siswa->nama))
            . '-' . $rapor->periode . '.pdf';

        return Pdf::loadView('admin.laporan.pdf.rapor', compact('rapor', 'namaKlub', 'tanggalCetak'))
            ->setPaper('a4', 'portrait')
            ->download($filename);
    }
}
