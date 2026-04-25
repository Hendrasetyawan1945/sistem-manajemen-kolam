# 🏊 Sistem Manajemen Klub Renang

Aplikasi web manajemen klub renang berbasis Laravel 10 dengan sistem multi-role (Admin, Coach, Siswa).

---

## 🚀 Cara Menjalankan

```bash
# 1. Masuk ke folder project
cd sistem-renang

# 2. Install dependencies (jika belum)
composer install

# 3. Copy environment file (jika belum ada .env)
cp .env.example .env
php artisan key:generate

# 4. Jalankan migrasi + seeder
php artisan migrate --seed

# 5. Buat symlink storage
php artisan storage:link

# 6. Jalankan server
php artisan serve --port=8888
```

Buka browser: **http://127.0.0.1:8888**

---

## 👤 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@renang.com | password |
| Coach 1 | coach1@renang.com | password |
| Coach 2 | coach2@renang.com | password |
| Siswa 1 | siswa1@renang.com | password |
| Siswa 2 | siswa2@renang.com | password |

---

## 🔗 URL Penting

| Halaman | URL |
|---------|-----|
| Login | `/login` |
| Daftar Siswa Baru | `/daftar` |
| Cek Status Pendaftaran | `/cek-status` |
| Dashboard Admin | `/admin/dashboard` |
| Dashboard Coach | `/coach/dashboard` |
| Dashboard Siswa | `/siswa/dashboard` |

---

## 📋 Fitur Lengkap

### 👨‍💼 Admin
| Modul | Fitur |
|-------|-------|
| **Siswa** | CRUD + upload foto + filter + search |
| **Kelas** | CRUD + assignment coach + kapasitas |
| **Sesi Latihan** | CRUD + auto-generate kehadiran |
| **Kehadiran** | Rekap + filter kelas/tanggal + export PDF |
| **Iuran Rutin** | CRUD + status lunas/belum + filter |
| **Iuran Insidentil** | CRUD + status pembayaran |
| **Kejuaraan** | CRUD + daftar peserta + statistik |
| **Iuran Kejuaraan** | Pendaftaran siswa ke kejuaraan |
| **Angsuran** | Cicilan + tracking pembayaran per cicilan |
| **Pengeluaran** | CRUD + filter kategori + date range |
| **Catatan Waktu** | Record waktu lomba + auto-update Personal Best |
| **Personal Best** | Tracking rekor terbaik per nomor lomba |
| **Catatan Latihan** | Record waktu latihan + analytics |
| **Rapor** | Penilaian teknik/fisik/kedisiplinan/semangat |
| **Jersey** | Pesanan (Dipesan → Diterima/Dibatalkan) |
| **Export** | PDF & Excel: keuangan, iuran, kehadiran, rapor |
| **Import** | CSV bulk upload siswa dengan preview |
| **Pendaftaran** | Review + approve/reject pendaftaran baru |

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

## 🗄️ Database

- **Engine**: SQLite
- **File**: `database/database.sqlite`
- **Tabel**: 25 tabel

### Reset Database
```bash
php artisan migrate:fresh --seed
```

---

## 🏗️ Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/          # 22 controller
│   ├── Coach/          # 8 controller
│   ├── Siswa/          # 6 controller
│   └── PendaftaranController.php
├── Models/             # 21 model Eloquent
├── Exports/            # 5 export class (Excel/CSV)
├── Helpers/helpers.php # formatRupiah(), formatTanggal()
└── Providers/
    └── ValidationServiceProvider.php  # custom validation rules

resources/views/
├── layouts/
│   ├── admin.blade.php   # sidebar merah, scrollable
│   ├── coach.blade.php   # sidebar biru
│   └── siswa.blade.php   # top navbar hijau
├── components/           # reusable components
├── admin/                # view admin
├── coach/                # view coach
├── siswa/                # view siswa
└── pendaftaran/          # form publik
```

---

## ⚙️ Tech Stack

| Komponen | Versi |
|----------|-------|
| PHP | 8.1+ |
| Laravel | 10.x |
| Bootstrap | 5.3 |
| Font Awesome | 6.4 |
| DomPDF | latest |
| Laravel Excel | 3.x |

---

## 🔧 Konvensi Kode

### Kolom Database Penting
```
Siswa: nama_ortu, telepon_ortu  (BUKAN nama_orang_tua)
User:  name (BUKAN nama), role, telepon
```

### Helper Functions
```php
formatRupiah(1500000)       // "Rp 1.500.000"
formatTanggal('2026-04-25') // "Sabtu, 25 April 2026"
```

### Custom Validation
```php
'phone_number'    // 10-15 digit angka
'time_format'     // MM:SS.MS
'currency_format' // angka positif
```

### SQLite — Fungsi yang Aman
```php
// ✅ Gunakan ini
whereYear('col', $year)
whereMonth('col', $month)
selectRaw("strftime('%Y', col) as tahun")
orderByRaw("CASE status WHEN 'a' THEN 0 ELSE 1 END")

// ❌ Jangan gunakan (MySQL only)
selectRaw('YEAR(col)')
orderByRaw("FIELD(col, 'a', 'b')")
```

---

## 📁 Spec & Dokumentasi

Dokumentasi lengkap ada di:
- `.kiro/specs/sistem-manajemen-klub-renang/requirements.md` — 31 requirements
- `.kiro/specs/sistem-manajemen-klub-renang/design.md` — desain teknis
- `.kiro/specs/sistem-manajemen-klub-renang/tasks.md` — progress implementasi
- `.kiro/steering/project-context.md` — context untuk Kiro AI
