<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\RPD;
use App\Models\Satuan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $kategoris = Kategori::all();
        $fkategori = $request->kategori_id;
        $funit = $request->unit_id;
        $unit = Unit::all();
        $ftahun = $request->tahun;
        if (request()->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as idRencana',
                'rencana.*',
                'detail_rencana.total as jumlahUsulan',
                'detail_rencana.uraian as uraian_rencana',
                'kode_komponen.*',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.*',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(parent.kode, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.status', '=', 'approved');

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
            foreach ($dataRencana as $data) {
                $data->rpds = RPD::where('detail_rencana_id', $data->idRencana)->get();
            }
            foreach ($dataRencana as $data) {
                $data->realisasi = Realisasi::where('detail_rencana_id', $data->idRencana)->get();
            }


            return datatables()->of($dataRencana)
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

                ->addColumn('ket', function ($row) {
                    $id = $row->idRencana; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="show(' . $id . ')" class="show btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    return $action;
                })
                ->rawColumns(['action', 'ket'])
                ->make(true);
        }
        return view('unit.monitoring', compact('kategoris', 'unit'));
    }

    public function getRealisasi(Request $request)
    {
        $id = $request->query('id');
        $rpdData = DB::table('rpd')
            ->where('detail_rencana_id', $id)
            ->get();

        // Mengambil data dari tabel realisasi
        $realisasiData = DB::table('realisasi')
            ->where('detail_rencana_id', $id)
            ->get();

        $data = [
            'rpd' => $rpdData,
            'realisasi' => $realisasiData,
        ];

        return response()->json($data);
    }
}
