<?php

use App\Http\Controllers\Admin\DetailRencanaController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\KodeController as AdminKodeController;
use App\Http\Controllers\Admin\SatuanController as AdminSatuanController;
use App\Http\Controllers\Admin\AnggaranController as AdminAnggaranController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Admin\UsulanController as AdminUsulanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\RPDanaController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Unit\RencanaPenarikanDanaController as UnitRencanaPenarikanDanaController;
use App\Http\Controllers\Direksi\MonitoringController as DireksiMonitoringController;
use App\Http\Controllers\Direksi\ProfileController as DireksiProfileController;
use App\Http\Controllers\Direksi\ReportController as DireksiReportController;
use App\Http\Controllers\Unit\HistoriController;
use App\Http\Controllers\Unit\UsulanController as UnitUsulanController;
use App\Http\Controllers\Unit\MonitoringController as UnitMonitoringController;
use App\Http\Controllers\Unit\ProfileController as UnitProfileController;
use App\Http\Controllers\Unit\ReportController as UnitReportController;
use App\Http\Controllers\UserController;
use App\Models\DetailRencana;
use Illuminate\Auth\Notifications\ResetPassword;
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
Route::get('/logout',[AuthLoginController::class, 'logout'])->name('logout1');

Auth::routes();
// REDIRECT HOME
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// REDIRECT PROFILE
Route::get('/profile', [App\Http\Controllers\SuperAdmin\ProfileController::class, 'redirectProfile'])->name('profile.redirect');

