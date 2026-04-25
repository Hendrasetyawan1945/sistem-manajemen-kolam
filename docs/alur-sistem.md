# Alur Sistem Manajemen Klub Renang

---

## 1. Pendaftaran Siswa Baru

```mermaid
flowchart TD
    A([Start]) --> B[Calon siswa isi form /daftar]
    B --> C[Data tersimpan sebagai Calon Siswa]
    C --> D[Admin review pendaftaran]
    D --> E{Keputusan?}
    E -->|Approve| F[Akun siswa dibuat, status Aktif]
    E -->|Reject| G[Status Ditolak]
    F --> H[Siswa bisa login]
    G --> I([End])
    H --> I
```

---

## 2. Setup Kelas & Coach

```mermaid
flowchart TD
    A([Start]) --> B[Admin buat Kelas]
    B --> C[Assign Coach ke kelas]
    C --> D[Assign Siswa ke kelas]
    D --> E([End])
```

---

## 3. Sesi Latihan & Kehadiran

```mermaid
flowchart TD
    A([Start]) --> B[Admin/Coach buat Sesi Latihan]
    B --> C[Sistem auto-generate daftar kehadiran]
    C --> D[Coach isi kehadiran per siswa]
    D --> E{Status}
    E --> F[Hadir / Izin / Sakit / Alpa]
    F --> G[Simpan rekap kehadiran]
    G --> H([End])
```

---

## 4. Catatan Latihan

```mermaid
flowchart TD
    A([Start]) --> B[Coach pilih Sesi & Siswa]
    B --> C[Isi gaya renang, jarak, waktu]
    C --> D[Simpan catatan latihan]
    D --> E{Tambah lagi?}
    E -->|Ya| B
    E -->|Tidak| F[Lihat analitik & tren]
    F --> G([End])
```

---

## 5. Catatan Waktu Lomba & Personal Best

```mermaid
flowchart TD
    A([Start]) --> B[Admin/Coach tambah Catatan Waktu]
    B --> C[Pilih kejuaraan, siswa, nomor lomba, waktu]
    C --> D{Lebih baik dari PB sebelumnya?}
    D -->|Ya| E[Personal Best otomatis diperbarui]
    D -->|Tidak| F[PB tetap]
    E --> G([End])
    F --> G
```

---

## 6. Rapor Siswa

```mermaid
flowchart TD
    A([Start]) --> B[Coach/Admin buat Rapor]
    B --> C[Pilih siswa & periode]
    C --> D[Isi nilai teknik, kecepatan, ketahanan, dll]
    D --> E[Sistem hitung rata-rata & grade otomatis]
    E --> F{Status}
    F -->|Draft| G[Simpan draft, bisa diedit]
    F -->|Final| H[Rapor terkunci]
    G --> I{Perlu revisi?}
    I -->|Ya| D
    I -->|Tidak| H
    H --> J[Siswa lihat rapor di portal]
    J --> K([End])
```

---

## 7. Iuran & Keuangan

```mermaid
flowchart TD
    A([Start]) --> B[Admin buat tagihan iuran]
    B --> C{Jenis iuran}
    C -->|Rutin| D[Tagihan bulanan per siswa]
    C -->|Insidentil| E[Tagihan tidak rutin]
    C -->|Kejuaraan| F[Tagihan biaya lomba]
    D --> G[Catat pembayaran]
    E --> G
    F --> G
    G --> H{Lunas?}
    H -->|Ya| I[Status Lunas]
    H -->|Cicilan| J[Buat Angsuran]
    J --> G
    I --> K([End])
```

---

## 8. Jersey

```mermaid
flowchart TD
    A([Start]) --> B[Admin buat pesanan jersey]
    B --> C[Pilih siswa, ukuran, jumlah, harga]
    C --> D[Status: Dipesan]
    D --> E{Update status}
    E -->|Tersedia| F[Status: Diterima]
    E -->|Batal| G[Status: Dibatalkan]
    F --> H[Siswa lihat status di portal]
    G --> H
    H --> I([End])
```

---

## 9. Export Laporan

```mermaid
flowchart TD
    A([Start]) --> B[Admin buka menu Export]
    B --> C{Pilih laporan}
    C -->|Kehadiran| D[Filter kelas & periode]
    C -->|Keuangan| E[Filter periode]
    C -->|Daftar Siswa| F[Filter status & kelas]
    C -->|Rapor| G[Filter siswa & periode]
    D --> H{Format}
    E --> H
    F --> H
    G --> H
    H -->|PDF| I[Download PDF]
    H -->|Excel/CSV| J[Download Excel/CSV]
    I --> K([End])
    J --> K
```

---

## 10. Login & Akses Role

```mermaid
flowchart TD
    A([Start]) --> B[Input email & password]
    B --> C{Login berhasil?}
    C -->|Tidak| D[Tampilkan error]
    D --> B
    C -->|Ya| E{Role?}
    E -->|Admin| F[/admin/dashboard — akses penuh]
    E -->|Coach| G[/coach/dashboard — kelas sendiri]
    E -->|Siswa| H[/siswa/dashboard — read only]
    F --> I([End])
    G --> I
    H --> I
```
