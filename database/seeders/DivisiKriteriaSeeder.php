<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Periode;
use App\Models\SubKriteria;
use App\Models\User;
use App\Models\Penilaian;
use App\Models\DetailPenilaian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DivisiKriteriaSeeder extends Seeder
{
    private const SUB_RUBRIK = [
        ['nilai' => 5, 'nama' => 'Sangat Baik'],
        ['nilai' => 4, 'nama' => 'Baik'],
        ['nilai' => 3, 'nama' => 'Cukup'],
        ['nilai' => 2, 'nama' => 'Kurang'],
        ['nilai' => 1, 'nama' => 'Sangat Kurang'],
    ];

    public function run(): void
    {
        // Bersihkan tabel
        Divisi::query()->delete();
        Kriteria::query()->delete();
        SubKriteria::query()->delete();
        User::query()->delete();
        Pegawai::query()->delete();
        Periode::query()->delete();

        // 1. Seed Divisi
        $hse = Divisi::create(['nama_divisi' => 'HSE (Health, Safety, Environment)', 'kelompok_kerja' => 'Lapangan']);
        $ops = Divisi::create(['nama_divisi' => 'Manajer Operasional', 'kelompok_kerja' => 'Kantor']);
        $keu = Divisi::create(['nama_divisi' => 'Keuangan', 'kelompok_kerja' => 'Kantor']);

        // 2. Seed Kriteria HSE (7 KPI)
        $kpisHse = [
            ['C1', 'Mencegah dan mengendalikan bahaya kimia/biohazard', 15.0, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mencegah dan mengendalikan gas, asap, dan kotoran', 15.0, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Memelihara kebersihan, kesehatan, dan ketertiban area kerja', 15.0, 'benefit', 'kualitatif', null, null],
            ['C4', 'Mencegah hilangnya pendapatan akibat insiden kerja', 20.0, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Memberikan pertolongan kecelakaan kerja (Respons Time)', 20.0, 'cost', 'kuantitatif', 'Menit', 5.00], 
            ['KH', 'Tingkat Kehadiran', 7.5, 'benefit', 'kuantitatif', '%', 100.00],
            ['DK', 'Disiplin Kehadiran (Ketepatan Waktu)', 7.5, 'benefit', 'kuantitatif', '%', 100.00],
        ];

        foreach ($kpisHse as [$kode, $nama, $bobot, $atribut, $tipe, $satuan, $target]) {
            $k = Kriteria::create([
                'id_divisi' => $hse->id,
                'kode_kriteria' => $kode,
                'nama_kriteria' => $nama,
                'bobot' => $bobot,
                'atribut' => $atribut,
                'tipe' => $tipe,
                'satuan' => $satuan,
                'target_angka' => $target,
                'target' => $tipe === 'kuantitatif' ? "Target: $target $satuan" : "Diisi oleh Kepala Divisi",
            ]);
            foreach (self::SUB_RUBRIK as $s) {
                SubKriteria::create([
                    'id_kriteria' => $k->id,
                    'nama_subkriteria' => $s['nama'],
                    'nilai' => $s['nilai'],
                ]);
            }
        }

        // 3. Seed Users
        // Admin
        User::create(['nama' => 'Admin HRD', 'username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        // Kadiv HSE
        $kadiv = User::create(['nama' => 'Kepala Divisi HSE', 'username' => 'kadiv', 'password' => Hash::make('manajer123'), 'role' => 'kadiv', 'id_divisi' => $hse->id]);
        // Pimpinan
        User::create(['nama' => 'Direktur Utama', 'username' => 'pimpinan', 'password' => Hash::make('pimpinan123'), 'role' => 'pimpinan']);

        // Pegawai HSE
        $uA = User::create(['nama' => 'Pegawai A', 'username' => 'pegawaia', 'password' => Hash::make('pegawaia123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);
        $uB = User::create(['nama' => 'Pegawai B', 'username' => 'pegawaib', 'password' => Hash::make('pegawaib123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);
        $uC = User::create(['nama' => 'Pegawai C', 'username' => 'pegawaic', 'password' => Hash::make('pegawaic123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);

        // 4. Seed Pegawai
        $pA = Pegawai::create(['nik' => 'HSE001', 'nama' => 'Pegawai A', 'jabatan' => 'Safety Officer', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-01-01', 'id_divisi' => $hse->id, 'id_user' => $uA->id]);
        $pB = Pegawai::create(['nik' => 'HSE002', 'nama' => 'Pegawai B', 'jabatan' => 'Safety Inspector', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-03-01', 'id_divisi' => $hse->id, 'id_user' => $uB->id]);
        $pC = Pegawai::create(['nik' => 'HSE003', 'nama' => 'Pegawai C', 'jabatan' => 'Safety Admin', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'P', 'tanggal_masuk' => '2023-06-01', 'id_divisi' => $hse->id, 'id_user' => $uC->id]);

        // 5. Seed Periode
        $periode = Periode::create(['nama_periode' => 'Semester 1 2026', 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-06-30', 'status' => 'aktif']);

        // 6. Contoh Data Laporan Realisasi Pegawai (untuk test awal)
        // Pegawai A
        $penA = Penilaian::create(['id_pegawai' => $pA->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realA = [
            'C1' => [100.0, 5.0], 
            'C2' => [100.0, 4.0],
            'C3' => [null, 5.0],  
            'C4' => [100.0, 5.0],
            'C5' => [3.0, 4.0],   
            'KH' => [95.0, 5.0],
            'DK' => [90.0, 4.0],
        ];

        // Pegawai B
        $penB = Penilaian::create(['id_pegawai' => $pB->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realB = [
            'C1' => [90.0, 4.0],
            'C2' => [100.0, 4.0],
            'C3' => [null, 3.0],
            'C4' => [80.0, 4.0],
            'C5' => [3.0, 4.0],
            'KH' => [90.0, 4.0],
            'DK' => [90.0, 4.0],
        ];

        // Pegawai C
        $penC = Penilaian::create(['id_pegawai' => $pC->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realC = [
            'C1' => [50.0, 2.0],
            'C2' => [80.0, 3.0],
            'C3' => [null, 2.0],
            'C4' => [40.0, 2.0],
            'C5' => [4.0, 3.0],
            'KH' => [80.0, 3.0],
            'DK' => [80.0, 2.0],
        ];

        $kriteria = Kriteria::where('id_divisi', $hse->id)->get();
        foreach ([$pA->id => [$penA->id, $realA], $pB->id => [$penB->id, $realB], $pC->id => [$penC->id, $realC]] as $pid => [$penid, $reals]) {
            foreach ($kriteria as $k) {
                $rdata = $reals[$k->kode_kriteria];
                DetailPenilaian::create([
                    'id_penilaian' => $penid,
                    'id_kriteria' => $k->id,
                    'realisasi' => $rdata[0],
                    'nilai' => $rdata[1],
                    'bukti_pdf' => $k->tipe === 'kuantitatif' ? 'uploads/bukti/contoh_bukti.pdf' : null,
                ]);
            }
        }

        // Panggil SMART Service untuk kalkulasi ranking awal
        $smart = app(\App\Services\SmartService::class);
        $smart->prosesPeriode($periode->id);
    }
}