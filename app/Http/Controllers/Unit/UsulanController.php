<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Rencana;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsulanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::all();
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
                    $action =  '<a href="javascript:void(0)" onClick="tambahRencanaLain(' . $id . ')" class="add btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="editUsulan(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusUsulan(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('unit.rencana.usulan', compact('satuan'));
    }

    public function rpd()
    {
        if (request()->ajax()) {
            $kodeKomponen = DetailRencana::select(
                'detail_rencana.*',
                'rencana.*',
                'kode_komponen.*',
                'satuan.*',
                // 'realisasi.*',
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                // ->join('rencana', 'realisasi.rencana_id', '=', 'rencana.id')
                ->get();
            return datatables()->of($kodeKomponen)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    // $action =  '<a href="javascript:void(0)" onClick="detialUsulan(' . $id . ')" class="add btn btn-success btn-sm"><i class="fas fa-eye"></i></a>';
                    // $action .=  '<a href="javascript:void(0)" onClick="editRPD(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    // $action .= '<a href="javascript:void(0)" onClick="hapusRPD(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    $action = '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm">Tambah RPD</a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('unit.rencana.realisasi');
    }

    public function store(Request $request)
    {
        $rencanaId = $request->id;

        $rencana = Rencana::updateOrCreate(
            [
                'id' => $rencanaId,
            ],
            [
                'tahun' => $request->input('year') . '-01-01',
            ]
        );
        DetailRencana::updateOrCreate(
            ['rencana_id' => $rencanaId]
        );
        return Response()->json($rencana);
    }

    public function store2(Request $request)
    {
        $rencanaId = $request->input('rencana_id');
        $kodeKomponenId = $request->input('kode_komponen_id');
        $satuanId = $request->input('satuan_id');

        $rencana = DetailRencana::updateOrCreate(
            [
                'id' => $request->input('id'),

            ],
            [
                'rencana_id' => $rencanaId,
                'kode_komponen_id' => $kodeKomponenId,
                'satuan_id' => $satuanId,
                'volume' => $request->input('volume'),
                'harga' => $request->input('harga'),
            ]
        );
        return Response()->json($rencana);
    }

    public function searchByCode(Request $request){
        Log::info('searchByCode called');
        $kode = $request->input('kode');
        Log::info('Kode: ' . $kode);

        $results = KodeKomponen::where('kode', 'LIKE', "%{$kode}%")->get();
        Log::info('Results: ' . $results);

        return response()->json($results);
    }
}
