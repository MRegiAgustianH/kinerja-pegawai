# DESAIN SISTEM - Penilaian Kinerja Pegawai PT Alika Jaya Perkasa (SMART)

Sumber: `laporan.pdf` (151 hal). Implementasi: Laravel 12 + Blade + MySQL, PHP 8.3.

---

## 1. Metode SMART

4 langkah (Bab 2.2.1, hal 19-21):

1. Normalisasi bobot: Wj = wj / Sum(wj) (Sum = 1)
2. Nilai utility:
   - Benefit: u = (Cout - Cmin) / (Cmax - Cmin)
   - Cost:    u = (Cmax - Cout) / (Cmax - Cmin)
3. Skor akhir: Sum (utility x bobot_normalisasi)
4. Ranking: skor tertinggi = terbaik

### Parameter sesuai contoh perhitungan (HSE, terverifikasi 0.8938)

- Skala nilai: 1-5, Cmin=1, Cmax=5 -> utility = (skor - 1) / 4
- Bobot per divisi diberi dalam % (Sum=100) -> bobot_normalisasi = bobot / 100
- Semua kriteria = benefit

### Verifikasi contoh HSE (3 pegawai, 7 KPI, bobot 15,15,15,20,20,7.5,7.5)

| Pegawai | K1 | K2 | K3 | K4 | K5 | K6 | K7 | Skor | Kategori |
|---|---|---|---|---|---|---|---|---|---|
| A | 5 | 4 | 5 | 5 | 4 | 5 | 4 | 0.8938 | Sangat Baik |
| B | 4 | 4 | 3 | 4 | 4 | 4 | 4 | 0.7125 | Baik |
| C | 2 | 3 | 2 | 2 | 3 | 3 | 2 | 0.3563 | Kurang |

Perhitungan A: 0.15*1 + 0.15*0.75 + 0.15*1 + 0.20*1 + 0.20*0.75 + 0.075*1 + 0.075*0.75 = 0.8938 (cocok)

---

## 2. Kriteria (KPI) per Divisi - Bab 3.3.3 (hal 65-81)

Kriteria = KPI per divisi/jabatan, 10 set, masing-masing Sum bobot = 100. Skala 1-5, semua benefit.

| # | Divisi/Jabatan | Jml KPI | Bobot | Kelompok Kerja |
|---|---|---|---|---|
| 1 | General Manager | 8 | 20,15,10,15,15,10,7.5,7.5 | Kantor |
| 2 | Manajer Operasional | 7 | 15,15,15,25,15,7.5,7.5 | Kantor |
| 3 | Keuangan | 7 | 15,15,15,25,15,7.5,7.5 | Kantor |
| 4 | HSE | 7 | 15,15,15,20,20,7.5,7.5 | Lapangan |
| 5 | Logistik/Gudang | 6 | 15,15,15,40,7.5,7.5 | Lapangan |
| 6 | SPV Sipil | 7 | 15,15,15,20,20,7.5,7.5 | Lapangan |
| 7 | SPV Mechanical | 6 | 20,25,25,15,7.5,7.5 | Lapangan |
| 8 | SPV Electrical | 7 | 20,10,15,20,20,7.5,7.5 | Lapangan |
| 9 | SPV Piping | 7 | 15,15,15,20,20,7.5,7.5 | Lapangan |
| 10 | SPV Konstruksi | 7 | 25,10,10,25,15,7.5,7.5 | Lapangan |

Pola berulang: "Tingkat Kehadiran" (7.5) + "Disiplin Kehadiran/Ketepatan Waktu" (7.5) ada di semua divisi (2 kriteria terakhir, Sum=15). Sisanya divisi-specific.

---

## 3. Kategori & Rekomendasi - Tabel 3.7 (hal 88)

| Rentang Skor | Kategori | Rekomendasi |
|---|---|---|
| > 0.80 | Sangat Baik | Pegawai terbaik, bonus, promosi, prioritaskan dipanggil lagi |
| 0.60 - 0.80 | Baik | Kandidat bonus, dipertahankan, dapat dipanggil lagi |
| 0.40 - 0.59 | Cukup | Pembinaan, dipertimbangkan |
| < 0.40 | Kurang | Evaluasi lanjut, tidak direkomendasikan |

