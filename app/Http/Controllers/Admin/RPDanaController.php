<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Realisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RPDanaController extends Controller
{
    public function rpd()
    {
        if (request()->ajax()) {
            $rencana = Realisasi::select(
                'realisasi.*',
                'realisasi.id as id',
                'detail_rencana.*',
                'detail_rencana.id as id_detail',
                'rencana.*',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                'satuan.*',
                'rpd.*'
            )
            ->join('detail_rencana', 'realisasi.detail_rencana_id', '=', 'detail_rencana.id')
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->leftJoin('rpd', 'rpd.detail_rencana_id', '=', 'detail_rencana.id')
                ->get();
                return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id_detail; // Ambil ID dari baris data
                    $action = '<div class="edit btn btn-success m-1 btn-sm disabled">Keterangan</div>';
                    return $action;
                    // if($row->realisasi === 'disetujui'){
                    //     $action =  '<div class="edit btn btn-success m-1 btn-sm disabled">Disetujui</div>';
                    // } else if($row->realisasi === 'pending') {
                    //     $action =  '<div class="edit btn btn-warning m-1 btn-sm disabled">Pending</div>';
                    // } else if($row->realisasi === 'tidakdisetujui'){
                    //     $action =  '<div class="edit btn btn-danger m-1 btn-sm disabled">Tidak Disetujui</div>';
                    // } else {
                    //     $action =  '<a href="javascript:void(0)" onClick="validasiUsulan(' . $id . ')" class="validasi btn btn-primary btn-sm"><i class="fas fa-plus"></i>  Validasi</a>';
                    // }

                })

                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.usulan.rpd');
    }

    public function storevalidasi(Request $request)
    {
        $id = $request->id;
        Log::info('ID received: ' . $id);
        $detailRencana = DetailRencana::findOrFail($id);
        $realisasi = Realisasi::where('detail_rencana_id', $detailRencana->id)->firstOrFail();


        if ($realisasi) {
            $realisasi->realisasi = $request->input('realisasi');
            $realisasi->save();
        }
        return response()->json($realisasi);
    }
}
