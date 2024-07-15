<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Rencana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailRencanaController extends Controller
{
    public function tabelDetail(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->query('id');
            $rencana = DetailRencana::select(
                'detail_rencana.*',
                'detail_rencana.id as idRencana',
                'detail_rencana.volume as volume',
                'detail_rencana.uraian as uraian_rencana',
                'rencana.*',
                'rencana.unit_id as unit_id',
                'rencana.jumlah as jumlahUsulan',
                'kode_komponen.*',
                // 'kode_komponen.kode as kodeUsulan',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.*',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.id', $id)
                ->get();

            $currentUser = Auth::user();
            return datatables()->of($rencana)
                ->addColumn('action', function ($row) use ($currentUser) {
                    if ($currentUser->role == 'admin' && $row->created_by == 'admin') {
                        $id = $row->idRencana;
                        $action = '<a href="javascript:void(0)" onClick="editRenc(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                        $action .= '<a href="javascript:void(0)" onClick="hapusRenc(' . $id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                        return $action;
                    } else {
                        $id = $row->idRencana;
                        $action = '<a href="javascript:void(0)" onClick="editParent(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i>Parent</a>';
                        // $action .= '<button class="btn btn-danger btn-sm" disabled><i class="fa fa-times"></i></button>';
                        return $action;
                    }
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tabeleditRA(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->query('id');
            $rencana = Rencana::select('*')
                ->where('rencana.id', $id)
                ->get();

            return datatables()->of($rencana)
                ->addColumn('action', function ($row) {
                    $id = $row->id;
                    $action = '<a href="javascript:void(0)" onClick="editRencAwal(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function checkStatus(Request $request)
    {
        $rencanaId = $request->query('id');
        $rencana = Rencana::find($rencanaId);

        if ($rencana) {
            return response()->json(['status' => $rencana->status]);
        }

        return response()->json(['status' => 'not found'], 404);
    }

    public function editLrencana(Request $request)
    {
        $id = $request->id;
        // Ambil data DetailRencana dengan ID yang sesuai
        $detailRencana = DetailRencana::with('kodeKomponen')->findOrFail($id);
        $rencana = Rencana::findOrFail($detailRencana->rencana_id);

        // Gabungkan kode dan uraian untuk dikirim ke view
        $detailRencana->kode_uraian = $detailRencana->kodeKomponen->kode . '.' . $detailRencana->kodeKomponen->kode_parent . ' - ' . $detailRencana->kodeKomponen->uraian;
        $detailRencana->tahun = $rencana->tahun;

        // Kembalikan respons JSON dengan data DetailRencana yang diedit
        return response()->json($detailRencana);
    }

    public function storelengkapiRencana(Request $request)
    {
        $request->validate([
            'rencana_id' => 'required',
            'satuan_id' => 'required',
            'volume' => 'required',
            'harga' => 'required',
            'kode_komponen_id' => 'required',
            'created_by' => 'required',
        ]);
        $idrencana = $request->rencana_id;
        $p = Rencana::findOrFail($idrencana);

        $detailRencanaId = $request->input('id');
        $kodeKomponenId = $request->input('kode_komponen_id');
        $satuanId = $request->input('satuan_id');
        $rencana = DetailRencana::updateOrCreate(
            [
                'id' => $detailRencanaId,

            ],
            [
                'rencana_id' => $p->id,
                'satuan_id' => $satuanId,
                'volume' => $request->input('volume'),
                'harga' => $request->input('harga'),
                'kode_komponen_id' => $kodeKomponenId,
                'created_by' => $request->input('created_by'),
            ]
        );
        $jumlah = $rencana->harga * $rencana->volume;
        $rencana->total = $jumlah;
        $rencana->save();

        return Response()->json($rencana,);
    }

    // untuk menghapus usulan yang ada di halaman lengkapi usulan
    public function destroy(Request $request)
    {
        $detailRencana = DetailRencana::findOrFail($request->id);

        // Hapus detail rencana
        $detailRencana->delete();

        return response()->json($detailRencana);
    }

    public function editRencAwal(Request $request)
    {
        $id = array('id' => $request->id);
        $rencana  = Rencana::where($id)->first();

        if ($rencana) {
            // Mengambil hanya bagian tahun dari format 'YYYY-01-01'
            $rencana->tahun = substr($rencana->tahun, 0, 4);
        }

        return Response()->json($rencana);
    }

    public function storeEditRA(Request $request)
    {
        $request->validate([
            'unit_id' => 'required',
            'tahun' => 'required',
            'anggaran' => 'required',
        ]);
        $id = $request->input('id');

        $rencana = Rencana::find($id);

        $rencana->unit_id = $request->input('unit_id');
        $rencana->anggaran = $request->input('anggaran');
        $rencana->tahun = $request->input('tahun') . '-01-01';
        $rencana->save();
        return response()->json($rencana);
    }

    public function storeParent(Request $request) {
        // Validasi input
        $request->validate([
            'noparent_id' => 'required|exists:detail_rencana,id',
            // Validasi lainnya jika diperlukan
        ]);

        // Simpan data
        $detailRencanaId = $request->input('id');
        $detailRencana = DetailRencana::find($detailRencanaId);
        if ($detailRencana) {
            // Misal menyimpan noparent_id ke dalam suatu field, sesuaikan dengan kebutuhan Anda
            $detailRencana->noparent_id = $request->input('noparent_id');
            // Set field lainnya sesuai kebutuhan
            $detailRencana->save();

            return response()->json(['success' => 'Data berhasil diperbarui']);
        } else {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }

}
