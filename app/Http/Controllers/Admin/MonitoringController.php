<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Rencana;
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
            $id = $request->query('id');
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
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.id', $id)
                ->where('rencana.status', '=', 'approved');
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
        $rencana = Rencana::with('unit')->findOrFail($id);
        return view('admin.detail_monitor', compact('rencana'));
    }
    public function store(Request $request)
    {
        $detailRencanaId = $request->input('detail_rencana_id');
        $bulanRealisasi = $request->input('bulan_realisasi');
        $jumlah = $request->input('jumlah');

        // Cek apakah ada data RPD yang terkait dengan detail_rencana_id
        $rpd = RPD::where('detail_rencana_id', $detailRencanaId)->first();

        // Jika RPD ada, buat data realisasi baru
        $realisasi = Realisasi::create([
            'detail_rencana_id' => $detailRencanaId,
            'bulan_realisasi' => $bulanRealisasi,
            'jumlah' => $jumlah,
        ]);
    }

    public function updateRealisasi(Request $request)
    {
        $ids = $request->input('id');
        $bulanRealisasi = $request->input('bulan_realisasi');
        $jumlah = $request->input('jumlah');

        foreach ($ids as $index => $id) {
            DB::table('realisasi')
                ->where('id', $id)
                ->update([
                    'bulan_realisasi' => $bulanRealisasi[$index] ?? '',
                    'jumlah' => $jumlah[$index] ?? 0
                ]);
        }

        return response()->json(['success' => 'Data realisasi berhasil diupdate']);
    }

    public function deleteRealisasi(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids) && count($ids) > 0) {
            Realisasi::whereIn('id', $ids)->delete();
            return response()->json(['message' => 'Data realisasi berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'Tidak ada data yang dipilih'], 400);
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
}