Route::middleware(['auth', 'user-access:super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', [HomeController::class, 'superadminHome'])->name('superadmin.dashboard');
    Route::get('/superadmin/user',  [UserController::class, 'index'])->name('superadmin.user');
    Route::post('/superadmin/tambah-user', [UserController::class, 'store'])->name('superadmin.tambah_user');
    Route::post('/superadmin/edit-user', [UserController::class, 'edit'])->name('superadmin.edit_user');
    Route::post('/superadmin/hapus-user', [UserController::class, 'destroy'])->name('superadmin.hapus_user');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('reset-password');
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
    // ANGGARAN
    Route::get('/admin/anggaran',  [AdminAnggaranController::class, 'index'])->name('admin.anggaran');
    Route::post('/admin/simpan-anggaran', [AdminAnggaranController::class, 'store'])->name('admin.simpan_anggaran');
    Route::post('/admin/edit-anggaran', [AdminAnggaranController::class, 'edit'])->name('admin.edit_anggaran');
    Route::post('/admin/hapus-anggaran', [AdminAnggaranController::class, 'destroy'])->name('admin.hapus_anggaran');
    // Usulan di admin
    Route::get('/admin/usulan',  [AdminUsulanController::class, 'index'])->name('admin.usulan');
    Route::get('/admin/datatabell',  [AdminUsulanController::class, 'tabelAwalRencana'])->name('admin.tabelRencanaAwal');
    Route::get('/admin/datatabel2',  [AdminUsulanController::class, 'tabelRencana'])->name('admin.tabelRencana');
    // DETAIL RENCANA SETIAP PENGAJUAN UNIT
    Route::get('/admin/detail-rencana', [AdminUsulanController::class, 'show'])->name('admin.show_rencana');
    Route::get('/admin/edit-rencana', [AdminUsulanController::class, 'edit'])->name('admin.edit_rencana');
    Route::get('/admin/search/code', [AdminUsulanController::class, 'searchByCode'])->name('admin.search_code');
    Route::get('/admin/tabeldetail', [DetailRencanaController::class, 'tabelDetail'])->name('admin.tabeldetail');
    Route::get('/admin/tabeleditRA', [DetailRencanaController::class, 'tabeleditRA'])->name('admin.tabeleditRA');
    Route::get('/admin/tabelRevisi', [DetailRencanaController::class, 'tabelRevisi'])->name('admin.tabelRevisi');
    Route::get('/admin/editRA', [DetailRencanaController::class, 'editRencAwal'])->name('admin.editRencAwal');

    // LENGKAPI RENCANA NYA UNIT
    Route::post('/admin/simpan-rencanaLengkap', [DetailRencanaController::class, 'storelengkapiRencana'])->name('admin.simpan_rencanaLengkap');
    Route::get('/rencana/check-status', [DetailRencanaController::class, 'checkStatus'])->name('rencana.checkStatus');
    Route::get('/admin/edit-lrencana',  [DetailRencanaController::class, 'editLrencana'])->name('admin.edit_Lrencana');
    Route::post('/admin/hapus-usulan', [DetailRencanaController::class, 'destroy'])->name('admin.hapus_usulan');
    Route::post('/admin/simpan-parent', [DetailRencanaController::class, 'storeParent'])->name('admin.simpan_parent');
    // RENCANA AWAL
    Route::post('/admin/buka-rencana', [AdminUsulanController::class, 'store'])->name('admin.bukaRencana');
    Route::post('/admin/simpan-RA', [DetailRencanaController::class, 'storeEditRA'])->name('admin.simpan_RA');
    Route::post('/admin/hapus-RA', [AdminUsulanController::class, 'destroyRA'])->name('admin.hapus_RA');
    Route::post('/admin/simpan-ket', [AdminUsulanController::class, 'storeKet'])->name('admin.simpan_ketUsulan');
    // REALISASI
    Route::get('/admin/RPD',  [RPDanaController::class, 'rpd'])->name('admin.realisasi');
    Route::post('/admin/simpan-validasi', [RPDanaController::class, 'storevalidasi'])->name('admin.simpan_validasiRPD');

    //MONITORING
    Route::get('/admin/monitoring',  [AdminMonitoringController::class, 'index'])->name('admin.monitoring');
    Route::post('/admin/update-realisasi', [AdminMonitoringController::class, 'updateRealisasi'])->name('admin.updateRealisasi');
    Route::post('/admin/simpan-realisasi', [AdminMonitoringController::class, 'store'])->name('admin.simpan_realisasi');
    Route::post('/admin/delete-realisasi', [AdminMonitoringController::class, 'deleteRealisasi'])->name('admin.deleteRealisasi');
    Route::get('/admin/show-realisasi', [AdminMonitoringController::class, 'getRealisasi'])->name('admin.getRealisasi');
    Route::get('/admin/data-anggaran', [AdminMonitoringController::class, 'dataAnggaran'])->name('admin.dataAnggaran');
    Route::get('/admin/detail-monitoring', [AdminMonitoringController::class, 'show'])->name('admin.show_monitoring');


    // PROFILE
    Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile');
    // REPORT
    Route::get('/admin/report', [AdminReportController::class, 'index'])->name('admin.report');
    Route::get('/admin/report/export-kode', [AdminKodeController::class, 'export_kode'])->name('admin.export_kode');
    Route::GET('/admin/report/export-rencana', [AdminReportController::class, 'exportRencana'])->name('admin.export_Rencana');
});


Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    // dashboard
    Route::get('/direksi/dashboard', [HomeController::class, 'direksiHome'])->name('direksi.dashboard');
    // monitoring
    Route::get('/direksi/monitoring', [DireksiMonitoringController::class, 'index'])->name('direksi.monitoring');
    Route::get('/direksi/show-realisasi', [DireksiMonitoringController::class, 'getRealisasi'])->name('direksi.getRealisasi');
    Route::get('/direksi/all-anggaran', [DireksiMonitoringController::class, 'allAnggaran'])->name('direksi.allAnggaran');
    Route::get('/direksi/data-anggaran', [DireksiMonitoringController::class, 'dataAnggaran'])->name('direksi.dataAnggaran');
    Route::get('/direksi/detail-monitoring', [DireksiMonitoringController::class, 'show'])->name('direksi.show_monitoring');
    // profile
    Route::get('/direksi/profile', [DireksiProfileController::class, 'index'])->name('direksi.profile');
    // report
    Route::get('/direksi/report', [DireksiReportController::class, 'index'])->name('direksi.report');
    Route::post('/direksi/report/export-rencana-unit', [DireksiReportController::class, 'exportRencanaUnit'])->name('direksi.export_rencanaUnit');
    Route::GET('/direksi/report/export-rencana', [DireksiReportController::class, 'exportRencana'])->name('direksi.export_Rencana');
});

