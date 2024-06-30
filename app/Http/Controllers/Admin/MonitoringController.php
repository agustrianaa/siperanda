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
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $unit = Unit::all();
        $kategoris = Kategori::all();
        if (request()->ajax()) {
            $funit = $request->unit_id;
            $fkategori = $request->kategori_id;
            $ftahun = $request->tahun;
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as idRencana',
                'detail_rencana.volume as volume',
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
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="tambahRealisasi(' . $id . ')" class="realisasi btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="editRealisasi(' . $id . ')" class="realisasi btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="hapusRealisasi(' . $id . ')" class="realisasi btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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
        return view('admin.monitoring', compact('unit', 'kategoris'));
    }

    public function store(Request $request)
    {
        $detailRencanaId = $request->input('detail_rencana_id');
    $bulanRealisasi = $request->input('bulan_realisasi');
    $jumlah = $request->input('jumlah');

    // Cek apakah ada data RPD yang terkait dengan detail_rencana_id
    $rpd = RPD::where('detail_rencana_id', $detailRencanaId)->first();

    if (!$rpd) {
        // Jika tidak ada RPD yang terkait, kembalikan pesan error
        return response()->json(['error' => 'Belum ada data RPD yang terkait dengan detail rencana ini.'], 404);
    }

    // Jika RPD ada, buat data realisasi baru
    $realisasi = Realisasi::create([
        'detail_rencana_id' => $detailRencanaId,
        'bulan_realisasi' => $bulanRealisasi,
        'jumlah' => $jumlah,
    ]);
    }

    public function edit(Request $request)
    {
        $id = array('id' => $request->id);
        $realisasi = DB::table('realisasi')
            ->join('rpd', 'realisasi.detail_rencana_id', '=', 'rpd.detail_rencana_id')
            ->where('realisasi.id', $id)
            ->select('realisasi.*', 'rpd.bulan_rpd')
            ->first();

        return Response()->json($realisasi);
    }
}
