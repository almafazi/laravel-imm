<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Events\LogFilled;
use Illuminate\Http\Request;
use App\Imports\StocksImport;
use App\Models\Material\Material;
use Illuminate\Support\Facades\DB;
use App\Exports\MaterialStockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Material\MaterialStock;
use Illuminate\Support\Facades\Storage;
use App\Notifications\StockNotification;
use Revolution\Google\Sheets\Facades\Sheets;
use Yajra\DataTables\Facades\DataTables;


// use Appstract\Stock\StockMutation;
// use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Illuminate\Http\Response;
// use GuzzleHttp\Promise\Create;
// use App\Exports\LogsExport;
// use DataTables;
// use Illuminate\Support\Carbon;




class MaterialStockController extends Controller
{
    // Clientside datatable
    // public function index($material_id)
    // {
    //     $stocks = Material::whereId($material_id)->first()->material_stocks()->get();

    //     return view('material-stock.stock', [
    //         'stocks' => $stocks,
    //         'material_id' => $material_id,
    //         'title' => 'Stock Bahan'
    //     ]);
    // }


    // Serverside datatable
    public function index($material_id)
    {
        if (request()->ajax()) {
            $stocks = MaterialStock::where('material_id', $material_id)->get();

            return DataTables::of($stocks)
                ->addColumn('nama_bahan', function ($stock) {
                    return $stock->material->name;
                })
                ->addColumn('kriteria_1', function ($stock) {
                    return $stock->material->criteria_1;
                })
                ->addColumn('kriteria_2', function ($stock) {
                    return $stock->material->criteria_2;
                })
                ->addColumn('stok', function ($stock) {
                    return $stock->stock;
                })
                ->addColumn('kode_produksi', function ($stock) {
                    return $stock->code;
                })
                ->addColumn('action', function ($stock) use ($material_id) {
                    $action = '<a href="' . route('material-stock.edit', ['material_id' => $material_id, 'material_stock_id' => $stock->id]) . '" class="btn btn-warning me-1"><span class="mdi mdi-pencil me-2"></span>Kelola Stok</a>';
                    $action .= '<a onclick="deleteMaterialStock(' . route('material-stock.destroy', ['id' => $stock->id]) . ')" href="javascript:;" class="btn btn-danger me-1"><span class="mdi mdi-delete me-2"></span>Hapus</a>';
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $stocks = MaterialStock::where('material_id', $material_id)->get();

        return view('material-stock.stock', [
            'stocks' => $stocks,
            'material_id' => $material_id,
            'title' => 'Stock Bahan'
        ]);
    }


    // client side untuk material list
    // public function material_list()
    // {
    //     $materials = Material::all();
    //     return view('material-stock.material-list', [
    //         "materials" => $materials,
    //         'title' => 'List Stok Bahan'
    //     ]);
    // }

    // serverside untuk material list
    public function material_list()
    {
        if (request()->ajax()) {
            $materials = Material::query();

            return DataTables::of($materials)
                ->addColumn('aksi', function ($material) {
                    return '<a class="btn btn-primary" href="' . route('material-stock.index', ['material_id' => $material->id]) . '"><span class="mdi mdi-pencil me-2"></span>Pilih Bahan</a>';
                })
                ->addColumn('total_stok', function ($material) {
                    return $material->stock_mutations()->sum('amount');
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('material-stock.material-list', [
            'title' => 'List Stok Bahan'
        ]);
    }

    public function create($material_id)
    {
        $material = Material::whereId($material_id)->first();

        $material_stocks = MaterialStock::join('materials', 'material_stocks.material_id', '=', 'materials.id')->where('materials.grade', 2)->select('material_stocks.*', 'materials.*')->get()->toArray();

        $codes = [];
        foreach ($material_stocks as $material_stock) {
            if ($material_stock['name'] === $material->name) {
                $codes[] = $material_stock['code'];
            }
            if ($material->name === "ASBP" || $material->name === "ASEG" || $material->name === "ASLO" || $material->name === "ASOG" || $material->name === "ASGR" || $material->name === "ASDB" || $material->name === "ASBR") {
                if ($material_stock['name'] === "BCWH")
                    $codes[] = $material_stock['code'];
            }
            if ($material->name === "WAXSEAL") {
                if ($material_stock['name'] === "WAXBLOK")
                    $codes[] = $material_stock['code'];
            }
        }
        //logic
        return view('material-stock.create', compact('material'), [
            'title' => 'Tambah Stock ' . $material->name,
            'codes' => array_unique($codes),
            'grade' => $material->grade
        ]);
    }

    public function store(Request $request)
    {
        $material = Material::whereId($request->material_id)->first();

        $material_stock = $material->material_stocks()->create($request->except(['_token', 'material_id', 'stock', 'report_at', 'price', 'information']));

        $price = $request->price;
        $report_at = $request->report_at;
        $information = $request->information;

        $material_stock->setStock($request->stock, [
            'description' => '<span class="badge bg-success">Stok Awal ' . $information . '</span>',
            'price' => $price,
            'report_at' => $report_at,
            'reference' => '',
        ]);

        if ($material->grade == 2) {
            DB::transaction(function () use ($material, $material_stock) {

                $last_material_code = $material->material_stocks()->latest('created_at')->skip(1)->value('code');

                $current_month = intval(date('m'));
                $current_year = intval(date('Y'));

                if ($last_material_code !== null) {
                    $last_code_parts = explode('/', $last_material_code);
                    $last_month = intval(substr($last_code_parts[1], 0, 2));
                    $last_year = intval(substr($last_code_parts[1], 2, 4));
                    $last_count = intval(substr($last_code_parts[2], -3));

                    if ($current_month !== $last_month || $current_year !== $last_year) {
                        $current_count = 1;
                    } else {
                        $current_count = $last_count + 1;
                    }
                    $generated_code = $material->grade . '/' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . $current_year . '/' . str_pad($current_count, 3, '0', STR_PAD_LEFT);
                } else {
                    $generated_code = $material->grade . '/' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . $current_year . '/' . str_pad(1, 3, '0', STR_PAD_LEFT);
                }


                $material_stock->update([
                    'code' => $generated_code
                ]);
            });
        } else {
            $material_stock->update($request->only(['code']));
        }

        $logData = [
            'name' => $material_stock->material->name,
            'criteria_1' => $material_stock->material->criteria_1,
            'criteria_2' => $material_stock->material->criteria_2,
            'information' => $material_stock->material->information,
            'grade' => $material_stock->material->grade,
            'stock' => $request->stock,
            'akumulasi' => $request->stock,
            'code' => $material_stock->code,
            'report_date' => Carbon::parse($report_at)->format('d/m/Y'),
            'price' => $price,
            'description' => 'Stok Awal ' . $information,
            'timestamp' => $material_stock->created_at->format('d/m/Y'),
        ];

        LogFilled::dispatch($logData);

        Auth()->user()->notify(new StockNotification($material_stock, $request->stock, 'new_stock'));

        return redirect()->route('material-stock.material-list')->with('success', 'Berhasil Menambah Stock');
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
        $id_material = $request->material_id;

        $report_at = $request->report_at;
        $price = $request->price;
        $information = $request->information;

        if ($request->increase_stock) {
            $last_amount = $material_stock->stock;
            $new_amount = $last_amount + $request->increase_stock;

            $increasedStock = $material_stock->increaseStock($request->increase_stock, [
                'description' => '<span class="badge bg-primary">Penambahan Stok ' . $information . '</span>',
                'price' => $price,
                'report_at' => $report_at,
                'reference' => '',
            ]);

            $logData = [
                'name' => $material_stock->material->name,
                'criteria_1' => $material_stock->material->criteria_1,
                'criteria_2' => $material_stock->material->criteria_2,
                'information' => $material_stock->material->information,
                'grade' => $material_stock->material->grade,
                'stock' => $request->increase_stock,
                'akumulasi' => $new_amount,
                'code' => $material_stock->code,
                'report_date' => Carbon::parse($report_at)->format('d/m/Y'),
                'price' => $price,
                'description' => 'Penambahan Stok ' . $information,
                'timestamp' => $material_stock->created_at->format('d/m/Y'),
            ];

            LogFilled::dispatch($logData);

            // Mengirim notifikasi penambahan stok
            Auth()->user()->notify(new StockNotification($material_stock, $increasedStock->amount, 'increase'));
        }

        if ($request->decrease_stock) {
            $last_amount = $material_stock->stock;
            $new_amount = $last_amount - $request->decrease_stock;

            $decreasedStock = $material_stock->decreaseStock($request->decrease_stock, [
                'description' => '<span class="badge bg-danger">Pengurangan Stok ' . $information . '</span>',
                'price' => $price,
                'report_at' => $report_at,
                'reference' => '',
            ]);


            $logData = [
                'name' => $material_stock->material->name,
                'criteria_1' => $material_stock->material->criteria_1,
                'criteria_2' => $material_stock->material->criteria_2,
                'information' => $material_stock->material->information,
                'grade' => $material_stock->material->grade,
                'stock' => -$request->decrease_stock,
                'akumulasi' => $new_amount,
                'code' => $material_stock->code,
                'report_date' => Carbon::parse($report_at)->format('d/m/Y'),
                'price' => $price,
                'description' => 'Pengurangan Stok ' . $information,
                'timestamp' => $material_stock->created_at->format('d/m/Y'),
            ];

            LogFilled::dispatch($logData);

            // Mengirim notifikasi pengurangan stok
            Auth()->user()->notify(new StockNotification($material_stock, $decreasedStock->amount, 'decrease'));
        }

        return redirect()->route('material-stock.material-list')->with('success', $material->name . ' Stock Updated!');
        // return redirect()->route('material-stock.index', ['material_id' => $id_material ])->with('success', $material->name . ' Stock Updated!');
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
        $material_stocks = MaterialStock::query()->with('stockMutations');

        $material_stocks = $material_stocks->get();

        return view('material-stock.logs', compact('material_stocks'))->with(['title' => 'Log Stock Bahan']);
    }

    public function logs_serverside()
    {
        return view('material-stock.logs', ['title' => 'Log Stock Bahan']);
    }

    public function logsData(Request $request)
    {
        $query = MaterialStock::query()->with(['stockMutations']);

        return datatables()->eloquent($query)
            ->addColumn('material_name', function ($material_stock) {
                return $material_stock->material->name;
            })
            ->addColumn('criteria_1', function ($material_stock) {
                return $material_stock->material->criteria_1;
            })
            ->addColumn('criteria_2', function ($material_stock) {
                return $material_stock->material->criteria_2;
            })
            ->addColumn('information', function ($material_stock) {
                return $material_stock->material->information;
            })
            ->addColumn('grade', function ($material_stock) {
                return $material_stock->material->grade;
            })
            ->addColumn('jumlah', function ($material_stock) {
                $amount = 0;
                foreach ($material_stock->stockMutations as $mutation) {
                    $amount = $mutation->amount;
                }
                return $amount;
            })
            // pada bagian ini perlu perbaikan untuk menghitung akumulasi yang terjadi dengan acuan data view awal yang menggunakan client side
            ->addColumn('akumulasi', function ($material_stock) {
                $accumulation = 0;
                foreach ($material_stock->stockMutations as $mutation) {
                    $accumulation += $mutation->amount;
                }
                return $accumulation;
            })
            ->addColumn('code', function ($material_stock) {
                return $material_stock->code;
            })
            ->addColumn('price', function ($material_stock) {
                $price = "";
                foreach ($material_stock->stockMutations as $mutation) {
                    $price = $mutation->price;
                }
                return $price;
            })
            ->addColumn('report_at', function ($material_stock) {
                $report_at = "";
                foreach ($material_stock->stockMutations as $mutation) {
                    if ($mutation->report_at)
                        try {
                            $date = \DateTime::createFromFormat('Y-m-d', $mutation->report_at);
                            if ($date !== false) {
                                $report_at = $date->format('d/m/Y');
                            } else {
                                $report_at = 'Invalid date format';
                            }
                        } catch (\Exception $e) {
                            echo 'Error: ' . $e->getMessage();
                        }
                }
                return $report_at;
            })
            ->addColumn('description', function ($material_stock) {
                $description = "";
                foreach ($material_stock->stockMutations as $mutation) {
                    $description = $mutation->description;
                }
                return $description;
            })->rawColumns(['description']) // Menggunakan rawColumns untuk memberi tahu DataTables bahwa kolom description adalah HTML
            ->addColumn('timestamp', function ($material_stock) {
                return $material_stock->created_at->format('d/m/Y');
            })
            ->toJson();
    }



    public function export(Request $request)
    {
        $created_at = request('created_at');

        // Lakukan operasi pengambilan data yang akan diekspor berdasarkan tanggal
        $dataToExport = MaterialStock::whereDate('created_at', $created_at)->get();

        // Konversi tanggal menjadi format yang sesuai untuk menyimpan dalam nama file
        $formattedDate = Carbon::parse($created_at)->format('Y-m-d');

        // Generate nama file yang unik
        $filename = 'material_stock_export_' . $formattedDate . '.xlsx';

        // Logic untuk ekspor data menggunakan library Excel
        return Excel::download(new MaterialStockExport($dataToExport), $filename);
    }
}
