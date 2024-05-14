<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Satuan::select('*'))

                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="editSatuan(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusSatuan(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.metadata.satuan');
    }

    public function store(Request $request)
    {
        $satuanId = $request -> id;

        $satuan = Satuan::updateOrCreate([
            'id' => $satuanId,
        ],
        [
            'satuan'=> $request-> satuan
        ]
    );
    return response()->json();
    }

    public function edit(Request $request)
    {
        $id = array('id' => $request->id);
        $satuan  = Satuan::where($id)->first();

        return Response()->json($satuan);
    }

    public function destroy(Request $request)
    {
        $satuan = Satuan::where('id',$request->id)->delete();

        return Response()->json($satuan);
    }
}
