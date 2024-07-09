<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExportKodeKomponen;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\KodeKomponen;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KodeController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        if (request()->ajax()) {
            $kodeKomponen = KodeKomponen::select(
                'kode_komponen.*',
                // 'kode_komponen.kode as kodeParent',
                'parent.kode as parent_kode',
                'kategori.nama_kategori',
            )
                ->join('kategori', 'kode_komponen.kategori_id', '=', 'kategori.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
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
        $request->validate([
            'kode' => 'required',
            'kategori_id' => 'required',
            'uraian' => 'required',
        ]);

        $kode = KodeKomponen::updateOrCreate(

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
        return Response()->json($kode);
    }

    public function searchByCode(Request $request)
    {
        // Log::info('searchByCode: ');
        $search = $request->input('search');
        // Log::info('Search: ' . $search);

        $results = KodeKomponen::where('kode', 'LIKE', "%{$search}%")
            ->orWhere('uraian', 'LIKE', "%{$search}%")
            ->get();
        // Log::info('Results: ' . $results);

        return response()->json($results);
    }

    public function destroy(Request $request)
    {
        $kode = KodeKomponen::where('id', $request->id)->delete();

        return Response()->json($kode);
    }

    public function export_kode()
    {
        return Excel::download(new ExportKodeKomponen, 'kode.xlsx');
    }
}
