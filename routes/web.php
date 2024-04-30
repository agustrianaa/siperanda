<?php

use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Perencanaan\UsulanController;
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
    Route::get('/usulan',  [UsulanController::class, 'index'])->name('admin.usulan');
});
Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    // Route::get('/direksi/dashboard', [HomeController::class, 'direksi'])->name('direksi.dashboard');
});
Route::middleware(['auth', 'user-access:unit'])->group(function () {
    // Route::get('/unit/dashboard', [HomeController::class, 'unit'])->name('unit.dashboard');
});

