<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Kategori::select('*'))

                ->addColumn('action', function ($row) {
                    $id = $row->id; // Ambil ID dari baris data
                    $action =  '<a href="javascript:void(0)" onClick="editKategori(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusKategori(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.metadata.kategori');
    }

    public function store(Request $request)
    {
        $kategoriId = $request -> id;

        $kategori = Kategori::updateOrCreate([
            'id' => $kategoriId,
        ],
        [
            'nama_kategori'=> $request-> nama_kategori
        ]
    );
    return response()->json(['success' => 'Kategori berhasil disimpan']);
    }
}
