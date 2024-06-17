<?php

use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\KodeController as AdminKodeController;
use App\Http\Controllers\Admin\SatuanController as AdminSatuanController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Admin\UsulanController as AdminUsulanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RPDanaController;
use App\Http\Controllers\Unit\RencanaPenarikanDanaController as UnitRencanaPenarikanDanaController;
use App\Http\Controllers\Direksi\MonitoringController as DireksiMonitoringController;
use App\Http\Controllers\Direksi\ProfileController as DireksiProfileController;
use App\Http\Controllers\Unit\HistoriController;
use App\Http\Controllers\Unit\UsulanController as UnitUsulanController;
use App\Http\Controllers\Unit\MonitoringController as UnitMonitoringController;
use App\Http\Controllers\Unit\ProfileController as UnitProfileController;
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

Auth::routes();
// REDIRECT HOME
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// REDIRECT PROFILE
Route::get('/profile', [App\Http\Controllers\SuperAdmin\ProfileController::class, 'redirectProfile'])->name('profile.redirect');

Route::middleware(['auth', 'user-access:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [HomeController::class, 'superadminHome'])->name('superadmin.dashboard');
    Route::get('/user',  [UserController::class, 'index'])->name('superadmin.tambah_user');
    Route::post('/tambah-user', [UserController::class, 'store'])->name('superadmin.tambah_user');
    Route::post('/edit-user', [UserController::class, 'edit'])->name('superadmin.edit_user');
    Route::post('/hapus-user', [UserController::class, 'destroy'])->name('superadmin.hapus_user');
    Route::get('/superadmin/profile', [SuperAdminProfileController::class, 'index'])->name('superadmin.profile');
});


Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'adminHome'])->name('admin.dashboard');
    // CRUD kategori
    Route::get('/admin/kategori',  [AdminKategoriController::class, 'index'])->name('admin.kategori');
    Route::post('/admin/tambah-kategori', [AdminKategoriController::class, 'store'])->name('admin.tambah_kategori');
    Route::post('/admin/edit-kategori', [AdminKategoriController::class, 'edit'])->name('admin.edit_kategori');
    Route::post('/admin/hapus-kategori', [AdminKategoriController::class, 'destroy'])->name('admin.hapus_kategori');
    // CRUD kode
    Route::get('/admin/kode',  [AdminKodeController::class, 'index'])->name('admin.kode');
    Route::post('/admin/edit-kode', [AdminKodeController::class, 'edit'])->name('admin.edit_kode');
    Route::post('/admin/simpan-kode', [AdminKodeController::class, 'store'])->name('admin.simpan_kode');
    Route::get('/admin/search/code_parent', [AdminKodeController::class, 'searchByCode'])->name('admin.search_codeParent');
    Route::post('/admin/hapus-kode', [AdminKodeController::class, 'destroy'])->name('admin.hapus_kode');
    // CRUD satuan
    Route::get('/admin/satuan',  [AdminSatuanController::class, 'index'])->name('admin.satuan');
    Route::post('/admin/tambah-satuan', [AdminSatuanController::class, 'store'])->name('admin.tambah_satuan');
    Route::post('/admin/edit-satuan', [AdminSatuanController::class, 'edit'])->name('admin.edit_satuan');
    Route::post('/admin/hapus-satuan', [AdminSatuanController::class, 'destroy'])->name('admin.hapus_satuan');
    // Usulan di admin
    Route::get('/admin/usulan',  [AdminUsulanController::class, 'index'])->name('admin.usulan');
    Route::get('/admin/datatabel',  [AdminUsulanController::class, 'tabelAwalRencana'])->name('admin.tabelRencanaAwal');
    Route::get('/admin/datatabe2',  [AdminUsulanController::class, 'tabelRencana'])->name('admin.tabelRencana');
    Route::post('/admin/buka-rencana', [AdminUsulanController::class, 'store'])->name('admin.bukaRencana');
    Route::post('/admin/simpan-ket', [AdminUsulanController::class, 'storeKet'])->name('admin.simpan_ketUsulan');
    Route::get('/admin/realisasi',  [RPDanaController::class, 'rpd'])->name('admin.realisasi');
    Route::post('/admin/simpan-validasi', [RPDanaController::class, 'storevalidasi'])->name('admin.simpan_validasiRPD');

    //MONITORING
    Route::get('/admin/monitoring',  [AdminMonitoringController::class, 'index'])->name('admin.monitoring');
    Route::post('/admin/simpan-realisasi', [AdminMonitoringController::class, 'store'])->name('admin.simpan_realisasi');
    // PROFILE
    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    // REPORT
    Route::get('/admin/report', [ReportController::class, 'index'])->name('admin.report');
    Route::get('/admin/report/export-kode', [AdminKodeController::class, 'export_kode'])->name('admin.export_kode');
});


Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    Route::get('/direksi/dashboard', [HomeController::class, 'direksiHome'])->name('direksi.dashboard');
    Route::get('/direksi/monitoring', [DireksiMonitoringController::class, 'index'])->name('direksi.monitoring');
    Route::get('/direksi/profile', [DireksiProfileController::class, 'index'])->name('direksi.profile');
});


Route::middleware(['auth', 'user-access:unit'])->group(function () {
    Route::get('/unit/dashboard', [HomeController::class, 'unitHome'])->name('unit.dashboard');
    // CRUD rencana
    Route::get('/unit/usulan', [UnitUsulanController::class, 'index'])->name('unit.usulan');
    Route::post('/unit/simpan-rencana', [UnitUsulanController::class, 'store'])->name('unit.simpan_tahun');
    Route::post('/unit/simpan-rencana2', [UnitUsulanController::class, 'store2'])->name('unit.simpan_rencana2');
    Route::post('/unit/edit-usulan/', [UnitUsulanController::class, 'edit'])->name('unit.edit_usulan');
    Route::put('/unit/update-usulan/{id}', [UnitUsulanController::class, 'update'])->name('unit.update_usulan');
    Route::get('/unit/search/code', [UnitUsulanController::class, 'searchByCode'])->name('unit.search_code');
    Route::post('/unit/hapus-usulan', [UnitUsulanController::class, 'destroy'])->name('unit.hapus_usulan');
    // Menu Histori
    Route::get('/unit/histori', [HistoriController::class, 'index'])->name('unit.histori');
    // CRUD RPD
    Route::get('/unit/rpd', [UnitRencanaPenarikanDanaController::class, 'index'])->name('unit.rpd');
    Route::post('/unit/simpan-skedul', [UnitRencanaPenarikanDanaController::class, 'storeRPD'])->name('unit.simpan_skedul');
    // MONITORING RPD
    Route::get('/unit/monitoring', [UnitMonitoringController::class, 'index'])->name('unit.monitoring');
    // PROFILE
    Route::get('/unit/profile', [UnitProfileController::class, 'index'])->name('unit.profile');
});

