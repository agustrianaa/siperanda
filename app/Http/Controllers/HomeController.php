<?php

namespace App\Http\Controllers;

use App\Models\DetailRencana;
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

    public function adminHome(){
        $totalAnggaran = DetailRencana::sum('total');
        $totalRKA = Rencana::count('id');
        return view('admin.dashboard', compact('totalAnggaran', 'totalRKA'));
    }

    public function direksiHome(){
        $totalAnggaran = Rencana::sum('jumlah');
        $totalRKA = Rencana::count('id');
        return view('direksi.dashboard', compact('totalAnggaran', 'totalRKA'));
    }

    public function unitHome(){
        $user = Auth::user();
        $unitId = $user->unit->id;

        $totalAnggaran = Rencana::where('unit_id', $unitId)
        ->sum('jumlah');
        $totalRencana = Rencana::where('unit_id', $unitId)
        ->count();

        return view('unit.dashboard', compact('totalAnggaran', 'totalRencana'));
    }



}
