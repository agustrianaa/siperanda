<?php

use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\KodeController as AdminKodeController;
use App\Http\Controllers\Admin\SatuanController as AdminSatuanController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Admin\UsulanController as AdminUsulanController;
use App\Http\Controllers\Unit\RencanaPenarikanDanaController as UnitRencanaPenarikanDanaController;
use App\Http\Controllers\Direksi\MonitoringController as DireksiMonitoringController;
use App\Http\Controllers\Unit\UsulanController as UnitUsulanController;
use App\Http\Controllers\Unit\MonitoringController as UnitMonitoringController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/login',[AuthLoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login',[AuthLoginController::class, 'postlogin'])->name('login');
Route::get('/logout',[AuthLoginController::class, 'logout'])->name('logout');
// Route::post('/logout', function () {
//     Auth::logout();
//     return redirect('/');
// })->name('logout');

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');


Route::middleware(['auth', 'user-access:super_admin'])->group(function () {
    Route::get('/user',  [UserController::class, 'index'])->name('superadmin.tambah_user');
    Route::post('/tambah-user', [UserController::class, 'store'])->name('superadmin.tambah_user');
    Route::post('/edit-user', [UserController::class, 'edit'])->name('superadmin.edit_user');
    Route::post('/hapus-user', [UserController::class, 'destroy'])->name('superadmin.hapus_user');

});
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    // CRUD kategori
    Route::get('/admin/kategori',  [AdminKategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/admin/tambah-kategori', [AdminKategoriController::class, 'store'])->name('admin.tambah_kategori');
    Route::post('/admin/edit-kategori', [AdminKategoriController::class, 'edit'])->name('admin.edit_kategori');
    Route::post('/admin/hapus-kategori', [AdminKategoriController::class, 'destroy'])->name('admin.hapus_kategori');
    // CRUD kode
    Route::get('/admin/kode',  [AdminKodeController::class, 'index'])->name('admin.kode');
    Route::post('/admin/edit-kode', [AdminKodeController::class, 'edit'])->name('admin.edit_kode');
    Route::post('/admin/simpan-kode', [AdminKodeController::class, 'store'])->name('admin.simpan_kode');
    // CRUD satuan
    Route::get('/admin/satuan',  [AdminSatuanController::class, 'index'])->name('admin.satuan');
    Route::post('/admin/tambah-satuan', [AdminSatuanController::class, 'store'])->name('admin.tambah_satuan');
    Route::post('/admin/edit-satuan', [AdminSatuanController::class, 'edit'])->name('admin.edit_satuan');
    Route::post('/admin/hapus-satuan', [AdminSatuanController::class, 'destroy'])->name('admin.hapus_satuan');
    // Usulan di admin
    Route::get('/admin/usulan',  [AdminUsulanController::class, 'index'])->name('admin.usulan');
    Route::get('/realisasi',  [AdminUsulanController::class, 'rpd'])->name('admin.realisasi');
    Route::get('/admin/monitoring',  [AdminMonitoringController::class, 'index'])->name('admin.monitoring');

});
Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    Route::get('/direksi/monitoring', [DireksiMonitoringController::class, 'index'])->name('direksi.monitoring');
});
Route::middleware(['auth', 'user-access:unit'])->group(function () {
    // CRUD rencana
    Route::get('/unit/usulan', [UnitUsulanController::class, 'index'])->name('unit.usulan');
    Route::post('/unit/simpan-rencana', [UnitUsulanController::class, 'store'])->name('unit.simpan_tahun');
    Route::post('/unit/simpan-rencana2', [UnitUsulanController::class, 'store2'])->name('unit.simpan_rencana2');
    Route::post('/unit/edit-usulan', [UnitUsulanController::class, 'edit'])->name('unit.edit_usulan');
    Route::put('/unit/update-usulan/{id}', [UnitUsulanController::class, 'update'])->name('unit.update_usulan');
    Route::get('/unit/search/code', [UnitUsulanController::class, 'searchByCode'])->name('unit.search_code');
    Route::post('/unit/hapus-usulan', [UnitUsulanController::class, 'destroy'])->name('unit.hapus_usulan');
    // CRUD RPD
    Route::get('/unit/rpd', [UnitRencanaPenarikanDanaController::class, 'index'])->name('unit.rpd');
    Route::post('/unit/simpan-skedul', [UnitRencanaPenarikanDanaController::class, 'store'])->name('unit.simpan_skedul');

    // MONITORING RPD
    Route::get('/unit/monitoring', [UnitMonitoringController::class, 'index'])->name('unit.monitoring');
});

