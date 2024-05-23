<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(){
        if (request()->ajax()) {
            $kodeKomponen = DetailRencana::select(
                'detail_rencana.*',
                'rencana.*',
                'kode_komponen.*',
                'satuan.*'
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->get();
            return datatables()->of($kodeKomponen)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<div class="add btn btn-danger btn-sm disabled">Belum di verfikasi</i></div>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('unit.monitoring');
    }
}