Rekomendasi = bahan pertimbangan objektif. Keputusan akhir tetap di tangan Pimpinan.

---

## 4. Aktor & Hak Akses (3 role, hal 52)

| Role | Hak Akses |
|---|---|
| Admin/HRD | Login, kelola akun, pegawai, divisi & kelompok kerja, kehadiran, kriteria & subkriteria, bobot, periode, lihat hasil seluruh pegawai, cetak PDF |
| Manajer | Login, input penilaian (skala 1-5 per kriteria) pegawai di divisinya, lihat hasil divisinya |
| Pimpinan | Login, lihat ranking seluruh pegawai + rekomendasi, cetak PDF |

Pegawai tdk login (analisis pengguna resmi). Hasil penilaian disampaikan via laporan tercetak oleh Admin/HRD.

---

## 5. Fitur Fungsional (16) - Tabel 3.3 (hal 47-51)

1. Login (Admin/HRD, Manajer, Pimpinan)
2. Kelola Data Pegawai (CRUD)
3. Kelola Data Divisi & Kelompok Kerja (CRUD)
4. Kelola Data Kehadiran (input + import Excel)
5. Kelola Kriteria & Subkriteria
6. Kelola Bobot Kriteria
7. Kelola Periode Penilaian (buka/tutup)
8. Lihat Hasil Penilaian (Admin/HRD)
9. Cetak Laporan Penilaian PDF (Admin/HRD)
10. Kelola Akun Pengguna
11. Login Manajer
12. Input Penilaian Kinerja (Manajer, skala 1-5)
13. Lihat Hasil Divisi (Manajer)
14. Login Pimpinan
15. Lihat Hasil Penilaian (Pimpinan)
16. Cetak Laporan PDF (Pimpinan)

---

## 6. Data Model

### Catatan perbedaan dengan ERD laporan (hal 123)

ERD laporan punya 11 tabel termasuk tabel Bobot (id_kriteria, kelompok_kerja, bobot). Karena di Bab 3.3.3 bobot hidup per divisi langsung di KPI/Kriteria, tabel Bobot jadi redundan. Solusi:

- DROP tabel Bobot -> bobot pindah ke kolom Kriteria.bobot
- Kriteria punya id_divisi (FK) + bobot langsung -> sumber kebenaran bobot
- kelompok_kerja (Lapangan/Kantor) tetap di Divisi -> untuk grouping laporan/ranking
- atribut (benefit/cost) di Kriteria -> default benefit, kolom tetap ada utk fleksibilitas

### 10 Tabel Final

1. divisi (id, nama_divisi, kelompok_kerja[Lapangan/Kantor], timestamps)
2. pegawai (id, nik, nama, jabatan, status, status_pegawai, jenis_kelamin, tanggal_masuk, id_divisi, timestamps)
3. kehadiran (id, id_pegawai, id_periode, hari_kerja, hari_hadir, timestamps) -> sumber nilai C-kehadiran
4. users (id, nama, username, password, role[admin/manajer/pimpinan], id_divisi[nullable, utk manajer], timestamps)
5. periode (id, nama_periode, tanggal_mulai, tanggal_selesai, status[aktif/tutup], timestamps)
6. kriteria (id, id_divisi, kode_kriteria, nama_kriteria, bobot, atribut[benefit/cost], target, timestamps)
7. sub_kriteria (id, id_kriteria, nama_subkriteria, nilai[1-5], keterangan, timestamps)
8. penilaian (id, id_pegawai, id_user[penilai], id_periode, status_penilaian[draft/final], timestamps)
9. detail_penilaian (id, id_penilaian, id_kriteria, id_sub_kriteria, nilai[1-5], timestamps)
10. hasil_penilaian (id, id_penilaian, nilai_smart, rangking, kategori, rekomendasi, timestamps) -> 1:1 dengan penilaian

