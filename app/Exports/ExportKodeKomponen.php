<?php

namespace App\Exports;

use App\Models\KodeKomponen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportKodeKomponen implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return KodeKomponen::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Kategori',
            'Uraian'
        ];
    }

    public function map($kodeKomponen): array
    {
        return [
            $kodeKomponen->kode,
            $kodeKomponen->kategori ? $kodeKomponen->kategori->nama_kategori : '',
            $kodeKomponen->uraian,
        ];
    }
}
