<?php

use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\UsulanController as AdminUsulanController;
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
    Route::get('/admin/usulan',  [AdminUsulanController::class, 'index'])->name('admin.usulan');
    Route::get('/realisasi',  [AdminUsulanController::class, 'realisasi'])->name('admin.realisasi');
    Route::get('/admin/monitoring',  [AdminMonitoringController::class, 'index'])->name('admin.monitoring');
});
Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    Route::get('/direksi/monitoring', [DireksiMonitoringController::class, 'index'])->name('direksi.monitoring');
});
Route::middleware(['auth', 'user-access:unit'])->group(function () {
    Route::get('/unit/usulan', [UnitUsulanController::class, 'index'])->name('unit.usulan');
    Route::get('/unit/rpd', [UnitUsulanController::class, 'rpd'])->name('unit.rpd');
    Route::get('/unit/monitoring', [UnitMonitoringController::class, 'index'])->name('unit.monitoring');
});