### Relasi

- divisi 1:N pegawai
- divisi 1:N kriteria
- pegawai 1:N kehadiran, penilaian
- periode 1:N kehadiran, penilaian
- users 1:N penilaian (sebagai penilai)
- kriteria 1:N sub_kriteria
- penilaian 1:N detail_penilaian
- penilaian 1:1 hasil_penilaian
- detail_penilaian N:1 kriteria, N:1 sub_kriteria

---

## 7. Alur Perhitungan (Sequence Diagram Hasil, hal 116)

1. Admin/HRD/Pimpinan pilih periode
2. Sistem ambil: penilaian + detail_penilaian + pegawai + kriteria(berdasar divisi pegawai)
3. Hitung SMART per pegawai:
   - utility per kriteria: (nilai - 1) / 4
   - bobot_normalisasi: bobot / 100
   - skor_smart: Sum (utility x bobot_normalisasi)
4. Tentukan kategori + rekomendasi (Tabel 3.7)
5. Ranking dalam scope (per divisi / per kelompok_kerja / seluruh)
6. Simpan ke hasil_penilaian
7. Tampilkan di halaman hasil + cetak PDF

### Validasi Bobot (Bab 3.4.3 seq Bobot, hal 120-121)

calculateTotalWeight() per divisi harus = 100%. Jika < 100% -> error, simpan ditolak.

---

## 8. Tech Stack (hal 51)

- PHP 8.3 (Laragon php-8.3.28)
- Laravel 12
- Blade
- MySQL (Xampp/Laragon)
- dompdf (cetak PDF) - barryvdh/laravel-dompdf
- Maatwebsite/Laravel-Excel (import kehadiran) - opsional, bisa manual input
- Bootstrap 5 (via CDN) utk UI cepat

---

## 9. Struktur Project

app/
  Models/        Divisi, Pegawai, Kehadiran, User, Periode, Kriteria, SubKriteria, Penilaian, DetailPenilaian, HasilPenilaian
  Services/      SmartService.php  (hitung utility, skor, ranking, kategori, rekomendasi)
  Http/
    Controllers/
      Auth/LoginController
      Admin/  (PegawaiController, DivisiController, KehadiranController, KriteriaController, PeriodeController, UserController, HasilController)
      Manajer/ (PenilaianController, HasilController)
      Pimpinan/ (HasilController)
    Middleware/ RoleMiddleware.php
database/
  migrations/   10 file
  seeders/       DivisiKriteriaSeeder (seed 10 divisi + KPI + bobot lengkap)
resources/views/
  layouts/app.blade.php
  auth/login.blade.php
  admin/*.blade.php
  manajer/*.blade.php
  pimpinan/*.blade.php
routes/web.php   (group per role + middleware)

---

## 10. Validasi vs Laporan

- [x] Rumus SMART sesuai Bab 2.2.1
- [x] Kriteria KPI per divisi sesuai Bab 3.3.3 (10 set)
- [x] Contoh hitung HSE terverifikasi (0.8938, 0.7125, 0.3563)
- [x] Kategori & rekomendasi sesuai Tabel 3.7
- [x] 3 aktor + 16 fitur fungsional sesuai Tabel 3.3 & 3.6
- [x] Data model sesuai ERD/Class Diagram (11 tabel -> 10 setelah drop Bobot redundan)
- [x] Validasi bobot = 100% per divisi sesuai seq Bobot
- [x] Tech stack sesuai Bab 3.2.2 (Laravel 12, PHP 8.2/8.3)

## 11. Ambiguitas yang diputuskan

1. Kriteria per-Divisi (bukan 6 generik C1-C6) -> reproduksi contoh hitung sidang
2. Tabel Bobot di-drop -> bobot hidup di Kriteria.bobot (sumber kebenaran Bab 3.3.3)
3. Pegawai tidak login -> ikut analisis pengguna resmi (3 aktor), bukan jawaban wawancara #5
4. Kelompok kerja -> tag di Divisi, utk grouping laporan/ranking, bukan pembagi bobot
