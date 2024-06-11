<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
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
                // 'rpd.bulan_rpd', // Kolom dari tabel rpd
                'kode_komponen.kode', // Kolom dari tabel kode_komponen
                'kode_komponen.uraian',
                'satuan.satuan', // Kolom dari tabel satuan
                'rencana.jumlah as jumlahUsulan',
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                // ->leftJoin('rpd', 'rpd.detail_rencana_id', '=', 'detail_rencana.id')
                ->where('rencana.unit_id', $unit->id)
                ->get();

                foreach ($rpd as $rencana) {
                    $rencana->rpds = RPD::where('detail_rencana_id', $rencana->idRencana)->get();
                }

            return datatables()->of($rpd)
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
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana;
                    $action = '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="lihatRPD(' . $id . ')" class="tambah btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    return $action;
                })
                ->rawColumns(['bulan_rpd','action'])
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
