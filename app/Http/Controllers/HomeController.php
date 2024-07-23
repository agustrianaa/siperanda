<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\DetailRencana;
use App\Models\Realisasi;
use App\Models\Rencana;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'super_admin') {
            alert()->toast('Hello ' . '<b>' . Auth::user()->email . '</b>' . ', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('superadmin.dashboard');
        } else if (auth()->user()->role == 'admin') {
            alert()->toast('Hello ' . '<b>' . Auth::user()->email . '</b>' . ', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('admin.dashboard');
        } else if (auth()->user()->role == 'direksi') {
            alert()->toast('Hello ' . '<b>' . Auth::user()->email . '</b>' . ', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('direksi.dashboard');
        } else if (auth()->user()->role == 'unit') {
            alert()->toast('Hello ' . '<b>' . Auth::user()->email . '</b>' . ', Selamat Datang Kembali!', 'success')->position('top-end');
            return redirect()->route('unit.dashboard');
        }
    }

    public function superadminHome()
    {
        $totalUser = User::count('id');
        return view('super_admin.dashboard', compact('totalUser'));
    }
    private function getLatestYear($unitId = null)
    {
        if ($unitId) {
            $latestYear = Rencana::where('unit_id', $unitId)->max('tahun');
        } else {
            $latestYear = Rencana::max('tahun');
        }

        if (!$latestYear) {
            $latestYear = now()->year;
        }

        return $latestYear;
    }

    public function adminHome()
    {
        $latestYear = $this->getLatestYear();
        $totalAnggaran = DetailRencana::whereHas('rencana', function ($query) use ($latestYear) {
            $query->where('tahun', $latestYear);
        })->sum('total');
        $totalRKA = Rencana::whereYear('tahun', $latestYear)->count('id');
        $totalUnit = Unit::count('id');
        $totalRealisasi = Realisasi::whereHas('detailRencana', function ($query) use ($latestYear) {
            $query->whereHas('rencana', function ($subQuery) use ($latestYear) {
                $subQuery->where('tahun', $latestYear);
            });
        })->sum('jumlah');
        $sisaAnggaran = $totalAnggaran - $totalRealisasi;

        return view('admin.dashboard', compact('totalAnggaran', 'totalRKA', 'totalUnit', 'totalRealisasi', 'sisaAnggaran', 'latestYear'));
    }

    public function direksiHome()
    {
        $latestYear = $this->getLatestYear();

        $totalAnggaran = DetailRencana::whereHas('rencana', function ($query) use ($latestYear) {
            $query->where('tahun', $latestYear);
        })->sum('total');
        $totalRKA = Rencana::whereYear('tahun', $latestYear)->count('id');
        $totalRealisasi = Realisasi::whereHas('detailRencana', function ($query) use ($latestYear) {
            $query->whereHas('rencana', function ($subQuery) use ($latestYear) {
                $subQuery->where('tahun', $latestYear);
            });
        })->sum('jumlah');
        $sisaAnggaran = $totalAnggaran - $totalRealisasi;

        return view('direksi.dashboard', compact('totalAnggaran', 'totalRKA', 'totalRealisasi', 'sisaAnggaran'));
    }

    public function unitHome()
    {
        $user = Auth::user();
        $unitId = $user->unit->id;

        $latestYear = $this->getLatestYear($unitId);

        $totalAnggaran = DetailRencana::whereHas('rencana', function ($query) use ($unitId, $latestYear) {
            $query->where('unit_id', $unitId)
                ->where('tahun', $latestYear);
        })->sum('total');

        $totalRencana = Rencana::where('unit_id', $unitId)
            ->where('tahun', $latestYear)
            ->count();

        $totalRealisasi = Realisasi::whereHas('detailRencana', function ($query) use ($unitId, $latestYear) {
            $query->whereHas('rencana', function ($subQuery) use ($unitId, $latestYear) {
                $subQuery->where('unit_id', $unitId)
                    ->where('tahun', $latestYear);
            });
        })->sum('jumlah');

        $sisaAnggaran = $totalAnggaran - $totalRealisasi;

        return view('unit.dashboard', compact('totalAnggaran', 'totalRencana', 'totalRealisasi', 'sisaAnggaran'));
    }
}
