<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'super_admin'){
            return view('super_admin.dashboard');
        } else if (auth()->user()->role == 'admin'){
            return view('admin.dashboard');
        } else if (auth()->user()->role == 'direksi'){
            return view('direksi.dashboard');
        } else if (auth()->user()->role == 'unit'){
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
