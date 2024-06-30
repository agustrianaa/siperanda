<?php

namespace App\Exports;

use App\Models\DetailRencana;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AllRencanaExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $tahun;
    public function __construct($tahun)
    {
        $this->tahun =  $tahun . '-01-01';
    }
    public function collection()
    {
        return DetailRencana::with(['kodeKomponen', 'satuan', 'realisasi'])
            ->whereHas('rencana', function ($query) {
                $query->where('tahun', $this->tahun);
            })->get();
    }

    public function headings(): array
    {
        $bulanNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        return [
            'KODE',
            'URAIAN',
            'VOLUME',
            'SATUAN',
            'HARGA',
            'JUMLAH BIAYA',
            ...$bulanNames
        ];
    }

    public function map($rencana): array
    {
        $kodeKomponen = $rencana->kodeKomponen;
        $uraian = $kodeKomponen ? $kodeKomponen->uraian : $rencana->uraian;
        $kode = $kodeKomponen ? $kodeKomponen->kode . '.' . ($kodeKomponen->parent ? $kodeKomponen->parent->kode : '') : '';

        $bulanNames = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $realisasiPerBulan = array_fill(0, 12, 0);

        foreach ($rencana->realisasi as $realisasis) {
            $bulan = array_search($realisasis->bulan_realisasi, $bulanNames);
            if ($bulan !== false) {
                $realisasiPerBulan[$bulan] = $realisasis->jumlah;
            }
        }
        return [
            $kode,
            $uraian,
            $rencana->volume,
            $rencana->satuan->satuan,
            $rencana->harga,
            $rencana->total,
            ...$realisasiPerBulan
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merging cells for custom header
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');
                $sheet->mergeCells('C4:E4');
                $sheet->mergeCells('G1:R1');
                $sheet->mergeCells('G3:R4');

                // Setting values for custom header
                $sheet->setCellValue('A1', 'RINCIAN KERTAS KERJA SATKER T.A. 2022');
                $sheet->setCellValue('A2', 'KEMENTERIAN PENDIDIKAN KEBUDAYAAN RISET DAN TEKNOLOGI Ditjen Pendidikan Vokasi');
                $sheet->setCellValue('A3', 'POLITEKNIK NEGERI INDRAMAYU');
                $sheet->setCellValue('C4', 'PERHITUNGAN TAHUN');
                $sheet->setCellValue('G1', 'REALISASI ANGGARAN');
                $sheet->setCellValue('G3', 'BULAN');

                // Applying styles
                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A3')->getFont()->setBold(true);

                // Center alignment for header
                $sheet->getStyle('A1:R5')->getAlignment()->setHorizontal('center');
            }
        ];
    }
}
