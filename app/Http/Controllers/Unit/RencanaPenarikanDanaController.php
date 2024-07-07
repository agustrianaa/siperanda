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
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(parent.kode, '')) as allkode")
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

            foreach ($rpd as $rencana) {
                $rencana->rpds = RPD::where('detail_rencana_id', $rencana->idRencana)->get();
            }

            return datatables()->of($rpd)
                ->addColumn('bulan_rpd', function ($row) {
                    $data = [];
                    // Memastikan properti rpds adalah sebuah array
                    if (!is_null($row->rpds)) {
                        foreach ($row->rpds as $rpd) {
                            $data[] = $rpd->bulan_rpd . ' ( ' . number_format($rpd->jumlah, 0, ',', '.') . ')';
                        }
                    }
                    return implode(', ', $data);
                })
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana;
                    $action = '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="editRPD(' . $id . ')" class="edit btn btn-info btn-sm"><i class="fas fa-edit"></i></a>';
                    return $action;
                })
                ->rawColumns(['bulan_rpd', 'action'])
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
        // $id = $request->id;
        // $rencana = DetailRencana::findOrFail($id);

        // if (!empty($request->bulan_rpd)) {
        $rpd = new Rpd();
        $rpd->detail_rencana_id = $request->detail_rencana_id;
        $rpd->bulan_rpd = $request->bulan_rpd;
        $rpd->jumlah = $request->jumlah;
        $rpd->save();
        // }
        return response()->json($rpd);
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
    public function edit(string $id)
    {
        //
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
}
