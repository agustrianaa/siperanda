<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'super_admin'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return view('super_admin.dashboard');
        } else if (auth()->user()->role == 'admin'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return view('admin.dashboard');
        } else if (auth()->user()->role == 'direksi'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return view('direksi.dashboard');
        } else if (auth()->user()->role == 'unit'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return view('unit.dashboard');
        }
    }

    // public function index() {
    //     return view('super_admin.dashboard');
    // }
    // public function admin() {
    //     return view('dmin.dashboard');
    // }
    // public function direksi() {
    //     return view('direksi.dashboard');
    // }
    // public function unit() {
    //     return view('unit.dashboard');
    // }
}
