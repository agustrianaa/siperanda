<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
use App\Models\RPD;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UsulanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $satuan = Satuan::all();

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
                'rencana.tahun as tahun',
                'kode_komponen.*',
                'satuan.*',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(kode_komponen.kode_parent, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->join('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id) // Tambahkan kondisi ini
                ->get();

            // Menghitung nilai 'jumlah' dan menyimpannya ke dalam tabel 'rencana'

            // Membangun data hierarki dengan nomor urut
            $usulanData = $this->buildHierarchy($usulan);

            return datatables()->of($usulanData)
                ->addColumn('action', function ($row) {
                    $id = $row->detail_rencana_id;
                    $id2 = $row->rencana_id;
                    $action = '<a href="javascript:void(0)" onClick="tambahRencanaLain(' . $id . ')" class="add btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="editUsulan(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusUsulan(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('unit.rencana.usulan', compact('satuan'));
    }




    public function store(Request $request)
    {
        $user = Auth::user();
        $unitId = $user->unit->id; // Asumsi user memiliki satu unit

        $rencanaId = $request->id;

        $rencana = Rencana::updateOrCreate(
            [
                'id' => $rencanaId,
            ],
            [
                'tahun' => $request->input('tahun') . '-01-01',
                'unit_id' => $unitId, // Sertakan unit_id
            ]
        );

        return response()->json($rencana);
    }


    public function store2(Request $request)
    {
        $rencanaId = $request->input('rencana_id');
        $detailRencanaId = $request->input('id');
        $kodeKomponenId = $request->input('kode_komponen_id');
        $satuanId = $request->input('satuan_id');
        $noparentId = $request->input('noparent_id');
        $user = Auth::user();
        $unitId = $user->unit->id;

        $rencana1 = Rencana::create(
            [
                'tahun' => $request->input('tahun') . '-01-01',
                'unit_id' => $unitId, // Sertakan unit_id
            ]
        );

        $rencanaId = $rencana1->id;
        $rencana2 = DetailRencana::updateOrCreate(
            [
                'id' => $detailRencanaId,

            ],
            [
                'rencana_id' => $rencanaId,
                'noparent_id' => $noparentId,
                'kode_komponen_id' => $kodeKomponenId,
                'satuan_id' => $satuanId,
                'volume' => $request->input('volume'),
                'harga' => $request->input('harga'),
            ]
        );

        $jumlah = $rencana2->harga * $rencana2->volume;
        $rencana1->jumlah = $jumlah;
        $rencana1->save();

        $detailId = $rencana2->id;
        Log::info('DetailRencana ID: ' . $detailId);
        $rpd = RPD::create(
            [
                'detail_rencana_id' => $detailId,
            ]
        );
        $realisasi = Realisasi::create(
            [
                'detail_rencana_id' => $detailId,
            ]
        );
        return Response()->json($rencana2,);
    }

    public function searchByCode(Request $request)
    {
        Log::info('searchByCode: ');
        $search = $request->input('search');
        Log::info('Search: ' . $search);

        $results = KodeKomponen::where('kode', 'LIKE', "%{$search}%")
            ->orWhere('uraian', 'LIKE', "%{$search}%")
            ->get();
        Log::info('Results: ' . $results);

        return response()->json($results);
    }


    private function buildHierarchy($usulan, $parentId = null, $prefix = '')
    {
        $result = [];
        $index = 1;

        foreach ($usulan as $item) {
            if ($item->noparent_id == $parentId) {
                $item->number = $prefix ? "{$prefix}.{$index}" : (string)$index;
                $result[] = $item;
                $children = $this->buildHierarchy($usulan, $item->detail_rencana_id, $item->number);
                $result = array_merge($result, $children);
                $index++;
            }
        }

        return $result;
    }

    public function edit(Request $request)
{
    $id = $request->id;

    // Ambil data DetailRencana dengan ID yang sesuai
    $detailRencana = DetailRencana::with('kodeKomponen')->findOrFail($id);

    // Ambil data Rencana yang sesuai dengan DetailRencana
    $rencana = Rencana::findOrFail($detailRencana->rencana_id);

    // Gabungkan kode dan uraian untuk dikirim ke view
    $detailRencana->kode_uraian = $detailRencana->kodeKomponen->kode . '.' . $detailRencana->kodeKomponen->kode_parent . ' - ' . $detailRencana->kodeKomponen->uraian;
    $detailRencana->tahun = $rencana->tahun;

    // Kembalikan respons JSON dengan data DetailRencana yang diedit
    return response()->json($detailRencana);
}


    public function update(Request $request, $id)
    {
        $detailRencana = DetailRencana::findOrFail($id);
    $rencana = $detailRencana->rencana;

    if ($rencana) {
        $rencana->tahun = $request->input('tahun') . '-01-01';
        $rencana->save();
    }

    // Update DetailRencana
    $detailRencana->kode_komponen_id = $request->kode_komponen_id;
    $detailRencana->volume = $request->volume;
    $detailRencana->satuan_id = $request->satuan_id;
    $detailRencana->harga = $request->harga;
    $detailRencana->save();

    return response()->json(['success' => 'Data berhasil diperbarui.']);
    }

    public function destroy(Request $request)
    {
        // Temukan detail rencana berdasarkan ID
        $detailRencana = DetailRencana::findOrFail($request->id);

        // Simpan ID rencana yang terkait
        $rencanaId = $detailRencana->rencana_id;

        // Hapus detail rencana
        $detailRencana->delete();

        // Cek jika tidak ada detail rencana lain yang terkait dengan rencana ini
        $remainingDetails = DetailRencana::where('rencana_id', $rencanaId)->count();

        if ($remainingDetails == 0) {
            // Hapus rencana jika tidak ada detail rencana lain yang terkait
            Rencana::findOrFail($rencanaId)->delete();
        }

        return response()->json(['success' => 'Detail rencana (dan rencana terkait jika tidak ada detail rencana lain) berhasil dihapus.']);
    }
}
