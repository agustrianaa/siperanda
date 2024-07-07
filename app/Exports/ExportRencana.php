<?php

namespace App\Exports;

use App\Models\DetailRencana;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExportRencana implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $unitId;
    protected $tahun;

    public function __construct($tahun, $unitId = null)
    {
        $this->unitId = $unitId;
        $this->tahun = $tahun;
    }
    public function collection()
    {
        $query = DetailRencana::with(['kodeKomponen', 'satuan', 'rencana'])
            ->whereHas('rencana', function ($query) {
                $query->whereYear('tahun', $this->tahun)
                ->where('status', 'approved');

                if (!is_null($this->unitId) && $this->unitId != '') {
                    $query->where('unit_id', $this->unitId);
                }
            });

        $result = $query->get();

        return $result;
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
            AfterSheet::class => function (AfterSheet $event) {
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
