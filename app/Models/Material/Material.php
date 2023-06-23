<?php

namespace App\Models\Material;

use Appstract\Stock\StockMutation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function material_stocks() {
        return $this->hasMany(MaterialStock::class);
    }

    public function stock_mutations() {
        return $this->hasManyThrough(StockMutation::class, MaterialStock::class, null, 'stockable_id')->where('stockable_type', MaterialStock::class);
    }
}
