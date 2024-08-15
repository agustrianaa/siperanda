<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AllRencanaExport;
use App\Exports\ExportRencana;
use App\Exports\RencanaUnitExport;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(){
        $units = Unit::all();
        return view('admin.report', compact('units'));
    }

    public function exportRencana(Request $request){
        $request->validate([
            'tahun' => 'required|digits:4',
        ]);
        $unitId = $request->input('unit_id');
        $tahun = $request->input('tahun');

        return Excel::download(new ExportRencana($tahun, $unitId), 'rencana_unit_' . $tahun . '.xlsx');
    }
}
