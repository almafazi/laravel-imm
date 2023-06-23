<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Appstract\Stock\HasStock;
use Appstract\Stock\StockMutation;

class MaterialStock extends Model
{
    use HasStock;
    use HasFactory;
    protected $guarded = [];

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function material_stock_logs() {
        return $this->hasMany(MaterialStockLog::class);
    }
}
