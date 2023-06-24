<?php

namespace App\Exports;

use App\Models\Material\MaterialStock;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;


class StocksExport implements WithEvents,WithStyles, FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    use RegistersEventListeners;

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();

        $sheet->getStyle('A1:h1')
        ->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()
        ->setARGB('39dd4b');
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 16]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'criteria_1',
            'criteria_2',
            'information',
            'grade',
            'stock',
            'code',
            'timestamp',
        ];
    }

    public function collection()
    {
        return MaterialStock::with('material')->get();
    }

    public function map($material_stock): array
    {
        return [
            $material_stock->material->name,
            $material_stock->material->criteria_1,
            $material_stock->material->criteria_2,
            $material_stock->material->information,
            $material_stock->material->grade,
            $material_stock->stock,
            $material_stock->code,
            $material_stock->created_at,
        ];
    }

}
