<?php

namespace App\Http\Controllers;

use DataTables;
use App\Exports\LogsExport;
use Illuminate\Http\Request;
use App\Imports\StocksImport;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Carbon;
// use Appstract\Stock\StockMutation;
use App\Models\Material\Material;
use Illuminate\Support\Facades\DB;
use App\Exports\MaterialStockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Material\MaterialStock;
use App\Notifications\StockNotification;
use Appstract\Stock\StockMutation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;


class MaterialStockController extends Controller
{
    public function index($material_id)
    {
        $stocks = Material::whereId($material_id)->first()->material_stocks()->get();

        return view('material-stock.stock', [
            "stocks" => $stocks,
            'material_id' => $material_id,
            'title' => 'Stock Bahan'
        ]);
    }

    public function material_list()
    {
        if (!Auth()->user()->hasRole('admin')) {
            return redirect()->route('material-stock.logs');
        }
        //logic
        $materials = Material::all();
        return view('material-stock.material-list', [
            "materials" => $materials,
            'title' => 'List Stok Bahan'
        ]);
    }

    public function create($material_id)
    {
        $material = Material::whereId($material_id)->first();
        $material_stocks = MaterialStock::join('materials', 'material_stocks.material_id', '=', 'materials.id')->select('material_stocks.*', 'materials.*')->get()->toArray();
        $codes = [];
        foreach ($material_stocks as $material_stock) {
            if ($material_stock['grade'] === 2 && !in_array($material_stock['code'], $codes)) {
                $codes[] = $material_stock['code'];
            }
        }
        //logic
        return view('material-stock.create', compact('material'), [
            'title' => 'Tambah Stock ' . $material->name,
            'codes' => $codes,
            'grade' => $material->grade
        ]);
    }

    public function store(Request $request)
    {
        $material = Material::whereId($request->material_id)->first();
        // dd($material->material_stocks()->get());

        $material_stock = $material->material_stocks()->create($request->except(['_token', 'material_id', 'stock', 'report_at']));

        $report_at = $request->report_at;

        $material_stock->setStock($request->stock, [
            'description' => '<span class="badge bg-success">Stok Awal</span>',
            'report_at' => $report_at,
            'reference' => '',
        ]);
        if ($material->grade == 2) {
            DB::transaction(function () use ($material, $material_stock) {
                $current_material_count = $material->material_stocks()->count();

                $generated_code = $material->grade . '/' . date('m') . date('Y') . '/' . str_pad($current_material_count, 3, 0, STR_PAD_LEFT);

                $material_stock->update([
                    'code' => $generated_code
                ]);
            });
        } else {
            $material_stock->update($request->only(['code']));
        }

        Auth()->user()->notify(new StockNotification($material_stock, $request->stock, 'new_stock'));

        return redirect()->route('material-stock.index', ['material_id' => $material->id])->with('success', 'Berhasil Menambah Stock');
    }

    public function edit($material_id, $material_stock_id)
    {
        $material = Material::whereId($material_id)->first();

        $material_stock = $material->material_stocks()->whereId($material_stock_id)->first();
        //logic
        return view('material-stock.edit', compact('material', 'material_stock'), [
            'title' => 'Kelola Stock ' . $material->name
        ]);
    }

    public function update(Request $request)
    {
        $material = Material::whereId($request->material_id)->first();
        $material_stock = $material->material_stocks()->whereId($request->material_stock_id)->first();

        $report_at = $request->report_at;

        if ($request->increase_stock) {
            $increasedStock = $material_stock->increaseStock($request->increase_stock, [
                'description' => '<span class="badge bg-primary">Penambahan Stok</span>',
                'report_at' => $report_at,
                'reference' => '',
            ]);

            // Mengirim notifikasi penambahan stok
            Auth()->user()->notify(new StockNotification($material_stock, $increasedStock->amount, 'increase'));
        }

        if ($request->decrease_stock) {
            $decreasedStock = $material_stock->decreaseStock($request->decrease_stock, [
                'description' => '<span class="badge bg-danger">Pengurangan Stok</span>',
                'report_at' => $report_at,
                'reference' => '',
            ]);

            // Mengirim notifikasi pengurangan stok
            Auth()->user()->notify(new StockNotification($material_stock, $decreasedStock->amount, 'decrease'));
        }

        return redirect()->route('material-stock.index', ['material_id' => $material->id])->with('success', $material->name . ' Stock Updated!');
    }

    public function destroy($id)
    {
        MaterialStock::whereId($id)->delete();
        return redirect()->back()->with('success', 'Stock Bahan Berhasil Dihapus!');
    }

    public function import()
    {
        Excel::import(new StocksImport, request()->file('file'));

        return redirect('/')->with('success', 'All good!');
    }

    public function logs(Request $request)
{
    // $material_stocks_x = MaterialStock::join('materials', 'material_stocks.material_id', '=', 'materials.id')
    // ->join('stock_mutations', 'material_stocks.id', '=', 'stock_mutations.stockable_id')
    // ->select('material_stocks.*', 'materials.*', 'stock_mutations.*')
    // ->get();
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    // Pengecekan apakah tanggal yang diinput valid
    if ($fromDate && $toDate) {
        $material_stocks = MaterialStock::with(['stockMutations' => function ($query) use ($fromDate, $toDate) {
            $query->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate);
        }])->groupBy('id')->get();
    } else {
        $material_stocks = MaterialStock::with('stockMutations')->groupBy('id')->get();
    }

    return view('material-stock.logs', compact('material_stocks'), [
        'title' => 'Log Stock Bahan',
        'fromDate' => $fromDate,
        'toDate' => $toDate
    ]);
}



    public function export()
    {
        return Excel::download(new MaterialStockExport, 'Export Stock Log Tanggal ' . date('d-m-Y') . '.xlsx');
    }
}
