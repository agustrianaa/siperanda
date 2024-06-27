<?php

namespace App\Http\Controllers\Admin;

use App\Exports\AllRencanaExport;
use App\Exports\RencanaUnitExport;
use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(){
        $units = Unit::all();
        return view('admin.usulan.report', compact('units'));
    }

    public function exportRencanaUnit(Request $request){
        $request->validate([
            'unit_id' => 'required|exists:unit,id',
        ]);
        $unitId = $request->input('unit_id');
        return Excel::download(new RencanaUnitExport($unitId), 'rencana_unit.xlsx');
    }

    public function exportRencana(Request $request){
        return Excel::download(new AllRencanaExport, 'seluruh_rencana.xlsx');
    }
}
