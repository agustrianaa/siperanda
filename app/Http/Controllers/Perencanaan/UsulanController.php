<?php

namespace App\Http\Controllers\Perencanaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsulanController extends Controller
{
    public function index(){
        return view('admin.usulan.usulan');
    }
}
