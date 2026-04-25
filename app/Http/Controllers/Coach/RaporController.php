<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Rapor;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RaporController extends Controller
{
    /**
     * Ambil daftar siswa_id yang ada di kelas coach.
     */
    private function getCoachSiswaIds()
    {
        $coachKelasIds = Kelas::where('coach_id', auth()->id())->pluck('id');
        return Siswa::whereIn('kelas_id', $coachKelasIds)->pluck('id');
    }

    /**
     * Ambil daftar kelas_id milik coach.
     */
    private function getCoachKelasIds()
    {
        return Kelas::where('coach_id', auth()->id())->pluck('id');
    }

    /**
     * Display a listing of report cards for coach's students.
     */
    public function index(Request $request)
    {
        $siswaIds = $this->getCoachSiswaIds();

        $query = Rapor::with(['siswa.kelas', 'coach'])
                     ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('siswa_id')) {
            if ($siswaIds->contains($request->siswa_id)) {
                $query->where('siswa_id', $request->siswa_id);
            }
        }

        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rapor = $query->orderBy('periode', 'desc')
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        $kelasIds  = $this->getCoachKelasIds();
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->orderBy('nama')->get();

        return view('coach.rapor.index', compact('rapor', 'siswaList'));
    }

    /**
     * Show the form for creating a new report card.
     */
    public function create()
    {
        $kelasIds  = $this->getCoachKelasIds();
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
                         ->where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();

        return view('coach.rapor.create', compact('siswaList'));
    }

    /**
     * Store a newly created report card.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'         => 'required|exists:siswa,id',
            'periode'          => 'required|regex:/^\d{4}-\d{2}$/',
            'teknik_renang'    => 'required|integer|min:1|max:10',
            'kondisi_fisik'    => 'required|integer|min:1|max:10',
            'kedisiplinan'     => 'required|integer|min:1|max:10',
            'semangat_berlatih'=> 'required|integer|min:1|max:10',
            'catatan_coach'    => 'nullable|string',
            'status'           => 'required|in:draft,final',
        ], [
            'siswa_id.required'          => 'Siswa wajib dipilih',
            'periode.required'           => 'Periode wajib diisi',
            'periode.regex'              => 'Format periode harus YYYY-MM (contoh: 2024-01)',
            'teknik_renang.required'     => 'Nilai teknik renang wajib diisi',
            'teknik_renang.min'          => 'Nilai minimal 1',
            'teknik_renang.max'          => 'Nilai maksimal 10',
            'kondisi_fisik.required'     => 'Nilai kondisi fisik wajib diisi',
            'kondisi_fisik.min'          => 'Nilai minimal 1',
            'kondisi_fisik.max'          => 'Nilai maksimal 10',
            'kedisiplinan.required'      => 'Nilai kedisiplinan wajib diisi',
            'kedisiplinan.min'           => 'Nilai minimal 1',
            'kedisiplinan.max'           => 'Nilai maksimal 10',
            'semangat_berlatih.required' => 'Nilai semangat berlatih wajib diisi',
            'semangat_berlatih.min'      => 'Nilai minimal 1',
            'semangat_berlatih.max'      => 'Nilai maksimal 10',
            'status.required'            => 'Status wajib dipilih',
        ]);

        // Validasi siswa di kelas coach
        $siswaIds = $this->getCoachSiswaIds();
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        // Cek duplikat per siswa/periode
        $exists = Rapor::where('siswa_id', $validated['siswa_id'])
                      ->where('periode', $validated['periode'])
                      ->exists();

        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Rapor untuk siswa ini pada periode tersebut sudah ada');
        }

        $validated['coach_id'] = auth()->id();

        Rapor::create($validated);

        return redirect()->route('coach.rapor.index')
                        ->with('success', 'Rapor berhasil ditambahkan');
    }

    /**
     * Display the specified report card.
     */
    public function show($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $rapor = Rapor::whereIn('siswa_id', $siswaIds)->findOrFail($id);
        $rapor->load(['siswa.kelas', 'coach']);

        return view('coach.rapor.show', compact('rapor'));
    }

    /**
     * Show the form for editing the specified report card.
     */
    public function edit($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $rapor = Rapor::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $kelasIds  = $this->getCoachKelasIds();
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
                         ->where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();

        return view('coach.rapor.edit', compact('rapor', 'siswaList'));
    }

    /**
     * Update the specified report card.
     */
    public function update(Request $request, $id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $rapor = Rapor::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $validated = $request->validate([
            'siswa_id'         => 'required|exists:siswa,id',
            'periode'          => 'required|regex:/^\d{4}-\d{2}$/',
            'teknik_renang'    => 'required|integer|min:1|max:10',
            'kondisi_fisik'    => 'required|integer|min:1|max:10',
            'kedisiplinan'     => 'required|integer|min:1|max:10',
            'semangat_berlatih'=> 'required|integer|min:1|max:10',
            'catatan_coach'    => 'nullable|string',
            'status'           => 'required|in:draft,final',
        ], [
            'siswa_id.required'          => 'Siswa wajib dipilih',
            'periode.required'           => 'Periode wajib diisi',
            'periode.regex'              => 'Format periode harus YYYY-MM (contoh: 2024-01)',
            'teknik_renang.required'     => 'Nilai teknik renang wajib diisi',
            'teknik_renang.min'          => 'Nilai minimal 1',
            'teknik_renang.max'          => 'Nilai maksimal 10',
            'kondisi_fisik.required'     => 'Nilai kondisi fisik wajib diisi',
            'kondisi_fisik.min'          => 'Nilai minimal 1',
            'kondisi_fisik.max'          => 'Nilai maksimal 10',
            'kedisiplinan.required'      => 'Nilai kedisiplinan wajib diisi',
            'kedisiplinan.min'           => 'Nilai minimal 1',
            'kedisiplinan.max'           => 'Nilai maksimal 10',
            'semangat_berlatih.required' => 'Nilai semangat berlatih wajib diisi',
            'semangat_berlatih.min'      => 'Nilai minimal 1',
            'semangat_berlatih.max'      => 'Nilai maksimal 10',
            'status.required'            => 'Status wajib dipilih',
        ]);

        // Validasi siswa di kelas coach
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        // Cek duplikat (kecuali record ini sendiri)
        $exists = Rapor::where('siswa_id', $validated['siswa_id'])
                      ->where('periode', $validated['periode'])
                      ->where('id', '!=', $rapor->id)
                      ->exists();

        if ($exists) {
            return back()->withInput()
                        ->with('error', 'Rapor untuk siswa ini pada periode tersebut sudah ada');
        }

        $rapor->update($validated);

        return redirect()->route('coach.rapor.index')
                        ->with('success', 'Rapor berhasil diperbarui');
    }
}
