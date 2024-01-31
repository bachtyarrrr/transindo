<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// })->name('login');

Route::get('dashboard', [CustomAuthController::class, 'dashboard']);
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

Route::get('tambah', [CustomAuthController::class, 'tambah']);
Route::post('proses_tambah', [CustomAuthController::class, 'proses_tambah']);
Route::get('/cari-mobil',  [CustomAuthController::class, 'cari_mobil']);
Route::get('/pinjam',  [CustomAuthController::class, 'pinjam_page']);
Route::get('/daftar-sewa-user',  [CustomAuthController::class, 'daftar_sewa_user']);
Route::get('/pengembalian',  [CustomAuthController::class, 'daftar_pengembalian']);
Route::get('/daftar-pengembalian',  [CustomAuthController::class, 'daftar_pengembalian']);
Route::post('/proses-pengembalian', [CustomAuthController::class, 'pengembalianMobil']);

Route::post('/pesan-mobil', [CustomAuthController::class, 'pesanMobil']);
