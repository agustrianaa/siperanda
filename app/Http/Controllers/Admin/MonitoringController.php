<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as id',
                'detail_rencana.volume as volume',
                'rencana.*',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                'kode_komponen.kode as kodeUsulan',
                'realisasi.*',
                'satuan.*',
                'satuan.satuan as satuan',
                'rpd.*'
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->leftJoin('realisasi', 'realisasi.detail_rencana_id', '=', 'detail_rencana.id')
                ->leftJoin('rpd', 'rpd.detail_rencana_id', '=','detail_rencana.id')
                ->get();
            return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="tambahRealisasi(' . $id . ')" class="realisasi btn btn-primary btn-sm"><i class="fas fa-plus"></i>  Realisasi</a>';
                    // if ($row->realisasi === 'disetujui') {
                    //     $action =  '<div class="edit btn btn-success m-1 btn-sm disabled">Disetujui</div>';
                    // } else if ($row->realisasi === 'pending') {
                    //     $action =  '<div class="edit btn btn-warning m-1 btn-sm disabled">Pending</div>';
                    // } else if ($row->realisasi === 'tidakdisetujui') {
                    //     $action =  '<div class="edit btn btn-danger m-1 btn-sm disabled">Tidak Disetujui</div>';
                    // } else {
                    //     $action =  '<a href="javascript:void(0)" onClick="validasiUsulan(' . $id . ')" class="validasi btn btn-primary btn-sm"><i class="fas fa-plus"></i>  Validasi</a>';
                    // }
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.monitoring');
    }
}
