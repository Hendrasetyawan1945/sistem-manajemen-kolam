<?php

if (!function_exists('formatRupiah')) {
    /**
     * Format angka menjadi format Rupiah
     *
     * @param float|int $angka
     * @return string
     */
    function formatRupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('formatTanggal')) {
    /**
     * Format tanggal ke format Indonesia
     *
     * @param string $tanggal
     * @return string
     */
    function formatTanggal($tanggal)
    {
        if (!$tanggal) return '-';
        
        $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $timestamp = strtotime($tanggal);
        $hari_nama = $hari[date('w', $timestamp)];
        $tanggal_angka = date('j', $timestamp);
        $bulan_nama = $bulan[date('n', $timestamp)];
        $tahun = date('Y', $timestamp);
        
        return "$hari_nama, $tanggal_angka $bulan_nama $tahun";
    }
}
