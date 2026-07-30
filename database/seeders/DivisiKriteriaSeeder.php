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

    private array $divisiData = [
        ['General Manager', 'Kantor', [
            ['C1', 'Mengoordinasikan seluruh kegiatan komersial perusahaan', 27.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Menetapkan kebijakan, rencana, dan tujuan perusahaan', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Mengembangkan rencana strategis sesuai teknologi dan keuangan', 10.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Mengelola operasional perusahaan sehari-hari', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Menetapkan dan mengawal standar proses & bisnis', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C6', 'Bertanggung jawab atas hubungan internal & eksternal perusahaan', 10.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['Manajer Operasional', 'Kantor', [
            ['C1', 'Melakukan eliminasi pengeluaran operasional di luar standar', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mengevaluasi laporan operasional agar sesuai SOP', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Mengawasi persediaan barang/jasa dan fasilitas operasional', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Mengawasi kualitas produk/hasil kerja operasional', 25.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Mengoordinasikan aktivitas produksi-distribusi unit operasional', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['Keuangan', 'Kantor', [
            ['C1', 'Mengoordinasikan pelaporan dan pembayaran kewajiban pajak', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Menyusun dan mengendalikan anggaran perusahaan', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Mengonsolidasikan perpajakan seluruh perusahaan', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Mengelola fungsi akuntansi untuk laporan keuangan akurat', 25.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Mengembangkan sistem dan prosedur keuangan yang tertib', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['HSE (Health, Safety, Environment)', 'Lapangan', [
            ['C1', 'Mencegah dan mengendalikan bahaya kimia/biohazard', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mencegah dan mengendalikan gas, asap, dan kotoran', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Memelihara kebersihan, kesehatan, dan ketertiban area kerja', 15.00, 'benefit', 'kualitatif', null, null],
            ['C4', 'Mencegah hilangnya pendapatan akibat insiden kerja', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Memberikan pertolongan kecelakaan kerja (Respons Time)', 20.00, 'cost', 'kuantitatif', 'Menit', 5.00], 
        ]],
        ['Logistik / Gudang', 'Lapangan', [
            ['C1', 'Mengawasi dan mengelola operasional gudang', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mengawasi keluar-masuk tool gudang sesuai SOP', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Mengonfirmasi jumlah dan kondisi barang sesuai SOP', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Merencanakan pengadaan logistik dan distribusi barang', 40.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['SPV Sipil', 'Lapangan', [
            ['C1', 'Memeriksa laporan shift (log book) tiap hari kerja', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mencatat dan mendata sipil equipment', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Mengomunikasikan masalah lapangan kepada atasan', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Memelihara dan mengoperasikan peralatan/bangunan sipil', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Memeriksa dan memastikan pekerjaan selesai dengan baik', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['SPV Mechanical', 'Lapangan', [
            ['C1', 'Memelihara dan mengoperasikan mesin/peralatan mechanical', 27.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Mematuhi SOP dan keselamatan kerja mekanikal', 32.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Menyelesaikan pekerjaan mekanikal sesuai spesifikasi dan jadwal', 25.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Berkoordinasi dengan tim dan melaporkan masalah lapangan', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['SPV Electrical', 'Lapangan', [
            ['C1', 'Mengoordinasi perbaikan dan perawatan mesin produksi elektrikal', 27.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Menginformasikan trouble/masalah elektrikal ke atasan', 17.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Menjaga kepatuhan tim terhadap aturan dasar & teknis elektrikal', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Menjaga performance mesin dan kerja tim di lapangan', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Membina bawahan (foreman, leader, operator)', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['SPV Piping', 'Lapangan', [
            ['C1', 'Meninjau dan menugaskan pekerjaan piping', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Melakukan kontrol dan evaluasi pekerjaan piping', 22.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Membuat laporan pekerjaan tepat waktu dan akurat', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Memberikan arahan kepada bawahan', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Memberikan bimbingan, bantuan, dan umpan balik konstruktif ke tim', 20.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
        ['SPV Konstruksi', 'Lapangan', [
            ['C1', 'Memastikan pelaksanaan tugas lapangan sesuai biaya, mutu, waktu (BMW)', 32.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C2', 'Membuat program kerja mingguan untuk staf di bawahnya', 17.50, 'benefit', 'kuantitatif', '%', 100.00],
            ['C3', 'Memahami dan menerapkan desain konstruksi secara teknis', 10.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C4', 'Menyusun metode pelaksanaan sesuai kondisi lapangan', 25.00, 'benefit', 'kuantitatif', '%', 100.00],
            ['C5', 'Melakukan evaluasi dan pelaporan progres ke atasan', 15.00, 'benefit', 'kuantitatif', '%', 100.00],
        ]],
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

        // 1. Seed Divisi & Kriteria
        $hse = null;
        foreach ($this->divisiData as $row) {
            [$nama, $kelompok, $kpis] = $row;
            $divisi = Divisi::create([
                'nama_divisi' => $nama,
                'kelompok_kerja' => $kelompok,
            ]);

            if ($nama === 'HSE (Health, Safety, Environment)') {
                $hse = $divisi;
            }

            foreach ($kpis as [$kode, $namaK, $bobot, $atribut, $tipe, $satuan, $target]) {
                $k = Kriteria::create([
                    'id_divisi' => $divisi->id,
                    'kode_kriteria' => $kode,
                    'nama_kriteria' => $namaK,
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
        }

        // 2. Seed Users
        User::create(['nama' => 'Admin HRD', 'username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        $kadiv = User::create(['nama' => 'Kepala Divisi HSE', 'username' => 'kadiv', 'password' => Hash::make('kadiv123'), 'role' => 'kadiv', 'id_divisi' => $hse->id]);
        User::create(['nama' => 'Direktur Utama', 'username' => 'pimpinan', 'password' => Hash::make('pimpinan123'), 'role' => 'pimpinan']);

        $uA = User::create(['nama' => 'Pegawai A', 'username' => 'pegawaia', 'password' => Hash::make('pegawaia123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);
        $uB = User::create(['nama' => 'Pegawai B', 'username' => 'pegawaib', 'password' => Hash::make('pegawaib123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);
        $uC = User::create(['nama' => 'Pegawai C', 'username' => 'pegawaic', 'password' => Hash::make('pegawaic123'), 'role' => 'pegawai', 'id_divisi' => $hse->id]);

        // 3. Seed Pegawai
        $pA = Pegawai::create(['nik' => 'HSE001', 'nama' => 'Pegawai A', 'jabatan' => 'Safety Officer', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-01-01', 'id_divisi' => $hse->id, 'id_user' => $uA->id]);
        $pB = Pegawai::create(['nik' => 'HSE002', 'nama' => 'Pegawai B', 'jabatan' => 'Safety Inspector', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'L', 'tanggal_masuk' => '2023-03-01', 'id_divisi' => $hse->id, 'id_user' => $uB->id]);
        $pC = Pegawai::create(['nik' => 'HSE003', 'nama' => 'Pegawai C', 'jabatan' => 'Safety Admin', 'status' => 'Aktif', 'status_pegawai' => 'Kontrak', 'jenis_kelamin' => 'P', 'tanggal_masuk' => '2023-06-01', 'id_divisi' => $hse->id, 'id_user' => $uC->id]);

        // 4. Seed Periode
        $periode = Periode::create(['nama_periode' => 'Semester 1 2026', 'tanggal_mulai' => '2026-01-01', 'tanggal_selesai' => '2026-06-30', 'status' => 'aktif']);

        // 5. Data Laporan
        $penA = Penilaian::create(['id_pegawai' => $pA->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realA = [
            'C1' => [100.0, 5.0], 
            'C2' => [100.0, 4.0],
            'C3' => [null, 5.0],  
            'C4' => [100.0, 5.0],
            'C5' => [3.0, 4.0],   
        ];

        $penB = Penilaian::create(['id_pegawai' => $pB->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realB = [
            'C1' => [90.0, 4.0],
            'C2' => [100.0, 4.0],
            'C3' => [null, 3.0],
            'C4' => [80.0, 4.0],
            'C5' => [3.0, 4.0],
        ];

        $penC = Penilaian::create(['id_pegawai' => $pC->id, 'id_user' => $kadiv->id, 'id_periode' => $periode->id, 'status_penilaian' => 'approved']);
        $realC = [
            'C1' => [50.0, 2.0],
            'C2' => [80.0, 3.0],
            'C3' => [null, 2.0],
            'C4' => [40.0, 2.0],
            'C5' => [4.0, 3.0],
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

        // SMART kalkulasi
        $smart = app(\App\Services\SmartService::class);
        $smart->prosesPeriode($periode->id);
    }
}