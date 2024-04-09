<?php

use App\Http\Controllers\Auth\LoginController as AuthLoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
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
Route::middleware(['auth', 'user-access:super_admin'])->group(function () {
    // Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/user', function () {return view('super_admin.user');});
});
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    // Route::get('/admin/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
});
Route::middleware(['auth', 'user-access:direksi'])->group(function () {
    // Route::get('/direksi/dashboard', [HomeController::class, 'direksi'])->name('direksi.dashboard');
});
Route::middleware(['auth', 'user-access:unit'])->group(function () {
    // Route::get('/unit/dashboard', [HomeController::class, 'unit'])->name('unit.dashboard');
});

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
//     Route::get('/admin/dashboard', [HomeController::class, 'admin'])->name('admin.dashboard');
//     Route::get('/direksi/dashboard', [HomeController::class, 'direksi'])->name('direksi.dashboard');
//     Route::get('/unit/dashboard', [HomeController::class, 'unit'])->name('unit.dashboard');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
