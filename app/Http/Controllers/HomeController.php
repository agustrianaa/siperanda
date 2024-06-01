<?php

namespace App\Http\Controllers;

use App\Models\Rencana;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'super_admin'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('superadmin.dashboard');
        } else if (auth()->user()->role == 'admin'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('admin.dashboard');
        } else if (auth()->user()->role == 'direksi'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('direksi.dashboard');
        } else if (auth()->user()->role == 'unit'){
            alert()->toast('Hello '. '<b>'.Auth::user()->email .'</b>' .', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('unit.dashboard');
        }


    }

    public function superadminHome(){
        return view('super_admin.dashboard');
    }

    public function dminHome(){
        return view('admin.dashboard');
    }

    public function direksiHome(){
        return view('direksi.dashboard');
    }

    public function unitHome(){
        $user = Auth::user();
        $unitId = $user->unit->id;

        $totalAnggaran = Rencana::where('unit_id', $unitId)
        ->sum('jumlah');

        return view('unit.dashboard', compact('totalAnggaran'));
    }



}
