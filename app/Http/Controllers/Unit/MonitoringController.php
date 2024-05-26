<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index(){
        $user = Auth::user();
        if (request()->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'rencana.*',
                'kode_komponen.*',
                'satuan.*',
                'realisasi.*',
                'realisasi.realisasi as realisasi',
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->join('realisasi', 'realisasi.detail_rencana_id', '=', 'detail_rencana.id')
                ->where('rencana.unit_id', $unit->id)
                ->whereNotNull('realisasi.realisasi')
                ->get();
            return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    if($row->realisasi === 'disetujui'){
                        $action =  '<div class="edit btn btn-success m-1 btn-sm disabled">Disetujui</div>';
                    } else if($row->realisasi === 'pending') {
                        $action =  '<div class="edit btn btn-warning m-1 btn-sm disabled">Pending</div>';
                    } else if($row->realisasi === 'tidakdisetujui'){
                        $action =  '<div class="edit btn btn-danger m-1 btn-sm disabled">Tidak Disetujui</div>';
                    } else {
                        $action =  '<a href="javascript:void(0)" onClick="validasiUsulan(' . $id . ')" class="validasi btn btn-primary btn-sm"><i class="fas fa-plus"></i>  Validasi</a>';
                    }
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('unit.monitoring');
    }
}
