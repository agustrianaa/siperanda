<?php

namespace App\Http\Controllers\Direksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(){
        return view('direksi.monitoring');
    }
}
