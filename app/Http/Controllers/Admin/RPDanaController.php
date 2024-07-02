<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\RPD;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RPDanaController extends Controller
{
    public function rpd(Request $request)
    {
        $unit = Unit::all();
        $kategoris = Kategori::all();
        if (request()->ajax()) {
            $funit = $request->unit_id;
            $fkategori = $request->kategori_id;
            $ftahun = $request->tahun;
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
            ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id');

            if ($funit) {
                $rencana->where('rencana.unit_id', $funit);
            }

            if ($fkategori) {
                $rencana->where('kode_komponen.kategori_id', $fkategori);
            }
            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $rencana->where('rencana.tahun', $ftahunFormat);
            }
            $dataRencana = $rencana->get();

            foreach ($dataRencana as $rpd) {
                $rpd->rpds = RPD::where('detail_rencana_id', $rpd->idRencana)->get();
            }
                return datatables()->of($dataRencana)
                ->addColumn('bulan_rpd', function ($row) {
                    $data = [];
                    // Memastikan properti rpds adalah sebuah array
                    if (!is_null($row->rpds)) {
                        foreach ($row->rpds as $rpd) {
                            $data[] = $rpd->bulan_rpd . ' ( ' . number_format($rpd->jumlah, 0, ',', '.' ) . ')';
                        }
                    }
                    return implode(', ', $data);
                })
                ->addColumn('action', function ($row) {
                    // if ($row->rpds->isEmpty()){
                    //     $action = '<div class="edit btn btn-danger m-1 btn-sm disabled">tak tau</div>';
                    // } else {
                    //     $action = '<div class="edit btn btn-success m-1 btn-sm disabled">Proses</div>';
                    // }
                    $action = '<div class="info btn btn-success m-1 btn-sm disabled">Proses</div>';
                    return $action;
                })
                ->rawColumns(['bulan_rpd','action'])
                ->make(true);
        }
        return view('admin.usulan.rpd',  compact('unit', 'kategoris'));
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
