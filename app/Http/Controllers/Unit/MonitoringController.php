<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (request()->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as id',
                'rencana.*',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                'realisasi.*',
                'satuan.*',
                'satuan.satuan as satuan',
                'rpd.*'
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->leftJoin('realisasi', 'realisasi.detail_rencana_id', '=', 'detail_rencana.id')
                ->leftJoin('rpd', 'rpd.detail_rencana_id', '=', 'detail_rencana.id')
                ->where('rencana.unit_id', $unit->id)
                ->get();
            return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<div class="edit btn btn-success m-1 btn-sm disabled">Diproses</div>';
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
        return view('unit.monitoring');
    }
}
