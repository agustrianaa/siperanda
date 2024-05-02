<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsulanController extends Controller
{
    public function index(){
        return view('unit.rencana.usulan');
    }
    public function rpd(){
        return view('unit.rencana.realisasi');
    }
}
