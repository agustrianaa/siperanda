<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\RPD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RencanaPenarikanDanaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (request()->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }
            $rpd = DetailRencana::select(
                'detail_rencana.id as idRencana',
                'detail_rencana.volume',
                'detail_rencana.harga',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.satuan',
                'detail_rencana.total as jumlahUsulan',
                'detail_rencana.uraian as uraian_rencana',
                KodeKomponen::raw("CONCAT(parent.kode, '.', COALESCE(kode_komponen.kode, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id)
                ->whereIn('rencana.tahun', function ($query) use ($unit) {
                    $query->select(DB::raw('MAX(tahun)'))
                        ->from('rencana')
                        ->where('unit_id', $unit->id);
                })
                ->orderBy('rencana.tahun', 'desc')
                ->get();

            return datatables()->of($rpd)
                ->addColumn('rpd', function ($row) {
                    $id = $row->idRencana;
                    $hasRpd = RPD::where('detail_rencana_id', $id)->exists();
                $icon = $hasRpd ? 'fa-eye' : 'fa-eye-slash';
                $rpd = '<a href="javascript:void(0)" onClick="showRPD(' . $id . ')" class="tambah btn btn-warning btn-sm"><i class="fas ' . $icon . '"></i></a>';
                    return $rpd;
                })
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana;
                    $action = '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="editRPD(' . $id . ')" class="edit btn btn-info btn-sm"><i class="fas fa-edit"></i></a>';
                    return $action;
                })
                ->rawColumns(['rpd', 'action'])
                ->make(true);
        }
        return view('unit.rencana.rpd');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $unitId = $user->unit->id;

        // Lakukan update pada semua entri dalam tabel Realisasi
        $rpd = RPD::query()->update([
            'bulan_rpd' => $request->bulan_rpd,
            'jumlah' => $request->jumlah,
        ]);

        return response()->json($rpd);
    }

    public function storeRPD(Request $request)
    {
        $request->validate([
            'detail_rencana_id' => 'required|integer',
            'jumlah' => 'required|array',
        ]);

        $jumlahData = $request->input('jumlah');

        foreach ($jumlahData as $monthName => $jumlah) {
            if (!empty($jumlah)) {
                Rpd::updateOrCreate(
                    ['detail_rencana_id' => $request->detail_rencana_id, 'bulan_rpd' => $monthName],
                    ['jumlah' => $jumlah]
                );
            }
        }

        return response()->json(['success' => 'RPD berhasil disimpan.']);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
{
    $detailRencanaId = $request->id;
    $rpdData = Rpd::where('detail_rencana_id', $detailRencanaId)->get();
    $detailRencana = DetailRencana::findOrFail($detailRencanaId);

    $data = [
        'detail_rencana_id' => $detailRencanaId,
        'jumlah' => [],
        'anggaran_max' => $detailRencana->total, // Anggaran maksimum yang diizinkan
    ];

    foreach ($rpdData as $rpd) {
        $data['jumlah'][$rpd->bulan_rpd] = $rpd->jumlah;
    }

    return response()->json($data);
}


    /**
     * Update the specified resource in storage.
     */
    public function updateRPD(Request $request)
    {
        $ids = $request->input('id');
        $bulanRpd = $request->input('bulan_rpd');
        $jumlah = $request->input('jumlah');

        foreach ($ids as $index => $id) {
            DB::table('RPD')
                ->where('id', $id)
                ->update([
                    'bulan_rpd' => $bulanRpd[$index] ?? '',
                    'jumlah' => $jumlah[$index] ?? 0
                ]);
        }

        return response()->json(['success' => 'Data realisasi berhasil diupdate']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getDetailRencana(Request $request)
{
    $detailRencana = DetailRencana::findOrFail($request->id);
    $anggaran_max = $detailRencana->total; // Anggaran maksimum yang diizinkan

    return response()->json([
        'anggaran_max' => $anggaran_max
    ]);
}

}
