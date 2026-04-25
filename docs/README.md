# Sistem Manajemen Klub Renang

Aplikasi web manajemen klub renang berbasis Laravel 10 dengan sistem multi-role (Admin, Coach, Siswa).

🌐 **Akses Aplikasi: [http://129.226.203.241:8888](http://129.226.203.241:8888)**

---

## Akun Demo

| Role    | Email                  | Password |
|---------|------------------------|----------|
| Admin   | admin@renang.com       | password |
| Coach 1 | coach1@renang.com      | password |
| Coach 2 | coach2@renang.com      | password |
| Siswa 1 | siswa1@renang.com      | password |
| Siswa 2 | siswa2@renang.com      | password |

---

## Akses Halaman

| Halaman              | URL                                                                 |
|----------------------|---------------------------------------------------------------------|
| Login                | [/login](http://129.226.203.241:8888/login)                        |
| Pendaftaran Publik   | [/daftar](http://129.226.203.241:8888/daftar)                      |
| Cek Status Daftar    | [/cek-status](http://129.226.203.241:8888/cek-status)              |
| Dashboard Admin      | [/admin/dashboard](http://129.226.203.241:8888/admin/dashboard)    |
| Dashboard Coach      | [/coach/dashboard](http://129.226.203.241:8888/coach/dashboard)    |
| Dashboard Siswa      | [/siswa/dashboard](http://129.226.203.241:8888/siswa/dashboard)    |

---

## Fitur Lengkap

### 👨‍💼 Admin

| Modul            | Fitur                                                              |
|------------------|--------------------------------------------------------------------|
| Siswa            | CRUD + upload foto + filter + search                               |
| Kelas            | CRUD + assignment coach + kapasitas                                |
| Sesi Latihan     | CRUD + auto-generate kehadiran                                     |
| Kehadiran        | Rekap + filter kelas/tanggal + export PDF                          |
| Iuran Rutin      | CRUD + status lunas/belum + filter                                 |
| Iuran Insidentil | CRUD + status pembayaran                                           |
| Kejuaraan        | CRUD + daftar peserta + statistik                                  |
| Iuran Kejuaraan  | Pendaftaran siswa ke kejuaraan                                     |
| Angsuran         | Cicilan + tracking pembayaran per cicilan                          |
| Pengeluaran      | CRUD + filter kategori + date range                                |
| Catatan Waktu    | Record waktu lomba + auto-update Personal Best                     |
| Personal Best    | Tracking rekor terbaik per nomor lomba                             |
| Catatan Latihan  | Record waktu latihan + analytics                                   |
| Rapor            | Penilaian teknik / fisik / kedisiplinan / semangat                 |
| Jersey           | Pesanan (Dipesan → Diterima / Dibatalkan)                          |
| Export           | PDF & Excel: keuangan, iuran, kehadiran, rapor                     |
| Import           | CSV bulk upload siswa dengan preview                               |
| Pendaftaran      | Review + approve / reject pendaftaran baru                         |

### 🏋️ Coach

- Dashboard kelas sendiri
- Sesi latihan & kehadiran kelas sendiri
- Catatan waktu & latihan siswa
- Rapor siswa di kelas sendiri

### 🎓 Siswa

- Dashboard info pribadi
- Riwayat kehadiran
- Status keuangan & tagihan
- Personal Best & prestasi
- Rapor & jersey

---

## Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/                    # 22 controller
│   ├── Coach/                    # 8 controller
│   ├── Siswa/                    # 6 controller
│   └── PendaftaranController.php
├── Models/                       # 21 model Eloquent
├── Exports/                      # 5 export class (Excel/CSV)
├── Helpers/helpers.php           # formatRupiah(), formatTanggal()
└── Providers/
    └── ValidationServiceProvider.php  # custom validation rules

resources/views/
├── layouts/
│   ├── admin.blade.php           # sidebar merah, scrollable
│   ├── coach.blade.php           # sidebar biru
│   └── siswa.blade.php           # top navbar hijau
├── components/                   # reusable components
├── admin/                        # view admin
├── coach/                        # view coach
├── siswa/                        # view siswa
└── pendaftaran/                  # form publik
```

---

## Alur Sistem

Lihat detail flowchart setiap alur di [alur-sistem.md](./alur-sistem.md)

| No | Alur                          |
|----|-------------------------------|
| 1  | Pendaftaran Siswa Baru        |
| 2  | Setup Kelas & Coach           |
| 3  | Sesi Latihan & Kehadiran      |
| 4  | Catatan Latihan               |
| 5  | Catatan Waktu & Personal Best |
| 6  | Rapor Siswa                   |
| 7  | Iuran & Keuangan              |
| 8  | Jersey                        |
| 9  | Export Laporan                |
| 10 | Login & Akses Role            |

---

## Stack Teknologi

| Komponen   | Teknologi                  |
|------------|----------------------------|
| Framework  | Laravel 10 + Breeze Auth   |
| Database   | SQLite                     |
| Frontend   | Bootstrap 5.3 + Font Awesome 6.4 |
| Export PDF | DomPDF                     |
| Export Excel | Laravel Excel            |
| PHP        | 8.1+                       |
