<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
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

        // Query dasar untuk mendapatkan data
        $rencana = DetailRencana::select(
            'detail_rencana.id as idRencana',
            'detail_rencana.volume',
            'detail_rencana.harga',
            'detail_rencana.uraian as uraian_rencana',
            'kode_komponen.kode',
            'kode_komponen.uraian as uraian_kode_komponen',
            'satuan.satuan',
            'detail_rencana.total as jumlahUsulan',
            KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
        )
        ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
        ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
        ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id');

        // Mendapatkan tahun terbaru dari tabel rencana
        $tahunTerbaru = Rencana::max('tahun');

        if ($ftahun) {
            // Jika tahun difilter, tampilkan data sesuai tahun filter
            $ftahunFormat = $ftahun . '-01-01';
            $rencana->where('rencana.tahun', $ftahunFormat);
        } else {
            // Jika tidak ada filter tahun, tampilkan data dari tahun terbaru
            $rencana->where('rencana.tahun', $tahunTerbaru);
        }

        // Filter berdasarkan unit dan kategori jika dipilih
        if ($funit) {
            $rencana->where('rencana.unit_id', $funit);
        }
        if ($fkategori) {
            $rencana->where('kode_komponen.kategori_id', $fkategori);
        }

        $dataRencana = $rencana->get();

        // Menghitung total RPD untuk setiap rencana
        foreach ($dataRencana as $rpd) {
            $rpdData = RPD::where('detail_rencana_id', $rpd->idRencana)->get();
            $totalRPD = $rpdData->sum('jumlah');
            $rpd->total_rpd = $totalRPD;
        }

        return datatables()->of($dataRencana)
            ->addColumn('action', function ($row) {
                $action = '<div class="info btn btn-success m-1 btn-sm disabled">Proses</div>';
                return $action;
            })
            ->rawColumns(['action'])
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
