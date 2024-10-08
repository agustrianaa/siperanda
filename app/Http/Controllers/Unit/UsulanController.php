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
use Illuminate\Support\Facades\DB;
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
        $noteRev = '';
        $total = '0';
        $dataRevisi = '';

        if ($rencanaId) {
            $is_rev = DetailRencana::where('rencana_id', $rencanaId->id)->where('is_revised', true)->count();
        }
        if ($rencanaId) {
            $noteRev = RevisiNote::where('rencana_id', $rencanaId->id)->orderBy('created_at', 'desc')->first();
        }
        if ($rencanaId) {
            $total = DetailRencana::where('rencana_id', $rencanaId->id)->sum('total');
        }
        if ($rencanaId){
            $dataRevisi = Revisi::where('rencana_id', $rencanaId->id)
            ->pluck('revision')
            ->unique()
            ->sort()
            ->toArray();
        }


        return view('unit.rencana.usulan', compact('satuan', 'rencanaId', 'is_rev', 'noteRev', 'total', 'dataRevisi'));
    }

    public function tabelRencana(Request $request)
    {
        $user = Auth::user();
        $satuan = Satuan::all();
        $unit_id = $user->unit->id;
        $rencanaId = $this->getLatestRencana($unit_id);
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
                KodeKomponen::raw("CONCAT(parent.kode, '.', COALESCE(kode_komponen.kode, '')) as allkode")
            )
                ->join('rencana', 'detail_rencana.rencana_id', '=', 'rencana.id')
                ->leftJoin('kode_komponen', 'detail_rencana.kode_komponen_id', '=', 'kode_komponen.id')
                ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
                ->join('satuan', 'detail_rencana.satuan_id', '=', 'satuan.id')
                ->where('rencana.unit_id', $unit->id) // Tambahkan kondisi ini
                ->where('rencana.id', $rencanaId->id);

            $dataRencana = $usulan->get();

            // Membangun data hierarki dengan nomor urut
            $usulanData = $this->buildHierarchy($dataRencana);

            return datatables()->of(collect($usulanData))
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

    public function tabelRevisi(Request $request)
{
    $user = Auth::user();
    $unit_id = $user->unit->id;
    $rencanaId = $this->getLatestRencana($unit_id);
    $revision = $request->revision;

    if ($request->ajax()) {
        $unit = $user->unit;

        if (!$unit) {
            return response()->json(['data' => []]);
        }

        // Query utama untuk mendapatkan data usulan
        $usulan = Revisi::select(
            'revisi.id as detail_rencana_id',
                'rencana.tahun',
                'revisi.uraian as uraian_rencana',
                'kode_komponen.uraian as uraian_kode_komponen',
                'satuan.satuan as satuan',
                KodeKomponen::raw("CONCAT(parent.kode, '.', COALESCE(kode_komponen.kode, '')) as allkode"),
                'revisi.volume',
                'revisi.harga',
                KodeKomponen::raw("revisi.volume * revisi.harga as total")
        )
            ->join('rencana', 'revisi.rencana_id', '=', 'rencana.id')
            ->leftJoin('kode_komponen', 'revisi.kode_komponen_id', '=', 'kode_komponen.id')
            ->leftJoin('kode_komponen as parent', 'kode_komponen.kode_parent', '=', 'parent.id')
            ->join('satuan', 'revisi.satuan_id', '=', 'satuan.id')
            ->where('rencana.unit_id', $unit->id)
            ->where('rencana.id', $rencanaId->id);

        if ($revision) {
            $usulan->where('revisi.revision', $revision);
        } else {
            $latestRevision = Revisi::where('rencana_id', $rencanaId->id)
                ->max('revision');
            $usulan->where('revisi.revision', $latestRevision);
        }

        $dataRevisi = $usulan->get();
        $revData = $this->buildHierarchy($dataRevisi);

        return datatables()->of(collect($revData))
            ->make(true);
    }
}



    protected function getLatestRencana($unit_id)
    {
        // Mendapatkan rencana terbaru berdasarkan tanggal atau kriteria lainnya
        $rencanaId = Rencana::where('unit_id', $unit_id)->orderBy('tahun', 'desc')->first();

        return $rencanaId;
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
                'kode_komponen_id' => 'required|exists:kode_komponen,id',
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
        $totalRencana = DetailRencana::where('rencana_id', $usulan->id)->sum('total');
        $exceeds_budget = $totalRencana > $usulan->anggaran;

        return response()->json([
            'success' => 'Data berhasil diperbarui.',
            'exceeds_budget' => $exceeds_budget,
            'total' => $totalRencana
        ]);
    }



    public function searchByCode(Request $request)
    {
        $search = $request->input('search');

        $results = KodeKomponen::leftJoin('kode_komponen as parents', 'kode_komponen.kode_parent', '=', 'parents.id')
            ->select('kode_komponen.*', 'parents.kode as kode_parent')
            ->where('kode_komponen.kode', 'LIKE', "%{$search}%")
            ->orWhere('kode_komponen.uraian', 'LIKE', "%{$search}%")
            ->get();

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

        $detailRencana = DetailRencana::with('kodeKomponen')->findOrFail($id);

        $rencana = Rencana::findOrFail($detailRencana->rencana_id);

        if ($detailRencana->kodeKomponen) {
            $detailRencana->kode_uraian = $detailRencana->kodeKomponen->kode . '.' . $detailRencana->kodeKomponen->kode_parent . ' - ' . $detailRencana->kodeKomponen->uraian;
        } else {
            $detailRencana->kode_uraian = '';
        }
        $detailRencana->status = $rencana->status;

        return response()->json($detailRencana);
    }



    public function update(Request $request, $id)
    {
        $detailRencana = DetailRencana::with('rencana')->findOrFail($id);
        $usulan = $detailRencana->rencana;

        // if ($detailRencana->rencana->status == 'revisi') {
        //     Revisi::create([
        //         'rencana_id' => $detailRencana->rencana_id,
        //         'kode_komponen_id' => $detailRencana->kode_komponen_id ?: null,
        //         'volume' => $detailRencana->volume,
        //         'satuan_id' => $detailRencana->satuan_id,
        //         'harga' => $detailRencana->harga,
        //         'total' => $detailRencana->volume * $detailRencana->harga,
        //         'uraian' => $detailRencana->uraian,
        //         'revision' => ($detailRencana->is_revised ?? 0) + 1,
        //     ]);

        //     $detailRencana->is_revised = ($detailRencana->is_revised ?? 0) + 1;
        // }

        // Update DetailRencana
        $detailRencana->kode_komponen_id = $request->kode_komponen_id ?: null;
        $detailRencana->volume = $request->volume;
        $detailRencana->satuan_id = $request->satuan_id;
        $detailRencana->harga = $request->harga;
        $detailRencana->uraian = $request->uraian;
        $detailRencana->total = $request->volume * $request->harga;
        $detailRencana->save();

        $totalRencana = DetailRencana::where('rencana_id', $usulan->id)->sum('total');
        $exceeds_budget = $totalRencana > $usulan->anggaran;

        return response()->json([
            'success' => 'Data berhasil diperbarui.',
            'exceeds_budget' => $exceeds_budget,
            'total' => $totalRencana
        ]);
    }


    public function destroy(Request $request)
    {
        // Temukan detail rencana berdasarkan ID
        $detailRencana = DetailRencana::with('rencana')->findOrFail($request->id);

        $rencana = Rencana::findOrFail($detailRencana->rencana_id);

        if ($rencana->status === 'approved') {
            return response()->json([
                'status' => 'approved',
                'message' => 'Usulan sudah disetujui dan tidak bisa dihapus'
            ], 400);
        } else {
            // Hapus detail rencana
            $detailRencana->delete();

            // Hitung total anggaran setelah penghapusan
            $totalRencana = DetailRencana::where('rencana_id', $rencana->id)->sum('total');
            $exceeds_budget = $totalRencana > $rencana->anggaran;

            return response()->json([
                'success' => 'Data berhasil dihapus.',
                'exceeds_budget' => $exceeds_budget,
                'total' => $totalRencana
            ]);
        }
    }


    public function checkAnggaran(Request $request)
    {
        $detailRencanaId = $request->input('detail_rencana_id');
        $detailRencana = DetailRencana::find($detailRencanaId);

        if ($detailRencana) {
            $rencana = $detailRencana->rencana; // Mengambil tabel rencana dari detail rencana
            $pagu = $rencana ? $rencana->anggaran : 0;

            // Menghitung total anggaran berdasarkan detail_rencana_id
            $totalAnggaran = DetailRencana::where('rencana_id', $rencana->id)->sum('total');

            return response()->json(['pagu' => $pagu, 'total_anggaran' => $totalAnggaran]);
        }

        return response()->json(['pagu' => null, 'total_anggaran' => 0], 404);
    }

    public function checkStatus(Request $request)
    {
        $id = $request->id;
        $rencana = Rencana::findOrFail($id);

        return response()->json([
            'status' => $rencana->status
        ]);
    }
}
