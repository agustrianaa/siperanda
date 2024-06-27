<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Rencana;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoriController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $satuan = Satuan::all();
        $unit_id = $user->unit->id;
        // $latestRencana = Rencana::where('unit_id', $unit_id->id)->first();
        $rencanaId = Rencana::where('unit_id', $unit_id)->orderBy('created_at', 'asc')->first();
        // $historiRencana = $this->getHistoriRencana($unit_id);
        if ($request->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }

            // Sesuaikan query untuk hanya mengambil data terkait dengan unit pengguna
            $usulan = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as detail_rencana_id',
                'rencana.*',
                'detail_rencana.uraian as uraian_rencana',
                'kode_komponen.uraian as uraian_kode_komponen',
                'rencana.tahun as tahun',
                'kode_komponen.*',
                'satuan.*',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id) // Tambahkan kondisi ini
                ->where('rencana.id', $rencanaId->id)
                ->get();

            // Membangun data hierarki dengan nomor urut
            // $usulanData = $this->buildHierarchy($usulan);

            return datatables()->of($usulan)
                ->make(true);
        }

        return view('unit.rencana.histori', compact('satuan','rencanaId',));
    }
}
