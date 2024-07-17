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
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.*',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.id', $id);
                $dataRencana = $rencana->get();
                $usulanData = $this->buildHierarchy($dataRencana);

                return datatables()->of(collect($usulanData))
                ->addColumn('action', function ($row) {
                        $id = $row->idRencana;
                        $action = '<a href="javascript:void(0)" onClick="editRenc(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                        $action .= '<a href="javascript:void(0)" onClick="hapusRenc(' . $id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                        return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tabeleditRA(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->query('id');
            $rencana = Rencana::select('rencana.*', 'unit.name as nama_unit')
            ->leftJoin('unit', 'rencana.unit_id', '=', 'unit.id')
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

    private function buildHierarchy($data, $parentId = null, $prefix = '')
    {
        $result = [];
        $counter = 1;
        foreach ($data as $item) {
            if ($item->noparent_id == $parentId) {
                $item->numbering = $prefix ? "{$prefix}.{$counter}" : (string)$counter;
                $result[] = $item;
                $children = $this->buildHierarchy($data, $item->idRencana, $item->numbering . '.');
                $result = array_merge($result, $children);
                $counter++;
            }
        }
        return $result;
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
        if ($detailRencana->kodeKomponen) {
            $detailRencana->kode_uraian = $detailRencana->kodeKomponen->kode . '.' . $detailRencana->kodeKomponen->kode_parent . ' - ' . $detailRencana->kodeKomponen->uraian;
        } else {
            $detailRencana->kode_uraian = '';
        }
        // Gabungkan kode dan uraian untuk dikirim ke view
        $detailRencana->tahun = $rencana->tahun;

        // Kembalikan respons JSON dengan data DetailRencana yang diedit
        return response()->json($detailRencana);
    }

    public function storelengkapiRencana(Request $request)
    {
        $request->validate([
            'volume' => 'required|numeric',
            'harga' => 'required|numeric',
            'satuan_id' => 'required',
        ]);

        if ($request->kategori === 'detil') {
            $request->validate([
                'uraian' => 'required|string|max:255',
            ]);
        } else {
            $request->validate([
                'kode_komponen_id' => 'required|exists:kode_komponen,id',  // Pastikan kode_komponen_id tidak null dan ada di tabel kode_komponen
            ]);
        }

        $usulan = Rencana::findOrFail($request->rencana_id);

        $detailRencanaId = $request->input('id');
        $kodeKomponenId = $request->input('kode_komponen_id');
        $satuanId = $request->input('satuan_id');
        $noparentId = $request->input('noparent_id');

        $rencana = DetailRencana::updateOrCreate(
            [
                'id' => $detailRencanaId,
            ],
            [
                'rencana_id' => $usulan->id,
                'noparent_id' => $noparentId,
                'satuan_id' => $satuanId,
                'volume' => $request->input('volume'),
                'harga' => $request->input('harga'),
                'created_by' => $request->input('created_by'),
                'total' => $request->input('harga') * $request->input('volume'),
                'uraian' => $request->kategori === 'detil' ? $request->uraian : null, // Simpan uraian hanya jika kategori detil
                'kode_komponen_id' => $request->kategori === 'detil' ? null : $request->kode_komponen_id, // Simpan kode_komponen_id hanya jika kategori bukan detil
            ]
        );

        $jumlah = $rencana->harga * $rencana->volume;
        $rencana->total = $jumlah;
        $rencana->noparent_id = $noparentId;
        $rencana->save();

        return response()->json($rencana);
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
