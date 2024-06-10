<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\Realisasi;
use App\Models\RevisiNote;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsulanController extends Controller
{
    public function index(Request $request)
{
    $unit = Unit::all();
    $kategoris = Kategori::all();

    if ($request->ajax()) {
        $funit = $request->unit_id;
        $fkategori = $request->kategori_id;

        $rencanaQuery = DetailRencana::select(
            'detail_rencana.*',
            'detail_rencana.id as idRencana',
            'detail_rencana.volume as volume',
            'rencana.*',
            'rencana.unit_id as unit_id',
            'rencana.jumlah as jumlahUsulan',
            'kode_komponen.*',
            'kode_komponen.kode as kodeUsulan',
            'satuan.*',
            'satuan.satuan as satuan',
        )
            ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
            ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
            ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id');

        // Filter by unit_id if provided
        if ($funit) {
            $rencanaQuery->where('rencana.unit_id', $funit);
        }

        if ($fkategori) {
            $rencanaQuery->where('kode_komponen.kategori_id', $fkategori);
        }

        $rencana = $rencanaQuery->get();

        return datatables()->of($rencana)
            ->addColumn('action', function ($row) {
                $id = $row->idRencana;
                $action =  '<a href="javascript:void(0)" onClick="tambahKetUsulan(' . $id . ')" class="add btn btn-success btn-sm mr-2"><i class="fas fa-plus"></i>Ket</a>';
                $action .= '<a href="javascript:void(0)" onClick="hapusUsulan(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('admin.usulan.usulan', compact('unit', 'kategoris'));
}

    public function storeKet(Request $request){
        $id = $request->id;
        Log::info('ID received: ' . $id);
        $rencana = DetailRencana::findOrFail($id);

        if ($rencana) {
            $rencana->status = $request->input('status');
            $rencana->save();
        }
        if (!empty($request->note)) {
                RevisiNote::create([
                    'detail_rencana_id' => $rencana->id,
                    'note' => $request->note,
                ]);
            }
        return response()->json($rencana);


    }
}
