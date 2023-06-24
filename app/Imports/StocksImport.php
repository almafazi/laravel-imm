<?php

namespace App\Imports;

use App\Models\Material\Material;
use App\Models\Material\MaterialStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StocksImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function headingRow(): int
    // {
    //     return 2;
    // }

    public function model(array $row)
    {
        $material = Material::whereName($row['name'])->where('criteria_1', $row['criteria_1'])->where('criteria_2', $row['criteria_2'])->where('information', $row['information'])->where('grade', $row['grade'])->first();
       
        if($material != null) {
            $material_stock = MaterialStock::where('code', $row['code'])->first();
            if($material_stock) {
                $material_stock->setStock($row['stock']);
                $material_stock->update([
                    'created_at' => $row['timestamp']
                ]);
                return $material_stock;
            } else {
                $newStock = MaterialStock::create([
                    'material_id' => $material->id,
                    'code' => $row['code']
                ]);
                $newStock->setStock($row['stock']);
                return $newStock;
            }
            
        } else {
            $material = Material::create([
                'name' => $row['name'],
                'criteria_1' => $row['criteria_1'],
                'criteria_2' => $row['criteria_2'],
                'information' => $row['information'],
                'grade' => $row['grade'],
            ]);

            $newStock = MaterialStock::create([
                'material_id' => $material->id,
                'code' => $row['code']
            ]);
            $newStock->setStock($row['stock']);
        } 
    }
}
