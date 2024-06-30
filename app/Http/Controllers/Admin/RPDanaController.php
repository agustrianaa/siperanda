<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\RPD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RPDanaController extends Controller
{
    public function rpd()
    {
        if (request()->ajax()) {
            $rencana = DetailRencana::select(
                'detail_rencana.id as idRencana',
                'detail_rencana.volume',
                'detail_rencana.harga',
                'detail_rencana.uraian as uraian_rencana',
                'kode_komponen.kode', // Kolom dari tabel kode_komponen
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.satuan', // Kolom dari tabel satuan
                'detail_rencana.total as jumlahUsulan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
            ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
            ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
            ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
            ->get();

            foreach ($rencana as $rpd) {
                $rpd->rpds = RPD::where('detail_rencana_id', $rpd->idRencana)->get();
            }
                return datatables()->of($rencana)
                ->addColumn('bulan_rpd', function ($row) {
                    $bulans = [];
                    // Memastikan properti rpds adalah sebuah array
                    if (!is_null($row->rpds)) {
                        foreach ($row->rpds as $rpd) {
                            $bulans[] = $rpd->bulan_rpd;
                        }
                    }
                    return implode(', ', $bulans);
                })
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

                ->rawColumns(['bulan_rpd','action'])
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
