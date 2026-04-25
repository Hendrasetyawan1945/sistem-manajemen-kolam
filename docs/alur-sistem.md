# 📊 Alur Sistem Manajemen Klub Renang

Dokumen ini menjelaskan alur kerja setiap fitur dalam sistem.
Untuk info akun demo dan cara menjalankan, lihat [README.md](../README.md).

---

## 📌 Daftar Alur

| No | Alur | Aktor |
|----|------|-------|
| 1 | [Pendaftaran Siswa Baru](#1-pendaftaran-siswa-baru) | Calon Siswa + Admin |
| 2 | [Login & Akses Role](#2-login--akses-role) | Semua User |
| 3 | [Setup Kelas & Coach](#3-setup-kelas--coach) | Admin |
| 4 | [Sesi Latihan & Kehadiran](#4-sesi-latihan--kehadiran) | Admin + Coach |
| 5 | [Catatan Latihan](#5-catatan-latihan) | Coach + Admin |
| 6 | [Catatan Waktu Lomba & Personal Best](#6-catatan-waktu-lomba--personal-best) | Admin + Coach |
| 7 | [Penilaian & Rapor Siswa](#7-penilaian--rapor-siswa) | Coach + Admin |
| 8 | [Iuran & Keuangan](#8-iuran--keuangan) | Admin |
| 9 | [Jersey](#9-jersey) | Admin + Siswa |
| 10 | [Export Laporan](#10-export-laporan) | Admin |
| 11 | [Import Siswa Massal](#11-import-siswa-massal) | Admin |

---

## 1. Pendaftaran Siswa Baru

> Calon siswa mendaftar secara mandiri, lalu admin melakukan review.

```mermaid
flowchart TD
    A([Start]) --> B[Calon siswa buka halaman /daftar]
    B --> C[Isi formulir: nama, tanggal lahir, alamat, data orang tua]
    C --> D[Submit formulir]
    D --> E[Data tersimpan dengan status 'Menunggu']
    E --> F[Admin buka menu Pendaftaran di dashboard]
    F --> G[Admin review data calon siswa]
    G --> H{Keputusan Admin}
    H -->|Approve| I[Status berubah menjadi 'Aktif']
    H -->|Reject| J[Status berubah menjadi 'Ditolak']
    I --> K[Akun login siswa otomatis dibuat]
    K --> L[Siswa bisa login ke portal]
    J --> M[Pendaftaran selesai ditolak]
    L --> N([End])
    M --> N
```

**Halaman terkait:**
- `/daftar` — form pendaftaran publik
- `/cek-status` — cek status pendaftaran
- `/admin/pendaftaran` — review oleh admin

---

## 2. Login & Akses Role

> Setiap role diarahkan ke dashboard yang berbeda setelah login.

```mermaid
flowchart TD
    A([Start]) --> B[Buka halaman /login]
    B --> C[Masukkan email dan password]
    C --> D{Kredensial valid?}
    D -->|Tidak| E[Tampilkan pesan error]
    E --> C
    D -->|Ya| F{Role user?}
    F -->|admin| G[Redirect ke /admin/dashboard]
    F -->|coach| H[Redirect ke /coach/dashboard]
    F -->|siswa| I[Redirect ke /siswa/dashboard]
    G --> J[Akses penuh semua fitur]
    H --> K[Akses terbatas: kelas sendiri saja]
    I --> L[Akses read-only: data diri sendiri]
    J --> M([End])
    K --> M
    L --> M
```

**Akun demo:**
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@renang.com | password |
| Coach | coach1@renang.com | password |
| Siswa | siswa1@renang.com | password |

---

## 3. Setup Kelas & Coach

> Admin membuat kelas dan menugaskan coach serta siswa ke dalamnya.

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Kelas]
    B --> C[Klik Tambah Kelas]
    C --> D[Isi: nama kelas, jadwal, kapasitas, biaya bulanan]
    D --> E[Pilih Coach yang bertanggung jawab]
    E --> F[Simpan kelas]
    F --> G[Buka menu Siswa]
    G --> H[Edit data siswa]
    H --> I[Pilih kelas untuk siswa tersebut]
    I --> J{Masih ada siswa lain?}
    J -->|Ya| H
    J -->|Tidak| K([End])
```

---

## 4. Sesi Latihan & Kehadiran

> Sesi dibuat terlebih dahulu, lalu kehadiran diisi saat latihan berlangsung.

```mermaid
flowchart TD
    A([Start]) --> B[Admin/Coach buka menu Latihan]
    B --> C[Klik Buat Sesi Baru]
    C --> D[Pilih kelas, isi tanggal dan waktu latihan]
    D --> E[Simpan sesi]
    E --> F[Sistem otomatis buat daftar kehadiran untuk semua siswa di kelas]
    F --> G[Saat latihan berlangsung, Coach buka sesi]
    G --> H[Isi status kehadiran per siswa]
    H --> I{Status per siswa}
    I --> J[Hadir / Izin / Sakit / Alpa]
    J --> K{Semua siswa sudah diisi?}
    K -->|Belum| H
    K -->|Ya| L[Simpan rekap kehadiran]
    L --> M([End])
```

---

## 5. Catatan Latihan

> Coach mencatat performa siswa saat latihan berlangsung.

```mermaid
flowchart TD
    A([Start]) --> B[Coach buka menu Catatan Latihan]
    B --> C[Klik Tambah Catatan]
    C --> D[Pilih sesi latihan yang sedang berjalan]
    D --> E[Pilih siswa]
    E --> F[Isi: gaya renang, jarak, catatan waktu]
    F --> G[Simpan catatan]
    G --> H{Tambah catatan siswa lain?}
    H -->|Ya| E
    H -->|Tidak| I[Lihat daftar catatan latihan]
    I --> J[Bisa filter berdasarkan siswa, tanggal, atau gaya]
    J --> K[Lihat grafik analitik & tren perkembangan]
    K --> L([End])
```

---

## 6. Catatan Waktu Lomba & Personal Best

> Setiap catatan waktu lomba yang lebih baik akan otomatis memperbarui Personal Best.

```mermaid
flowchart TD
    A([Start]) --> B[Admin/Coach buka menu Catatan Waktu]
    B --> C[Klik Tambah Catatan]
    C --> D[Pilih kejuaraan]
    D --> E[Pilih siswa]
    E --> F[Isi: gaya renang, jarak, waktu, posisi]
    F --> G[Simpan catatan waktu]
    G --> H{Waktu lebih baik dari Personal Best sebelumnya?}
    H -->|Ya| I[Personal Best otomatis diperbarui]
    H -->|Tidak| J[Personal Best tetap tidak berubah]
    I --> K[Lihat halaman Personal Best untuk rekap rekor]
    J --> K
    K --> L([End])
```

---

## 7. Penilaian & Rapor Siswa

> Coach menilai siswa per periode, sistem menghitung grade otomatis.

```mermaid
flowchart TD
    A([Start]) --> B[Coach/Admin buka menu Rapor]
    B --> C[Klik Tambah Rapor]
    C --> D[Pilih siswa dan periode penilaian]
    D --> E[Isi nilai: teknik, kecepatan, ketahanan, kedisiplinan, semangat]
    E --> F[Sistem hitung rata-rata dan grade otomatis]
    F --> G{Nilai rata-rata}
    G -->|90-100| H[Grade A]
    G -->|80-89| I[Grade B]
    G -->|70-79| J[Grade C]
    G -->|di bawah 70| K[Grade D]
    H --> L{Set status rapor}
    I --> L
    J --> L
    K --> L
    L -->|Draft| M[Simpan sebagai draft, masih bisa diedit]
    L -->|Final| N[Rapor dikunci dan bisa dilihat siswa]
    M --> O{Perlu revisi?}
    O -->|Ya| E
    O -->|Tidak| N
    N --> P[Admin bisa export rapor ke PDF atau Excel]
    P --> Q([End])
```

---

## 8. Iuran & Keuangan

> Admin mengelola semua tagihan dan mencatat pembayaran siswa.

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Iuran & Keuangan]
    B --> C{Pilih jenis iuran}
    C -->|Iuran Rutin| D[Tagihan bulanan per siswa]
    C -->|Iuran Insidentil| E[Tagihan tidak rutin, misal seragam]
    C -->|Iuran Kejuaraan| F[Tagihan biaya ikut lomba]
    D --> G[Buat tagihan, isi jumlah dan bulan]
    E --> G
    F --> G
    G --> H[Siswa melakukan pembayaran]
    H --> I[Admin catat pembayaran]
    I --> J{Jenis pembayaran}
    J -->|Lunas sekaligus| K[Status berubah menjadi Lunas]
    J -->|Dicicil| L[Buat data Angsuran]
    L --> M[Catat setiap cicilan yang masuk]
    M --> N{Semua cicilan lunas?}
    N -->|Belum| M
    N -->|Ya| K
    K --> O([End])
```

---

## 9. Jersey

> Admin membuat pesanan jersey, siswa bisa memantau statusnya.

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Jersey]
    B --> C[Klik Tambah Pesanan]
    C --> D[Pilih siswa, ukuran, jumlah, dan harga]
    D --> E[Simpan pesanan]
    E --> F[Status awal: Dipesan]
    F --> G{Jersey sudah tersedia?}
    G -->|Sudah diterima| H[Admin update status menjadi Diterima]
    G -->|Dibatalkan| I[Admin update status menjadi Dibatalkan]
    H --> J[Siswa lihat status jersey di portal /siswa/jersey]
    I --> J
    J --> K([End])
```

---

## 10. Export Laporan

> Admin bisa mengekspor berbagai laporan dalam format PDF atau Excel.

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Export di /admin/export]
    B --> C{Pilih jenis laporan}
    C -->|Rekap Kehadiran| D[Filter: pilih kelas dan periode]
    C -->|Laporan Keuangan| E[Filter: pilih periode dan jenis iuran]
    C -->|Daftar Siswa| F[Filter: pilih status dan kelas]
    C -->|Rapor Siswa| G[Filter: pilih siswa dan periode]
    D --> H{Pilih format}
    E --> H
    F --> H
    G --> H
    H -->|PDF| I[Generate dan download file PDF]
    H -->|Excel| J[Generate dan download file Excel]
    H -->|CSV| K[Generate dan download file CSV]
    I --> L([End])
    J --> L
    K --> L
```

---

## 11. Import Siswa Massal

> Admin bisa menambahkan banyak siswa sekaligus menggunakan file CSV.

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Import di /admin/import]
    B --> C[Download template CSV yang tersedia]
    C --> D[Isi data siswa di file template]
    D --> E[Upload file CSV ke sistem]
    E --> F[Sistem menampilkan preview data sebelum disimpan]
    F --> G{Data sudah benar?}
    G -->|Ada yang salah| H[Perbaiki file CSV]
    H --> E
    G -->|Sudah benar| I[Klik konfirmasi untuk proses import]
    I --> J[Sistem menyimpan semua data siswa]
    J --> K[Tampilkan laporan hasil import: berhasil dan gagal]
    K --> L([End])
```

---

> 💡 **Tips:** Semua flowchart di atas bisa dirender menjadi diagram visual di VS Code (ekstensi Markdown Preview Mermaid), GitHub, atau Obsidian.
