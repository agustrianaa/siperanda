<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsulanController extends Controller
{
    public function index(){
        return view('admin.usulan.usulan');
    }
    public function realisasi(){
        return view('admin.usulan.realisasi');
    }
}
