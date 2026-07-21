<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\DivisiController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\KehadiranController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\HasilController as AdminHasil;
use App\Http\Controllers\Manajer\DashboardController as ManajerDashboard;
use App\Http\Controllers\Manajer\PenilaianController;
use App\Http\Controllers\Manajer\HasilController as ManajerHasil;
use App\Http\Controllers\Pimpinan\DashboardController as PimpinanDashboard;
use App\Http\Controllers\Pimpinan\HasilController as PimpinanHasil;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin/HRD
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/create', [PegawaiController::class, 'create'])->name('pegawai.create');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    Route::get('/divisi', [DivisiController::class, 'index'])->name('divisi.index');
    Route::post('/divisi', [DivisiController::class, 'store'])->name('divisi.store');
    Route::put('/divisi/{divisi}', [DivisiController::class, 'update'])->name('divisi.update');
    Route::delete('/divisi/{divisi}', [DivisiController::class, 'destroy'])->name('divisi.destroy');

    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
    Route::put('/kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
    Route::post('/kriteria/sub', [KriteriaController::class, 'storeSub'])->name('kriteria.sub.store');
    Route::delete('/kriteria/sub/{subKriteria}', [KriteriaController::class, 'destroySub'])->name('kriteria.sub.destroy');

    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::post('/kehadiran', [KehadiranController::class, 'store'])->name('kehadiran.store');
    Route::delete('/kehadiran/{kehadiran}', [KehadiranController::class, 'destroy'])->name('kehadiran.destroy');

    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::post('/periode/{periode}/toggle', [PeriodeController::class, 'toggleStatus'])->name('periode.toggle');
    Route::post('/periode/{periode}/proses', [PeriodeController::class, 'proses'])->name('periode.proses');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/hasil', [AdminHasil::class, 'index'])->name('hasil.index');
    Route::get('/hasil/pdf', [AdminHasil::class, 'pdf'])->name('hasil.pdf');
});

// Manajer
Route::middleware(['auth', 'role:manajer'])->prefix('manajer')->name('manajer.')->group(function () {
    Route::get('/dashboard', [ManajerDashboard::class, 'index'])->name('dashboard');

    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/{pegawai}/create', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian/{pegawai}', [PenilaianController::class, 'store'])->name('penilaian.store');

    Route::get('/hasil', [ManajerHasil::class, 'index'])->name('hasil.index');
});

// Pimpinan
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
    Route::get('/dashboard', [PimpinanDashboard::class, 'index'])->name('dashboard');
    Route::get('/hasil', [PimpinanHasil::class, 'index'])->name('hasil.index');
    Route::get('/hasil/pdf', [PimpinanHasil::class, 'pdf'])->name('hasil.pdf');
});