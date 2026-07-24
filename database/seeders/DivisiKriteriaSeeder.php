<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Kriteria;
use App\Models\Pegawai;
use App\Models\Periode;
use App\Models\SubKriteria;
use App\Models\User;
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

    private array $divisiData = [
        ['General Manager', 'Kantor', [
            ['C1', 'Mengoordinasikan seluruh kegiatan komersial perusahaan', 20],
            ['C2', 'Menetapkan kebijakan, rencana, dan tujuan perusahaan', 15],
            ['C3', 'Mengembangkan rencana strategis sesuai teknologi dan keuangan', 10],
            ['C4', 'Mengelola operasional perusahaan sehari-hari', 15],
            ['C5', 'Menetapkan dan mengawal standar proses & bisnis', 15],
            ['C6', 'Bertanggung jawab atas hubungan internal & eksternal perusahaan', 10],
        ]],
        ['Manajer Operasional', 'Kantor', [
            ['C1', 'Melakukan eliminasi pengeluaran operasional di luar standar', 15],
            ['C2', 'Mengevaluasi laporan operasional agar sesuai SOP', 15],
            ['C3', 'Mengawasi persediaan barang/jasa dan fasilitas operasional', 15],
            ['C4', 'Mengawasi kualitas produk/hasil kerja operasional', 25],
            ['C5', 'Mengoordinasikan aktivitas produksi-distribusi unit operasional', 15],
        ]],
        ['Keuangan', 'Kantor', [
            ['C1', 'Mengoordinasikan pelaporan dan pembayaran kewajiban pajak', 15],
            ['C2', 'Menyusun dan mengendalikan anggaran perusahaan', 15],
            ['C3', 'Mengonsolidasikan perpajakan seluruh perusahaan', 15],
            ['C4', 'Mengelola fungsi akuntansi untuk laporan keuangan akurat', 25],
            ['C5', 'Mengembangkan sistem dan prosedur keuangan yang tertib', 15],
        ]],
        ['HSE (Health, Safety, Environment)', 'Lapangan', [
            ['C1', 'Mencegah dan mengendalikan bahaya kimia/biohazard', 15],
            ['C2', 'Mencegah dan mengendalikan gas, asap, dan kotoran', 15],
            ['C3', 'Memelihara kebersihan, kesehatan, dan ketertiban area kerja', 15],
            ['C4', 'Mencegah hilangnya pendapatan akibat insiden kerja', 20],
            ['C5', 'Memberikan pertolongan kecelakaan kerja', 20],
        ]],
        ['Logistik / Gudang', 'Lapangan', [
            ['C1', 'Mengawasi dan mengelola operasional gudang', 15],
            ['C2', 'Mengawasi keluar-masuk tool gudang sesuai SOP', 15],
            ['C3', 'Mengonfirmasi jumlah dan kondisi barang sesuai SOP', 15],
            ['C4', 'Merencanakan pengadaan logistik dan distribusi barang', 40],
        ]],
        ['SPV Sipil', 'Lapangan', [
            ['C1', 'Memeriksa laporan shift (log book) tiap hari kerja', 15],
            ['C2', 'Mencatat dan mendata sipil equipment', 15],
            ['C3', 'Mengomunikasikan masalah lapangan kepada atasan', 15],
            ['C4', 'Memelihara dan mengoperasikan peralatan/bangunan sipil', 20],
            ['C5', 'Memeriksa dan memastikan pekerjaan selesai dengan baik', 20],
        ]],
        ['SPV Mechanical', 'Lapangan', [
            ['C1', 'Memelihara dan mengoperasikan mesin/peralatan mechanical', 20],
            ['C2', 'Mematuhi SOP dan keselamatan kerja mekanikal', 25],
            ['C3', 'Menyelesaikan pekerjaan mekanikal sesuai spesifikasi dan jadwal', 25],
            ['C4', 'Berkoordinasi dengan tim dan melaporkan masalah lapangan', 15],
        ]],
        ['SPV Electrical', 'Lapangan', [
            ['C1', 'Mengoordinasi perbaikan dan perawatan mesin produksi elektrikal', 20],
            ['C2', 'Menginformasikan trouble/masalah elektrikal ke atasan', 10],
            ['C3', 'Menjaga kepatuhan tim terhadap aturan dasar & teknis elektrikal', 15],
            ['C4', 'Menjaga performance mesin dan kerja tim di lapangan', 20],
            ['C5', 'Membina bawahan (foreman, leader, operator)', 20],
        ]],
        ['SPV Piping', 'Lapangan', [
            ['C1', 'Meninjau dan menugaskan pekerjaan piping', 15],
            ['C2', 'Melakukan kontrol dan evaluasi pekerjaan piping', 15],
            ['C3', 'Membuat laporan pekerjaan tepat waktu dan akurat', 15],
            ['C4', 'Memberikan arahan kepada bawahan', 20],
            ['C5', 'Memberikan bimbingan, bantuan, dan umpan balik konstruktif ke tim', 20],
        ]],
        ['SPV Konstruksi', 'Lapangan', [
            ['C1', 'Memastikan pelaksanaan tugas lapangan sesuai biaya, mutu, waktu (BMW)', 25],
            ['C2', 'Membuat program kerja mingguan untuk staf di bawahnya', 10],
            ['C3', 'Memahami dan menerapkan desain konstruksi secara teknis', 10],
            ['C4', 'Menyusun metode pelaksanaan sesuai kondisi lapangan', 25],
            ['C5', 'Melakukan evaluasi dan pelaporan progres ke atasan', 15],
        ]],
    ];

    public function run(): void
    {
        Divisi::query()->delete();
        Kriteria::query()->delete();
        SubKriteria::query()->delete();
        User::query()->delete();

        foreach ($this->divisiData as $row) {
            [$nama, $kelompok, $kpis] = $row;
            $divisi = Divisi::create([
                'nama_divisi' => $nama,
                'kelompok_kerja' => $kelompok,
            ]);

            foreach ($kpis as [$kode, $namaK, $bobot]) {
                $kriteria = Kriteria::create([
                    'id_divisi' => $divisi->id,
                    'kode_kriteria' => $kode,
                    'nama_kriteria' => $namaK,
                    'bobot' => $bobot,
                    'atribut' => 'benefit',
                    'target' => 'Sesuai target KPI periode berjalan',
                ]);
                foreach (self::SUB_RUBRIK as $s) {
                    SubKriteria::create([
                        'id_kriteria' => $kriteria->id,
                        'nama_subkriteria' => $s['nama'],
                        'nilai' => $s['nilai'],
                        'keterangan' => null,
                    ]);
                }
            }
            // 2 kriteria umum: Tingkat Kehadiran & Disiplin Kehadiran
            $this->tambahKehadiranKriteria($divisi->id);
        }

        $this->seedAkunDanContoh();
    }

    private function tambahKehadiranKriteria(int $idDivisi): void
    {
        $k1 = Kriteria::create([
            'id_divisi' => $idDivisi,
            'kode_kriteria' => 'KH',
            'nama_kriteria' => 'Tingkat Kehadiran',
            'bobot' => 7.5,
            'atribut' => 'benefit',
            'target' => 'Kehadiran minimal 90% per tahun (216 hari dari 240 hari kerja)',
        ]);
        $k2 = Kriteria::create([
            'id_divisi' => $idDivisi,
            'kode_kriteria' => 'DK',
            'nama_kriteria' => 'Disiplin Kehadiran (Ketepatan Waktu)',
            'bobot' => 7.5,
            'atribut' => 'benefit',
            'target' => 'Keterlambatan maksimal 15% per tahun (36 hari dari 240 hari kerja)',
        ]);
        foreach ([$k1, $k2] as $k) {
            foreach (self::SUB_RUBRIK as $s) {
                SubKriteria::create([
                    'id_kriteria' => $k->id,
                    'nama_subkriteria' => $s['nama'],
                    'nilai' => $s['nilai'],
                    'keterangan' => null,
                ]);
            }
        }
    }

    private function seedAkunDanContoh(): void
    {
        $hse = Divisi::where('nama_divisi', 'like', 'HSE%')->first();

        User::create(['nama' => 'Admin HRD', 'username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        User::create(['nama' => 'Manajer HSE', 'username' => 'manajer', 'password' => Hash::make('manajer123'), 'role' => 'manajer', 'id_divisi' => $hse->id]);
        User::create(['nama' => 'Direktur', 'username' => 'pimpinan', 'password' => Hash::make('pimpinan123'), 'role' => 'pimpinan']);

        // 3 pegawai contoh HSE (sesuai contoh perhitungan laporan)
        $a = Pegawai::create(['nik' => 'HSE001', 'nama' => 'Pegawai A', 'jabatan' => 'Staff HSE', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-01-01', 'id_divisi' => $hse->id]);
        $b = Pegawai::create(['nik' => 'HSE002', 'nama' => 'Pegawai B', 'jabatan' => 'Staff HSE', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-03-01', 'id_divisi' => $hse->id]);
        $c = Pegawai::create(['nik' => 'HSE003', 'nama' => 'Pegawai C', 'jabatan' => 'Staff HSE', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'P', 'tanggal_masuk' => '2023-06-01', 'id_divisi' => $hse->id]);

        $periode = Periode::create(['nama_periode' => 'Contoh Perhitungan HSE', 'tanggal_mulai' => '2025-01-01', 'tanggal_selesai' => '2025-12-31', 'status' => 'aktif']);

        $manajer = User::where('username', 'manajer')->first();
        $nilai = [
            $a->id => [5, 4, 5, 5, 4, 5, 4],
            $b->id => [4, 4, 3, 4, 4, 4, 4],
            $c->id => [2, 3, 2, 2, 3, 3, 2],
        ];
        $kriteriaHse = Kriteria::where('id_divisi', $hse->id)->orderBy('id')->get();
        foreach ($nilai as $idPegawai => $skor) {
            $penilaian = \App\Models\Penilaian::create([
                'id_pegawai' => $idPegawai, 'id_user' => $manajer->id, 'id_periode' => $periode->id, 'status_penilaian' => 'final',
            ]);
            foreach ($kriteriaHse as $i => $k) {
                \App\Models\DetailPenilaian::create([
                    'id_penilaian' => $penilaian->id, 'id_kriteria' => $k->id, 'nilai' => $skor[$i],
                ]);
            }
        }
    }
}