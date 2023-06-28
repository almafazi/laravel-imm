<?php

namespace App\Http\Controllers;

use App\Imports\StocksImport;
use App\Models\Material\Material;
use App\Models\Material\MaterialStock;
use App\Notifications\StockNotification;
use Appstract\Stock\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class MaterialStockController extends Controller
{
    public function index($material_id)
    {
        $stocks = Material::whereId($material_id)->first()->material_stocks()->get();

        return view('material-stock.stock', ["stocks" => $stocks, 'material_id' => $material_id]);
    }

    public function material_list()
    {
        if (!Auth()->user()->hasRole('admin')) {
            return redirect()->route('material-stock.logs');
        }
        //logic
        $materials = Material::all();
        return view('material-stock.material-list', ["materials" => $materials]);
    }

    public function create($material_id)
    {
        $material = Material::whereId($material_id)->first();
        //logic
        return view('material-stock.create', compact('material'));
    }

    public function store(Request $request)
    {
        $material = Material::whereId($request->material_id)->first();
        // dd($material->material_stocks()->get());

        $material_stock = $material->material_stocks()->create($request->except(['_token', 'material_id', 'stock']));

        $material_stock->setStock($request->stock, [
            'description' => '<span class="badge bg-success">Stok Awal</span>',
            'reference' => '',
        ]);
        DB::transaction(function () use ($material, $material_stock) {
            $current_material_count = $material->material_stocks()->count();

            $generated_code = $material->grade . '/' . date('m') . date('Y') . '/' . str_pad($current_material_count, 3, 0, STR_PAD_LEFT);

            $material_stock->update([
                'code' => $generated_code
            ]);
        });

        Auth()->user()->notify(new StockNotification($material_stock, $request->stock, 'increase'));

        return redirect()->route('material-stock.index', ['material_id' => $material->id]);
    }

    public function edit($material_id, $material_stock_id)
    {
        $material = Material::whereId($material_id)->first();

        $material_stock = $material->material_stocks()->whereId($material_stock_id)->first();
        //logic
        return view('material-stock.edit', compact('material', 'material_stock'));
    }

    public function update(Request $request)
{
    $material = Material::whereId($request->material_id)->first();
    $material_stock = $material->material_stocks()->whereId($request->material_stock_id)->first();

    if ($request->increase_stock) {
        $increasedStock = $material_stock->increaseStock($request->increase_stock, [
            'description' => '<span class="badge bg-primary">Penambahan Stok</span>',
            'reference' => '',
        ]);

        // Mengirim notifikasi penambahan stok
        Auth()->user()->notify(new StockNotification($material_stock, $increasedStock->amount, 'increase'));
    }

    if ($request->decrease_stock) {
        $decreasedStock = $material_stock->decreaseStock($request->decrease_stock, [
            'description' => '<span class="badge bg-danger">Pengurangan Stok</span>',
            'reference' => '',
        ]);

        // Mengirim notifikasi pengurangan stok
        Auth()->user()->notify(new StockNotification($material_stock, $decreasedStock->amount, 'decrease'));
    }

    return redirect()->route('material-stock.index', ['material_id' => $material->id])->with('success', 'Stok berhasil diubah.');
}



    public function destroy($id)
    {
        MaterialStock::whereId($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }

    public function logs()
    {
        $material_stocks = MaterialStock::with('stockMutations')->groupBy('id')->get();

        return view('material-stock.logs', compact('material_stocks'));
    }

    public function import()
    {
        Excel::import(new StocksImport, request()->file('file'));

        return redirect('/')->with('success', 'All good!');
    }
}
