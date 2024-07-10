<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
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
        $unit = Unit::all();
        $funit = $request->unit_id;
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
                KodeKomponen::raw("CONCAT(parent.kode, '.', COALESCE(kode_komponen.kode, '')) as allkode")
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

    public function allAnggaran(Request $request){
        $ftahun = $request->tahun;
        if(request()->ajax()){
            $anggaran = DB::table('anggaran')
            ->leftJoin('rencana', 'anggaran.tahun', '=', 'rencana.tahun')
            ->leftJoin('detail_rencana', 'rencana.id', '=', 'detail_rencana.rencana_id')
            ->leftJoin('realisasi', 'detail_rencana.id', '=', 'realisasi.detail_rencana_id')
            ->select(
                'anggaran.id',
                'anggaran.tahun',
                'anggaran.all_anggaran',
                DB::raw('SUM(realisasi.jumlah) as jumlahRealisasi'),
                DB::raw('anggaran.all_anggaran - COALESCE(SUM(realisasi.jumlah), 0) as sisaAnggaran')
            )
            ->groupBy('anggaran.id', 'anggaran.tahun', 'anggaran.all_anggaran');
            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $anggaran->where('rencana.tahun', $ftahunFormat);
            }
            $dataAnggaran = $anggaran->get();
            return datatables()->of($dataAnggaran)
            ->make(true);
        }
    }

    public function dataAnggaran(Request $request)
    {
        $funit = $request->unit_id;
        $ftahun = $request->tahun;

        if (request()->ajax()) {
            $rencana = Rencana::select(
                'rencana.*',
                'rencana.id as idRencana',
                'unit.name as unit'
            )
            ->leftJoin('unit', 'rencana.unit_id', '=', 'unit.id');

            if ($funit) {
                $rencana->where('rencana.unit_id', $funit);
            }

            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $rencana->where('rencana.tahun', $ftahunFormat);
            }

            $dataRencana = $rencana->get();

            // Menghitung jumlah realisasi dan sisa anggaran secara manual
            foreach ($dataRencana as $data) {
                $detailRencanaIds = DetailRencana::where('rencana_id', $data->id)->pluck('id');
                $jumlahRealisasi = Realisasi::whereIn('detail_rencana_id', $detailRencanaIds)->sum('jumlah');
                $data->jumlahRealisasi = $jumlahRealisasi;
                $data->sisaAnggaran = $data->anggaran - $jumlahRealisasi;
            }

            return datatables()->of($dataRencana)
                ->addColumn('jumlahRealisasi', function ($row) {
                    return $row->jumlahRealisasi;
                })
                ->addColumn('sisaAnggaran', function ($row) {
                    return $row->sisaAnggaran;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" onClick="show(' . $row->idRencana . ')" class="tambah btn btn-warning btn-sm"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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

    public function show(Request $request){
        $id = $request->query('id');
        $rencana = Rencana::findorFail($id);
        return view('unit.detail_monitoring', compact('rencana'));
    }
}
