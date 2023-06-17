<?php

namespace App\Models\Material;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStock extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function material_stock_logs() {
        return $this->hasMany(MaterialStockLog::class);
    }
}
