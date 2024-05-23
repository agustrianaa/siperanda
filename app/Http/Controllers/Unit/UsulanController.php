<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
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
            foreach ($usulan as $detail) {
                $harga = $detail->harga;
                $volume = $detail->volume;
                $jumlah = $harga * $volume;

                // Debug: Print values to check
                error_log("Harga: $harga, Volume: $volume, Jumlah: $jumlah");

                // Update field 'jumlah' dalam tabel 'rencana'
                $rencana = Rencana::find($detail->rencana_id);
                if ($rencana) {
                    $rencana->jumlah = $jumlah;
                    $rencana->save();
                }
            }

        // Membangun data hierarki dengan nomor urut
        $usulanData = $this->buildHierarchy($usulan);

        return datatables()->of($usulanData)
            ->addColumn('action', function ($row) {
                $id = $row->detail_rencana_id;
                $id2 = $row->rencana_id;
                $action = '<a href="javascript:void(0)" onClick="tambahRencanaLain(' . $id . ')" class="add btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                $action .= '<a href="javascript:void(0)" onClick="editUsulan(' . $id . ')" class="edit btn btn-success btn-sm"><i class="fas fa-edit"></i></a>';
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

        $rencana = DetailRencana::updateOrCreate(
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

        $detailId = $rencana->id;
        Log::info('DetailRencana ID: ' . $detailId);
        $rpd = Realisasi::updateOrCreate(
            [
                'detail_rencana_id' => $detailId,
            ]
        );
        Log::info('Created Realisasi ID: ' . $rpd->id);
        return Response()->json($rencana,);
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
    $id = array('id' => $request->id);
    $kategori  = DetailRencana::with('kodeKomponen')->where($id)->first();

    // Gabungkan kode dan uraian untuk dikirim ke view
    $kategori->kode_uraian = $kategori->kodeKomponen->kode . ' - ' . $kategori->kodeKomponen->uraian;

    return response()->json($kategori);
}
public function update(Request $request, $id)
{
    $kategori = DetailRencana::findOrFail($id);
    $kategori->kode_komponen_id = $request->kode_komponen_id;
    $kategori->volume = $request->volume;
    $kategori->satuan_id = $request->satuan_id;
    $kategori->harga = $request->harga;
    $kategori->save();

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
