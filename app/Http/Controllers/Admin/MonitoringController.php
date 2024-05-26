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
                ->whereNotNull('realisasi.realisasi')
                ->get();
            return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    if ($row->realisasi === 'disetujui') {
                        $action =  '<div class="edit btn btn-success m-1 btn-sm disabled">Disetujui</div>';
                    } else if ($row->realisasi === 'pending') {
                        $action =  '<div class="edit btn btn-warning m-1 btn-sm disabled">Pending</div>';
                    } else if ($row->realisasi === 'tidakdisetujui') {
                        $action =  '<div class="edit btn btn-danger m-1 btn-sm disabled">Tidak Disetujui</div>';
                    } else {
                        $action =  '<a href="javascript:void(0)" onClick="validasiUsulan(' . $id . ')" class="validasi btn btn-primary btn-sm"><i class="fas fa-plus"></i>  Validasi</a>';
                    }
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.monitoring');
    }
}
