<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use Illuminate\Http\Request;

class KodeController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        if (request()->ajax()) {
            $kodeKomponen = KodeKomponen::select('kode_komponen.*', 'kategori.nama_kategori')
                ->join('kategori', 'kode_komponen.kategori_id', '=', 'kategori.id')
                ->get();
            return datatables()->of($kodeKomponen)
                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="editKode(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusKode(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.metadata.kode', compact('kategori'));
    }

    public function edit(Request $request)
    {
        $id = array('id' => $request->id);
        $kode  = KodeKomponen::where($id)->first();

        return Response()->json($kode);
    }

    public function store(Request $request)
    {
        $kodeId = $request->id;

        $kategori = KodeKomponen::updateOrCreate(
            [
                'id' => $kodeId,
            ],
            [
                'kode' => $request->kode,
                'kode_parent' => $request->kode_parent,
                'kategori_id' => $request->kategori_id,
                'uraian' => $request->uraian,
            ]
        );
    }
}
