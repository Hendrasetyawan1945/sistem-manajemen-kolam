<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaktu;
use App\Models\Kejuaraan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class CatatanWaktuController extends Controller
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
     * Display a listing of competition times for coach's students.
     */
    public function index(Request $request)
    {
        $siswaIds = $this->getCoachSiswaIds();

        $query = CatatanWaktu::with(['siswa.kelas', 'kejuaraan'])
                            ->whereIn('siswa_id', $siswaIds);

        if ($request->filled('kejuaraan_id')) {
            $query->where('kejuaraan_id', $request->kejuaraan_id);
        }

        if ($request->filled('siswa_id')) {
            if ($siswaIds->contains($request->siswa_id)) {
                $query->where('siswa_id', $request->siswa_id);
            }
        }

        if ($request->filled('gaya_renang')) {
            $query->where('gaya_renang', $request->gaya_renang);
        }

        if ($request->filled('jarak')) {
            $query->where('jarak', $request->jarak);
        }

        $catatanWaktu = $query->orderBy('created_at', 'desc')->paginate(20);

        $kelasIds      = $this->getCoachKelasIds();
        $siswaList     = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->orderBy('nama')->get();
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-waktu.index', compact('catatanWaktu', 'siswaList', 'kejuaraanList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Show the form for creating a new competition time record.
     */
    public function create()
    {
        $kelasIds      = $this->getCoachKelasIds();
        $siswaList     = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-waktu.create', compact('siswaList', 'kejuaraanList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Store a newly created competition time record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'gaya_renang'  => 'required|string|max:50',
            'jarak'        => 'required|integer|min:1',
            'catatan_waktu'=> 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'posisi'       => 'nullable|integer|min:1',
            'keterangan'   => 'nullable|string',
        ], [
            'siswa_id.required'      => 'Siswa wajib dipilih',
            'kejuaraan_id.required'  => 'Kejuaraan wajib dipilih',
            'gaya_renang.required'   => 'Gaya renang wajib dipilih',
            'jarak.required'         => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex'    => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
            'posisi.min'             => 'Posisi minimal 1',
        ]);

        // Validasi siswa di kelas coach
        $siswaIds = $this->getCoachSiswaIds();
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        $validated['nomor_lomba'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';

        // CatatanWaktu model akan auto-update PersonalBest via boot event
        CatatanWaktu::create($validated);

        return redirect()->route('coach.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil ditambahkan dan Personal Best telah diperbarui');
    }

    /**
     * Show the form for editing the specified competition time record.
     */
    public function edit($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanWaktu = CatatanWaktu::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $kelasIds      = $this->getCoachKelasIds();
        $siswaList     = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->with('kelas')->orderBy('nama')->get();
        $kejuaraanList = Kejuaraan::orderBy('tanggal_mulai', 'desc')->get();

        $gayaRenangList = ['Bebas', 'Punggung', 'Dada', 'Kupu-kupu', 'Gaya Ganti'];
        $jarakList      = [50, 100, 200, 400, 800, 1500];

        return view('coach.catatan-waktu.edit', compact('catatanWaktu', 'siswaList', 'kejuaraanList', 'gayaRenangList', 'jarakList'));
    }

    /**
     * Update the specified competition time record.
     */
    public function update(Request $request, $id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanWaktu = CatatanWaktu::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $validated = $request->validate([
            'siswa_id'     => 'required|exists:siswa,id',
            'kejuaraan_id' => 'required|exists:kejuaraan,id',
            'gaya_renang'  => 'required|string|max:50',
            'jarak'        => 'required|integer|min:1',
            'catatan_waktu'=> 'required|regex:/^\d{1,2}:\d{2}\.\d{2}$/',
            'posisi'       => 'nullable|integer|min:1',
            'keterangan'   => 'nullable|string',
        ], [
            'siswa_id.required'      => 'Siswa wajib dipilih',
            'kejuaraan_id.required'  => 'Kejuaraan wajib dipilih',
            'gaya_renang.required'   => 'Gaya renang wajib dipilih',
            'jarak.required'         => 'Jarak wajib dipilih',
            'catatan_waktu.required' => 'Catatan waktu wajib diisi',
            'catatan_waktu.regex'    => 'Format waktu harus MM:SS.MS (contoh: 01:23.45)',
            'posisi.min'             => 'Posisi minimal 1',
        ]);

        // Validasi siswa di kelas coach
        if (!$siswaIds->contains($validated['siswa_id'])) {
            return back()->withInput()
                        ->with('error', 'Siswa tidak berada di kelas Anda');
        }

        $validated['nomor_lomba'] = $validated['gaya_renang'] . ' ' . $validated['jarak'] . 'm';

        $catatanWaktu->update($validated);

        return redirect()->route('coach.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil diperbarui dan Personal Best telah diperbarui');
    }

    /**
     * Remove the specified competition time record.
     */
    public function destroy($id)
    {
        $siswaIds = $this->getCoachSiswaIds();
        $catatanWaktu = CatatanWaktu::whereIn('siswa_id', $siswaIds)->findOrFail($id);

        $catatanWaktu->delete();

        return redirect()->route('coach.catatan-waktu.index')
                        ->with('success', 'Catatan waktu berhasil dihapus');
    }
}
