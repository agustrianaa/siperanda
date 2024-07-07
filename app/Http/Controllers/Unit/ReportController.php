<?php

namespace App\Http\Controllers\Unit;

use App\Exports\RencanaUnitExport;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('unit.report', compact('units'));
    }

    public function exportRencana(Request $request)
    {
        $request->validate([
            'tahun' => 'required|digits:4',
        ]);

        $tahun = $request->input('tahun');
        $unitId = Auth::user()->unit->id; // Asumsikan unit_id ada di user

        return Excel::download(new RencanaUnitExport($tahun, $unitId), 'rencana_unit_' . $tahun . '.xlsx');
    }
}
