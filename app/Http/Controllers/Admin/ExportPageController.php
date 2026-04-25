<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;

class ExportPageController extends Controller
{
    public function index()
    {
        // Get kelas list for dropdown
        $kelasList = Kelas::active()
                         ->orderBy('nama')
                         ->pluck('nama', 'id')
                         ->toArray();
        
        // Get siswa list for dropdown
        $siswaList = Siswa::active()
                         ->with('kelas')
                         ->orderBy('nama')
                         ->get()
                         ->mapWithKeys(function ($siswa) {
                             $kelasName = $siswa->kelas ? " ({$siswa->kelas->nama})" : '';
                             return [$siswa->id => $siswa->nama . $kelasName];
                         })
                         ->toArray();
        
        return view('admin.exports.index', compact('kelasList', 'siswaList'));
    }
}