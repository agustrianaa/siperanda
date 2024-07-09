<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnggaranController extends Controller
{
    public function index(){
        if(request()->ajax()){
            $anggaran = DB::table('anggaran')
            ->leftJoin('rencana', 'anggaran.tahun', '=', 'rencana.tahun')
            ->leftJoin('detail_rencana', 'rencana.id', '=', 'detail_rencana.rencana_id')
            ->leftJoin('realisasi', 'detail_rencana.id', '=', 'realisasi.detail_rencana_id')
            ->select(
                'anggaran.id',
                'anggaran.tahun',
                'anggaran.all_anggaran',
                DB::raw('SUM(realisasi.jumlah) as total_realisasi'),
                DB::raw('anggaran.all_anggaran - COALESCE(SUM(realisasi.jumlah), 0) as sisaAnggaran')
            )
            ->groupBy('anggaran.id', 'anggaran.tahun', 'anggaran.all_anggaran')
            ->get();
            return datatables()->of($anggaran)
            ->addColumn('action', function ($row) {
                $id = $row->id; // Ambil ID dari baris data
                $action =  '<a href="javascript:void(0)" onClick="editAnggaran(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('admin.anggaran');
    }

    public function store(Request $request){
        $anggaranId = $request -> id;

        $anggaran = Anggaran::updateOrCreate([
            'id' => $anggaranId,
        ],
        [
            'all_anggaran'=> $request->all_anggaran,
            'tahun' => $request->tahun . '-01-01',
        ]
    );
    return Response()->json($anggaran);
    }

    public function edit(Request $request){
        $id = array('id' => $request->id);
        $anggaran  = Anggaran::where($id)->first();

        if($anggaran){
            $anggaran->tahun = substr($anggaran->tahun, 0 ,4);
        }
        return Response()->json($anggaran);
    }
}
