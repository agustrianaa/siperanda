<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Rencana;
use App\Models\Revisi;
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
        $kategoris = Kategori::all();
        // untuk filter
        $fkategori = $request->kategori_id;
        $ftahun = $request->tahun;
        if ($request->ajax()) {

            $unit = $user->unit;
            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }

            // Sesuaikan query untuk hanya mengambil data terkait dengan unit pengguna
            $usulan = Rencana::select(
                'rencana.*',
                'rencana.id as idRencana',
                'unit.name as unit'
            )
                ->leftJoin('unit', 'rencana.unit_id', '=', 'unit.id')
                ->where('rencana.unit_id', $unit->id); // Tambahkan kondisi ini

            if ($fkategori) {
                $usulan->where('kode_komponen.kategori_id', $fkategori);
            }
            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $usulan->where('rencana.tahun', $ftahunFormat);
            }
            $dataRencana = $usulan->get();

            return datatables()->of($dataRencana)
                ->addColumn('ket', function ($row) {
                    return '<a href="javascript:void(0)" onClick="showHistori(' . $row->idRencana . ')" class="tambah btn btn-warning btn-sm"><i class="fas fa-eye"></i></a>';
                })
                ->rawColumns(['ket'])
                ->make(true);
        }

        return view('unit.rencana.histori', compact('satuan', 'kategoris'));
    }

    public function detailHistori(Request $request)
    {
        $user = Auth::user();
        if ($request->ajax()) {
            $id = $request->query('id');
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
                KodeKomponen::raw("CONCAT(parent.kode, '.', COALESCE(kode_komponen.kode, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id)
                ->where('rencana.id', $id); // Tambahkan kondisi ini
            $dataRencana = $usulan->get();

            return datatables()->of($dataRencana)
                ->make(true);
        }
    }

    public function showHistori(Request $request)
    {
        $id = $request->query('id');
        $rencana = Rencana::with('unit')->findOrFail($id);
        $dataRevisi = Revisi::where('rencana_id', $rencana->id)
            ->pluck('revision')
            ->unique()
            ->sort()
            ->toArray();
        return view('unit.rencana.detail_histori', compact('rencana', 'dataRevisi'));
    }
}
