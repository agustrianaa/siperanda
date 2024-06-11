<?php

namespace App\Exports;

use App\Models\KodeKomponen;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportKodeKomponen implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $datakode = KodeKomponen::orderBy('kode', 'asc')->get();

        return $datakode;
    }
}
