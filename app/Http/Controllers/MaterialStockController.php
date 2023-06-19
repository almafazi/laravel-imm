<?php

namespace App\Http\Controllers;

use App\Models\Material\Material;
use Illuminate\Http\Request;

class MaterialStockController extends Controller
{
    public function material_list() {
        //logic
        $materials = Material::all();
        return view('material-stock.material-list', ["materials" => $materials]);
    }

    public function create($material_id) {
        $material = Material::whereId($material_id)->first();
        //logic
        return view('material-stock.create', compact('material'));
    }

    public function store(Request $request) {
        $material = Material::whereId($request->material_id)->first();
       // dd($material->material_stocks()->get());
        $material->material_stocks()->create($request->except('_token', 'material_id'));
        return redirect('/materials');
    }
}
