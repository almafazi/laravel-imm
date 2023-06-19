<?php

namespace App\Http\Controllers;

use App\Models\Material\Material;
use Illuminate\Http\Request;
use DataTables;

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
        $request->validate([
            'name' => 'required|string|min:5|max:100',
            'criteria_1' => 'nullable|string|min:5|max:100',
            'criteria_2' => 'nullable|string|min:5|max:100',
            'information' => 'required|string|min:5|max:1000',
            'grade' => 'required|numeric|in:1,2,3',
        ]);
        Material::create($request->all());
        return redirect('/materials');
    }

    public function datatable() {
        $model = Material::query();
 
        return DataTables::of($model)
        ->addColumn('action', function($row) {
            return '<a href="" class="btn btn-danger">edit</a>';
        })
        ->make(true);
    }
}
