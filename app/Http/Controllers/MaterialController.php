<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use App\Models\Material\Material;
use App\Exports\MaterialStockExport;
use Maatwebsite\Excel\Facades\Excel;

class MaterialController extends Controller
{
    public function index()
    {
        //logic
        $materials = Material::all();
        return view('material.material', ["materials" => $materials]);
    }

    public function material_api()
    {
        //logic
        $materials = Material::all();
        return response()->json($materials);
    }

    public function create()
    {
        //logic
        return view('material.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|max:100',
            'criteria_1' => 'nullable|string|min:2|max:100',
            'criteria_2' => 'nullable|string|min:2|max:100',
            'information' => 'required|string|min:2|max:1000',
            'grade' => 'required|numeric|in:1,2,3',
        ]);
        Material::create($request->all());
        return redirect('/material');
    }

    public function edit($id)
    {
        //logic
        $material = Material::where('id', $id)->firstOrFail();
        return view('material.edit', ["bahan" => $material]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:materials,id',
            'name' => 'required|string|min:2|max:100',
            'criteria_1' => 'nullable|string|min:2|max:100',
            'criteria_2' => 'nullable|string|min:2|max:100',
            'information' => 'required|string|min:2|max:1000',
            'grade' => 'required|numeric|in:1,2,3',
        ]);
        Material::whereId($request->id)->update(
            $request->except('_token')
        );
        return redirect('/material')->with('success', 'Data berhasil di update.');
    }

    public function datatable()
    {
        $material = Material::query();

        return DataTables::of($material)
            ->addColumn('actions', function ($material) {
                return '<a href="' . route('material.edit', ['id' => $material->id]) . '" class="btn btn-primary me-1">ubah bahan</a>
            <a onclick="deleteMaterial(`' . route('material.destroy', ['id' => $material->id]) . '`)" href="javascript:;" class="btn btn-danger me-1">hapus</a>';
            })
            ->editColumn('name', function ($material) {
                return '<b>' . ucwords($material->name) . '</b>';
            })
            ->editColumn('criteria_2', function ($material) {
                if ($material->criteria_2) {
                    return $material->criteria_2;
                } else {
                    return '-';;
                }
            })
            ->rawColumns(['actions', 'name'])
            ->make(true);
    }

    public function destroy($id)
    {
        Material::whereId($id)->delete();
        return redirect('/material')->with('success', 'Data berhasil di hapus.');
    }

    public function export()
    {
        return Excel::download(new MaterialStockExport, 'material_stock.xlsx');
    }
}