Route::middleware(['auth', 'user-access:unit'])->group(function () {
    Route::get('/unit/dashboard', [HomeController::class, 'unitHome'])->name('unit.dashboard');
    // CRUD rencana
    Route::get('/unit/usulan', [UnitUsulanController::class, 'index'])->name('unit.usulan');
    Route::get('/unit/tabel1', [UnitUsulanController::class, 'tabel1'])->name('unit.new');
    Route::get('/unit/tabel2', [UnitUsulanController::class, 'tabel2'])->name('unit.last');
    Route::post('/unit/simpan-rencana', [UnitUsulanController::class, 'store'])->name('unit.simpan_tahun');
    Route::post('/unit/simpan-rencana2', [UnitUsulanController::class, 'store2'])->name('unit.simpan_rencana2');
    Route::post('/unit/edit-usulan/', [UnitUsulanController::class, 'edit'])->name('unit.edit_usulan');
    Route::put('/unit/update-usulan/{id}', [UnitUsulanController::class, 'update'])->name('unit.update_usulan');
    Route::get('/unit/search/code', [UnitUsulanController::class, 'searchByCode'])->name('unit.search_code');
    Route::post('/unit/hapus-usulan', [UnitUsulanController::class, 'destroy'])->name('unit.hapus_usulan');
    Route::get('/unit/check-anggaran', [UnitUsulanController::class, 'checkAnggaran'])->name('unit.checkAnggaran');
    Route::get('/unit/check-status-rencana', [UnitUsulanController::class, 'checkStatus'])->name('unit.checkStatus');
    // Menu Histori
    Route::get('/unit/histori', [HistoriController::class, 'index'])->name('unit.histori');
    Route::get('/unit/detail-histori', [HistoriController::class, 'showHistori'])->name('unit.show_histori');
    Route::get('/unit/tabel-histori', [HistoriController::class, 'detailHistori'])->name('unit.detailHistori');
    // CRUD RPD
    Route::get('/unit/rpd', [UnitRencanaPenarikanDanaController::class, 'index'])->name('unit.rpd');
    Route::get('/get-detail-rencana', [UnitRencanaPenarikanDanaController::class, 'getDetailRencana'])->name('unit.getDetailRencana');

    Route::post('/unit/simpan-skedul', [UnitRencanaPenarikanDanaController::class, 'storeRPD'])->name('unit.simpan_skedul');
    Route::post('/unit/update-realisasi', [UnitRencanaPenarikanDanaController::class, 'updateRPD'])->name('unit.updateRPD');
    Route::get('/unit/edit-realisasi', [UnitRencanaPenarikanDanaController::class, 'edit'])->name('unit.editRPD');
    // MONITORING RPD
    Route::get('/unit/monitoring', [UnitMonitoringController::class, 'index'])->name('unit.monitoring');
    Route::get('/unit/show-realisasi', [UnitMonitoringController::class, 'getRealisasi'])->name('unit.getRealisasi');
    Route::get('/unit/all-anggaran', [UnitMonitoringController::class, 'allAnggaran'])->name('unit.allAnggaran');
    Route::get('/unit/data-anggaran', [UnitMonitoringController::class, 'dataAnggaran'])->name('unit.dataAnggaran');
    Route::get('/unit/detail-monitoring', [UnitMonitoringController::class, 'show'])->name('unit.show_monitoring');
    // PROFILE
    Route::get('/unit/profile', [UnitProfileController::class, 'index'])->name('unit.profile');
    // REPORT
    Route::get('/unit/report', [UnitReportController::class, 'index'])->name('unit.report');
    Route::get('/unit/report-rencana', [UnitReportController::class, 'exportRencana'])->name('unit.export_rencana');
});
