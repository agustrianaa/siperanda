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
                ->whereIn('rencana.status', ['approved', 'top_up'])
                ->where('rencana.unit_id', $unit->id);

            if ($fkategori) {
                $rencana->where('kode_komponen.kategori_id', $fkategori);
            }

            $tahunTerbaru = Rencana::max('tahun');

            if ($ftahun) {
                // Jika tahun difilter, tampilkan data sesuai tahun filter
                $ftahunFormat = $ftahun . '-01-01';
                $rencana->where('rencana.tahun', $ftahunFormat);
            } else {
                // Jika tidak ada filter tahun, tampilkan data dari tahun terbaru
                $rencana->where('rencana.tahun', $tahunTerbaru);
            }


            $dataRencana = $rencana->get();

            foreach ($dataRencana as $data) {
                $realisasi = Realisasi::where('detail_rencana_id', $data->idRencana)->get();
                $totalRealisasi = $realisasi->sum('jumlah');
                $sisaAnggaran = $data->jumlahUsulan - $totalRealisasi;
                $data->total_realisasi = $totalRealisasi;
                $data->sisa_anggaran = $sisaAnggaran;
            }

            $hierarkiData = $this->buildHierarchy($dataRencana);
            return datatables()->of(collect($hierarkiData))
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

    public function allAnggaran(Request $request)
    {
        $ftahun = $request->tahun;
        if (request()->ajax()) {
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

    private function buildHierarchy($data, $parentId = null, $prefix = '')
    {
        $result = [];
        $counter = 1;
        foreach ($data as $item) {
            if ($item->noparent_id == $parentId) {
                $item->numbering = $prefix ? "{$prefix}.{$counter}" : (string)$counter;
                $result[] = $item;
                $children = $this->buildHierarchy($data, $item->idRencana, $item->numbering . '.');
                $result = array_merge($result, $children);
                $counter++;
            }
        }
        return $result;
    }

    public function show(Request $request)
    {
        $id = $request->query('id');
        $rencana = Rencana::findorFail($id);
        return view('unit.detail_monitoring', compact('rencana'));
    }
}
