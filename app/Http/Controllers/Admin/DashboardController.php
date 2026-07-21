<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\Pegawai;
use App\Models\Periode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'jumlahPegawai' => Pegawai::count(),
            'jumlahDivisi' => Divisi::count(),
            'jumlahPeriode' => Periode::count(),
            'periodeAktif' => Periode::where('status', 'aktif')->get(),
        ]);
    }
}