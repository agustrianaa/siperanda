<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
use App\Models\Revisi;
use App\Models\RevisiNote;
use App\Models\Satuan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsulanController extends Controller
{
    public function index(Request $request)
    {
        $unit = Unit::all();
        $kategoris = Kategori::all();
        return view('admin.usulan.usulan', compact('unit', 'kategoris'));
    }

    public function tabelAwalRencana(Request $request)
    {
        if ($request->ajax()) {
            $funit = $request->unit_id;
            $ftahun = $request->tahun;

            $rencana = Rencana::select(
                'rencana.*',
                'rencana.id as idRencana1',
                'rencana.anggaran',
                'unit.*',
                'unit.name as nama_unit'
            )
                ->leftJoin('unit', 'rencana.unit_id', '=', 'unit.id');

            if ($funit) {
                $rencana->where('rencana.unit_id', $funit);
            }
            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $rencana->where('rencana.tahun', $ftahunFormat);
            }

            $usulan = $rencana->get();
            return datatables()->of($usulan)
                ->addColumn('action', function ($row) {
                    $id = $row->idRencana1;
                    $action =  '<a href="javascript:void(0)" onClick="showUsulan(' . $id . ')" class="add btn btn-warning btn-sm mr-2">Validasi</i></a>';
                    $action .=  '<a href="javascript:void(0)" onClick="editUsulan(' . $id . ')" class="add btn btn-success btn-sm mr-2"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusUsulan(' . $id . ')" class="delete btn btn-danger btn-sm mr-2"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tabelRencana(Request $request)
    {
        if ($request->ajax()) {
            $funit = $request->unit_id;
            $fkategori = $request->kategori_id;
            $ftahun = $request->tahun;

            $rencanaQuery = DetailRencana::select(
                'detail_rencana.*',
                'rencana.id as idRencana',
                'detail_rencana.volume as volume',
                'rencana.*',
                'rencana.unit_id as unit_id',
                'detail_rencana.uraian as uraian_rencana',
                'kode_komponen.*',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.*',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id');

            // Filter by unit_id if provided
            if ($funit) {
                $rencanaQuery->where('rencana.unit_id', $funit);
            }

            if ($fkategori) {
                $rencanaQuery->where('kode_komponen.kategori_id', $fkategori);
            }
            if ($ftahun) {
                $ftahunFormat = $ftahun . '-01-01';
                $rencanaQuery->where('rencana.tahun', $ftahunFormat);
            }

            $rencana = $rencanaQuery->get();

            return datatables()->of($rencana)
                ->make(true);
        }
    }


    public function storeKet(Request $request)
    {
        // Ambil ID dari request
        $id = $request->id;

        // Cari rencana berdasarkan ID
        $rencana = Rencana::findOrFail($id);

        if ($rencana) {
            // Update status rencana
            $rencana->status = $request->input('status');
            $rencana->save();
        }

        // Simpan catatan revisi jika ada
        if (!empty($request->note)) {
            RevisiNote::create([
                'rencana_id' => $rencana->id,
                'note' => $request->note,
            ]);
        }

        // Periksa apakah rencana berada dalam status revisi
        if ($rencana->status == 'revisi') {
            // Ambil semua detail rencana yang terkait dengan rencana ini
            $detailRencanaList = DetailRencana::where('rencana_id', $rencana->id)->get();

            foreach ($detailRencanaList as $detailRencana) {
                // Simpan data revisi
                Revisi::create([
                    'rencana_id' => $detailRencana->rencana_id,
                    'kode_komponen_id' => $detailRencana->kode_komponen_id ?: null,
                    'volume' => $detailRencana->volume,
                    'satuan_id' => $detailRencana->satuan_id,
                    'harga' => $detailRencana->harga,
                    'total' => $detailRencana->volume * $detailRencana->harga,
                    'uraian' => $detailRencana->uraian,
                    'revision' => $rencana->revision,
                ]);
            }
            $rencana->revision = ($rencana->revision ?? 0) + 1;
                $rencana->save();
        }

        return response()->json($rencana);
    }


    // untuk menyimpan rencanan awal
    public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'tahun' => 'required',
            'anggaran' => 'required',
        ]);

        $rencana = Rencana::create([
            'id' => $request->id,
            'tahun' => $request->input('tahun') . '-01-01',
            'unit_id' => $request->input('unit_id'),
            'anggaran' => $request->input('anggaran') // Jangan lupa untuk menambahkan kolom anggaran jika diperlukan
        ]);

        return response()->json($rencana);
    }

    public function show(Request $request)
    {
        $id = $request->query('id');
        $rencana = Rencana::findorFail($id);
        return view('admin.usulan.validasi', compact('rencana'));
    }

    public function edit(Request $request)
    {
        $id = $request->query('id');
        $rencana = Rencana::findorFail($id);
        $satuan = Satuan::all();
        $unit = Unit::all();
        $parent = DetailRencana::select('kode_komponen.*', 'detail_rencana.id as detail_rencana_id')
            ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
            ->where('detail_rencana.rencana_id', $id)
            ->get();
        return view('admin.usulan.edit_rencana', compact('rencana', 'satuan', 'unit', 'parent'));
    }

    // untuk mencari kode/uraian dari db Kode Komponen
    public function searchByCode(Request $request)
    {
        $search = $request->input('search');
        Log::info('Search: ' . $search);

        $results = KodeKomponen::where('kode', 'LIKE', "%{$search}%")
            ->orWhere('uraian', 'LIKE', "%{$search}%")
            ->get();
        Log::info('Results: ' . $results);

        return response()->json($results);
    }

    // untuk menghapus rencana awal
    public function destroyRA(Request $request)
    {
        $rencanaAwal = Rencana::where('id', $request->id)->delete();

        return Response()->json($rencanaAwal);
    }
}
