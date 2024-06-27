<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Rencana;
use Illuminate\Http\Request;

class DetailRencanaController extends Controller
{
    public function tabelDetail(Request $request){
        if ($request->ajax()) {
            $id = $request->query('id');
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'rencana.id as idRencana',
                'detail_rencana.volume as volume',
                'detail_rencana.uraian as uraian_rencana',
                'rencana.*',
                'rencana.unit_id as unit_id',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                // 'kode_komponen.kode as kodeUsulan',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.*',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.id', $id)
                ->get();

            return datatables()->of($rencana)
                ->make(true);
        }
    }

    public function storelengkapiRencana(Request $request){
        $idrencana = $request->id;
        $p = Rencana::findOrFail($idrencana);

        $detailRencanaId = $request->input('id');
        $kodeKomponenId = $request->input('kode_komponen_id');
        $satuanId = $request->input('satuan_id');
        $rencana = DetailRencana::updateOrCreate(
            [
                'id' => $detailRencanaId,

            ],
            [
                'rencana_id' => $p->id,
                'satuan_id' => $satuanId,
                'volume' => $request->input('volume'),
                'harga' => $request->input('harga'),
                'kode_komponen_id' =>$kodeKomponenId,
            ]


        );
        $jumlah = $rencana->harga * $rencana->volume;
        $rencana->total = $jumlah;
        $rencana->save();

        return Response()->json($rencana,);
    }

}
