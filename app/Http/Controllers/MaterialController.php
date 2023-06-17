<?php

namespace App\Http\Controllers;

use App\Models\Material\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index() {
        //logic
        $materials = Material::all();
        return view('material.material', ["materials" => $materials]);
    }

    public function material_api() {
        //logic
        $materials = Material::all();
        return response()->json($materials);
    }

    public function create() {
        //logic
        return view('material.create');
    }

    public function store(Request $request) {
        Material::create($request->all());
        return redirect('/materials');
    }
}
