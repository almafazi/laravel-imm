<?php

namespace App\Exports;

use App\Models\Material\MaterialStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Events\AfterSheet;

class MaterialStockExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        $created_at = request('created_at');

        $materialStocks = MaterialStock::with('material', 'stockMutations')
            ->whereHas('stockMutations', function ($query) use ($created_at) {
                $query->whereDate('created_at', $created_at);
            })
            ->get();

        $data = [];

        foreach ($materialStocks as $materialStock) {
            $accumulation = 0;
            foreach ($materialStock->stockMutations as $mutation) {
                $accumulation += $mutation->amount;
                $description = strip_tags($mutation->description);

                $data[] = [
                    $materialStock->material->name,
                    $materialStock->material->criteria_1,
                    $materialStock->material->criteria_2 ?? '-',
                    $materialStock->material->information,
                    $materialStock->material->grade,
                    $mutation->amount,
                    $accumulation,
                    $materialStock->code,
                    $mutation->price,
                    $mutation->report_at,
                    $description ?? '',
                    $mutation->created_at->format('d/m/Y'),
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Kriteria 1',
            'Kriteria 2',
            'Informasi',
            'Grade',
            'Jumlah',
            'Akumulasi',
            'Kode Produksi',
            'Harga',
            'Tanggal Lapor',
            'Deskripsi',
            'Timestamp',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '8affa4',
                ],
            ],
        ]);

        // Style the data rows
        $sheet->getStyle('A2:L' . $sheet->getHighestRow())->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $columnWidths = [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 10,
            'F' => 15,
            'G' => 15,
            'H' => 20,
            'I' => 15,
            'J' => 20,
            'K' => 40,
            'L' => 15,
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Set column gap
        $columnGap = 2;
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setWidth($sheet->getColumnDimension($column)->getWidth() + $columnGap);
        }
    }
}
