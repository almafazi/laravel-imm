<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStockLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function material_stock() {
        return $this->belongsTo(MaterialStock::class);
    }
}
