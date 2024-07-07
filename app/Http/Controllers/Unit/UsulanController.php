<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use App\Models\DetailRencana;
use App\Models\KodeKomponen;
use App\Models\Realisasi;
use App\Models\Rencana;
use App\Models\Revisi;
use App\Models\RevisiNote;
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
        $unit_id = $user->unit->id;
        $rencanaId = $this->getLatestRencana($unit_id);

        $is_rev = 0;
        $noteRev ='';

        if ($rencanaId) {
            $is_rev = DetailRencana::where('rencana_id', $rencanaId->id)->where('is_revised', true)->count();
        }
        if($rencanaId){
            $noteRev = RevisiNote::where('rencana_id', $rencanaId->id)->orderBy('created_at', 'desc')->first();
        }

        return view('unit.rencana.usulan', compact('satuan','rencanaId', 'is_rev', 'noteRev'));
    }

    public function tabel1(Request $request)
    {
        $user = Auth::user();
        $satuan = Satuan::all();
        $unit_id = $user->unit->id;
        // $latestRencana = Rencana::where('unit_id', $unit_id->id)->first();
        $rencanaId = $this->getLatestRencana($unit_id);
        // $historiRencana = $this->getHistoriRencana($unit_id);
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
                'detail_rencana.uraian as uraian_rencana',
                'kode_komponen.uraian as uraian_kode_komponen',
                'rencana.tahun as tahun',
                'kode_komponen.*',
                'satuan.*',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(parent.kode, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id) // Tambahkan kondisi ini
                ->where('rencana.id', $rencanaId->id)
                ->get();

            // Membangun data hierarki dengan nomor urut
            $usulanData = $this->buildHierarchy($usulan);

            return datatables()->of($usulanData)
                ->addColumn('action', function ($row) {
                    $id = $row->detail_rencana_id;
                    $action = '<a href="javascript:void(0)" onClick="tambahRencanaLain(' . $id . ')" class="add btn btn-success btn-sm"><i class="fas fa-plus"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="editUsulan(' . $id . ')" class="edit btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
                    $action .= '<a href="javascript:void(0)" onClick="hapusUsulan(' . $id . ')" class="delete btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function tabel2(Request $request)
    {
        $user = Auth::user();
        $satuan = Satuan::all();
        $unit_id = $user->unit->id;
        // $latestRencana = Rencana::where('unit_id', $unit_id->id)->first();
        $rencanaId = $this->getLatestRencana($unit_id);
        // $historiRencana = $this->getHistoriRencana($unit_id);
        if ($request->ajax()) {
            $unit = $user->unit;

            // Pastikan unit ditemukan
            if (!$unit) {
                return response()->json(['data' => []]);
            }

            // Sesuaikan query untuk hanya mengambil data terkait dengan unit pengguna
            $usulan = Revisi::select(
                'revisi.*',
                'revisi.id as revisi_id',
                'rencana.*',
                'revisi.uraian as uraian_rencana',
                'kode_komponen.uraian as uraian_kode_komponen',
                'rencana.tahun as tahun',
                'kode_komponen.*',
                'satuan.*',
                KodeKomponen::raw("CONCAT(kode_komponen.kode, '.', COALESCE(parent.kode, '')) as allkode")
            )
                ->join('rencana', 'revisi.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'revisi.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'revisi.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id) // Tambahkan kondisi ini
                ->where('rencana.id', $rencanaId->id)
                ->get();

            // Membangun data hierarki dengan nomor urut
            // $usulanData = $this->buildHierarchy($usulan);

            return datatables()->of($usulan)
                ->make(true);
        }

        return view('unit.rencana.usulan', compact('satuan','rencanaId',));
    }

    protected function getLatestRencana($unit_id)
    {
        // Mendapatkan rencana terbaru berdasarkan tanggal atau kriteria lainnya
        $rencanaId = Rencana::where('unit_id', $unit_id)->orderBy('tahun', 'desc')->first();

        return $rencanaId;
    }

    protected function getHistoriRencana($unit_id){
        $rencanaId = $this->getLatestRencana($unit_id);

        if($rencanaId){
            $historiRencana = Rencana::where('unit_id', $unit_id)
            ->where('id', '!=', $rencanaId ? $rencanaId->id : 0)
            ->orderBy('created_at', 'desc')
            ->get();
        } else {
            $historiRencana = Rencana::where('unit_id', $unit_id)->orderBy('created_at', 'desc')->get();
        }

        return $historiRencana;
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $unitId = $user->unit->id;
        $rencanaId = $request->id;

        $rencana = Rencana::updateOrCreate(
            [
                'id' => $rencanaId,
            ],
            [
                'tahun' => $request->input('tahun') . '-01-01',
                'unit_id' => $unitId,
            ]
        );

        return response()->json($rencana);
    }


public function store2(Request $request, $id = null)
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
    $rencana->save();

    return response()->json($rencana);
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
    if ($detailRencana->kodeKomponen) {
        $detailRencana->kode_uraian = $detailRencana->kodeKomponen->kode . '.' . $detailRencana->kodeKomponen->kode_parent . ' - ' . $detailRencana->kodeKomponen->uraian;
    } else {
        $detailRencana->kode_uraian = ''; // Atau nilai default lainnya
    }

    // Kembalikan respons JSON dengan data DetailRencana yang diedit
    return response()->json($detailRencana);
}



    public function update(Request $request, $id)
    {
        $detailRencana = DetailRencana::with('rencana')->findOrFail($id);
        if($detailRencana->rencana->status == 'revisi'){
            Revisi::create([
                'rencana_id' => $detailRencana->rencana_id,
                'kode_komponen_id'  => $detailRencana->kode_komponen_id  ? : null,
                'volume'    => $detailRencana->volume,
                'satuan_id' => $detailRencana->satuan_id,
                'harga' => $detailRencana->harga,
                'total' => $detailRencana->total = $detailRencana->volume * $detailRencana->harga,
                'uraian' => $detailRencana->uraian,
                'revision' => ($detailRencana->is_revised + $detailRencana->is_revised2 + $detailRencana->is_revised3) + 1,
            ]);

            $detailRencana->is_revised3 = $detailRencana->is_revised2 ? 1 : 0;
            $detailRencana->is_revised2 = $detailRencana->is_revised ? 1 : 0;
            $detailRencana->is_revised = 1;
        }

        // Update DetailRencana
        $detailRencana->kode_komponen_id = $request->kode_komponen_id ? : null;
        $detailRencana->volume = $request->volume;
        $detailRencana->satuan_id = $request->satuan_id;
        $detailRencana->harga = $request->harga;
        $detailRencana->uraian = $request->uraian;
        $detailRencana->total =  $request->volume * $request->harga;
        $detailRencana->save();

    return response()->json(['success' => 'Data berhasil diperbarui.']);
    }

    public function destroy(Request $request)
    {
        // Temukan detail rencana berdasarkan ID
        $detailRencana = DetailRencana::findOrFail($request->id);

        // Hapus detail rencana
        $detailRencana->delete();

        return response()->json($detailRencana);
    }
}
