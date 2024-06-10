<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Realisasi;
use App\Models\RPD;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as idRencana',
                'detail_rencana.volume as volume',
                'rencana.*',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                'kode_komponen.kode as kodeUsulan',
                'satuan.*',
                'satuan.satuan as satuan',
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->get();

                foreach ($rencana as $data) {
                    $data->rpds = RPD::where('detail_rencana_id', $data->idRencana)->get();
                }
                foreach ($rencana as $data) {
                    $data->realisasi = Realisasi::where('detail_rencana_id', $data->idRencana)->get();
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
            ->addColumn('bulan_realisasi', function ($row) {
                $bulans = [];
                // Memastikan properti rpds adalah sebuah array
                if (!is_null($row->realisasi)) {
                    foreach ($row->realisasi as $data) {
                        $bulans[] = $data->bulan_realisasi;
                    }
                }
                return implode(', ', $bulans);
            })
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="tambahRealisasi(' . $id . ')" class="realisasi btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="editRealisasi(' . $id . ')" class="realisasi btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="hapusRealisasi(' . $id . ')" class="realisasi btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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

                ->addColumn('ket', function ($row) {
                    $id = $row->idRencana; // Ambil ID dari baris data
                    $ket =  '<a href="javascript:void(0)" onClick="show(' . $id . ')" class="show btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    return $ket;
                })
                ->rawColumns(['action', 'ket'])
                ->make(true);
        }
        return view('admin.monitoring');
    }

    public function store(Request $request){
        $realisasi = new Realisasi();
        $realisasi->detail_rencana_id = $request->detail_rencana_id;
        $realisasi->bulan_realisasi = $request->bulan_realisasi;
        $realisasi->jumlah = $request->jumlah;
        $realisasi->save();

        return response()->json($realisasi);
    }
}
