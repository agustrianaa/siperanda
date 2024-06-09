<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Realisasi;
use App\Models\RPD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
                'detail_rencana.*',
                'detail_rencana.id as idRencana',
                'rpd.*', // Kolom dari tabel rpd
                'detail_rencana.*', // Kolom dari tabel detail_rencana
                'kode_komponen.*', // Kolom dari tabel kode_komponen
                'satuan.*', // Kolom dari tabel satuan
                'rencana.*',
                'rencana.jumlah as jumlahUsulan'
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->leftJoin('rpd', 'rpd.detail_rencana_id', '=', 'detail_rencana.id')
                ->where('rencana.unit_id', $unit->id)
                ->get();

            return datatables()->of($rpd)
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana;
                    if ($row->bulan_rpd) {
                        $action = '<div class="info btn btn-danger btn-sm disabled">Submited</div>';
                        $action .= '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm">Tambah RPDLain</a>';
                    } else { // Ambil ID dari baris data
                        $action = '<a href="javascript:void(0)" onClick="tambahRPD(' . $id . ')" class="tambah btn btn-success btn-sm">Tambah RPD</a>';
                    }
                    return $action;
                })
                ->rawColumns(['action'])
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

    public function storeRPD(Request $request){
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
