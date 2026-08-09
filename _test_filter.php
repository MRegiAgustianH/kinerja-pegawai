<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('username', 'admin')->first();
Auth::login($user);

// Test 1: Tanpa filter
$r = app()->handle(Illuminate\Http\Request::create('/admin/hasil?periode=1', 'GET'));
echo "Test 1 (no filter): " . $r->getStatusCode() . "\n";

// Test 2: Filter kelompok kerja Lapangan
$r = app()->handle(Illuminate\Http\Request::create('/admin/hasil?periode=1&kelompok_kerja=Lapangan', 'GET'));
echo "Test 2 (Lapangan): " . $r->getStatusCode() . "\n";

// Test 3: Filter divisi HSE (id=1)
$r = app()->handle(Illuminate\Http\Request::create('/admin/hasil?periode=1&divisi=1', 'GET'));
echo "Test 3 (HSE): " . $r->getStatusCode() . "\n";

// Test 4: Filter kelompok + divisi
$r = app()->handle(Illuminate\Http\Request::create('/admin/hasil?periode=1&kelompok_kerja=Lapangan&divisi=1', 'GET'));
echo "Test 4 (Lapangan+HSE): " . $r->getStatusCode() . "\n";

// Test 5: PDF dengan filter
$r = app()->handle(Illuminate\Http\Request::create('/admin/hasil/pdf?periode=1&kelompok_kerja=Lapangan&divisi=1', 'GET'));
echo "Test 5 (PDF Lapangan+HSE): " . $r->getStatusCode() . " | Type: " . $r->headers->get('Content-Type') . " | Len: " . strlen($r->getContent()) . "\n";

// Test 6: Pimpinan juga
$user = User::where('username', 'pimpinan')->first();
Auth::login($user);
$r = app()->handle(Illuminate\Http\Request::create('/pimpinan/hasil?periode=1&kelompok_kerja=Kantor', 'GET'));
echo "Test 6 (Pimpinan Kantor): " . $r->getStatusCode() . "\n";
$r = app()->handle(Illuminate\Http\Request::create('/pimpinan/hasil/pdf?periode=1&divisi=1', 'GET'));
echo "Test 7 (Pimpinan PDF HSE): " . $r->getStatusCode() . " | Type: " . $r->headers->get('Content-Type') . " | Len: " . strlen($r->getContent()) . "\n";