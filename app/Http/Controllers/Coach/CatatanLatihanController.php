<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CatatanLatihan;
use App\Models\Kelas;
use App\Models\Sesi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class CatatanLatihanController extends Controller
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
     * Display a listing of training records for coach's students.
     */
    public function index(Request $request)
    {
        $siswaIds = $this->getCoachSiswaIds();

        $query = CatatanLatihan::with(['siswa.kelas', 'sesi.kelas', 'coach'])
                              ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('siswa_id')) {
            if ($siswaIds->contains($request->siswa_id)) {
                $query->where('siswa_id', $request->siswa_id);
            }
        }

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereHas('sesi', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
            });
        }

        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }

        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }

        $catatanLatihan = $query->orderBy('created_at', 'desc')->paginate(20);

        $kelasIds  = $this->getCoachKelasIds();
        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
                         ->where('status', 'aktif')
                         ->orderBy('nama')
                         ->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-latihan.index', compact('catatanLatihan', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Show the form for creating a new training record.
     */
    public function create()
    {
        $kelasIds = $this->getCoachKelasIds();

        $sesiList = Sesi::whereIn('kelas_id', $kelasIds)
                       ->with('kelas')
                       ->orderBy('tanggal', 'desc')
                       ->orderBy('waktu_mulai', 'desc')
                       ->get();

        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
                         ->where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-latihan.create', compact('sesiList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Store a newly created training record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'sesi_id'      => 'required|exists:sesi,id',
            'gaya_renang'  => 'required|string|max:50',
            'jarak'        => 'required|integer|min:1',
            'catatan_waktu'=> 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'catatan'      => 'nullable|string',
        ], [
            'siswa_id.required'      => 'Siswa wajib dipilih',
            'sesi_id.required'       => 'Sesi latihan wajib dipilih',
            'gaya_renang.required'   => 'Gaya renang wajib dipilih',
            'jarak.required'         => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex'    => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
        ]);

        // Validasi siswa di kelas coach
        $siswaIds = $this->getCoachSiswaIds();
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        // Validasi sesi milik kelas coach
        $kelasIds = $this->getCoachKelasIds();
        $sesi = Sesi::whereIn('kelas_id', $kelasIds)->find($validated['sesi_id']);
        if (!$sesi) {
            return back()->withInput()
                        ->with('error', 'Sesi tidak valid untuk kelas Anda');
        }

        $validated['nomor_latihan'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';
        $validated['coach_id']      = auth()->id();

        CatatanLatihan::create($validated);

        return redirect()->route('coach.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified training record.
     */
    public function edit($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanLatihan = CatatanLatihan::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $kelasIds = $this->getCoachKelasIds();

        $sesiList = Sesi::whereIn('kelas_id', $kelasIds)
                       ->with('kelas')
                       ->orderBy('tanggal', 'desc')
                       ->orderBy('waktu_mulai', 'desc')
                       ->get();

        $siswaList = Siswa::whereIn('kelas_id', $kelasIds)
                         ->where('status', 'aktif')
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-latihan.edit', compact('catatanLatihan', 'sesiList', 'siswaList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Update the specified training record.
     */
    public function update(Request $request, $id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanLatihan = CatatanLatihan::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $validated = $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'sesi_id'      => 'required|exists:sesi,id',
            'gaya_renang'  => 'required|string|max:50',
            'jarak'        => 'required|integer|min:1',
            'catatan_waktu'=> 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'catatan'      => 'nullable|string',
        ], [
            'siswa_id.required'      => 'Siswa wajib dipilih',
            'sesi_id.required'       => 'Sesi latihan wajib dipilih',
            'gaya_renang.required'   => 'Gaya renang wajib dipilih',
            'jarak.required'         => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex'    => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
        ]);

        // Validasi siswa di kelas coach
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        // Validasi sesi milik kelas coach
        $kelasIds = $this->getCoachKelasIds();
        $sesi = Sesi::whereIn('kelas_id', $kelasIds)->find($validated['sesi_id']);
        if (!$sesi) {
            return back()->withInput()
                        ->with('error', 'Sesi tidak valid untuk kelas Anda');
        }

        $validated['nomor_latihan'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';

        $catatanLatihan->update($validated);

        return redirect()->route('coach.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil diperbarui');
    }

    /**
     * Remove the specified training record.
     */
    public function destroy($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanLatihan = CatatanLatihan::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $catatanLatihan->delete();

        return redirect()->route('coach.catatan-latihan.index')
                        ->with('success', 'Catatan latihan berhasil dihapus');
    }
}
