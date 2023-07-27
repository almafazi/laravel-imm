@extends('layouts.master')

@section('content')
    <!-- Form controls -->
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang / List Stock Bahan / Stock Bahan / </span>
        Kelola Stock {{ $material->name }}
    </h5>

    <form action="{{ route('material-stock.update') }}" method="post" id="form">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Kelola Stock Bahan</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" disabled class="form-control" id="name" value="{{ $material->name }}" />
                        <label for="name">Nama Bahan</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" disabled class="form-control" id="criteria_1"
                            value="{{ $material->criteria_1 }}" />
                        <label for="criteria_1">Kriteria 1</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" disabled class="form-control" id="criteria_2"
                            value="{{ $material->criteria_2 }}" />
                        <label for="criteria_2">Kriteria 2</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" disabled class="form-control" id="code"
                            value="{{ $material_stock->code }}" />
                        <label for="code">Kode Produksi</label>
                    </div>
                    <input type="hidden" name="material_id" value="{{ $material->id }}" id="">
                    <input type="hidden" name="material_stock_id" value="{{ $material_stock->id }}" id="">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="base_qty" name="base_qty" disabled
                            value="{{ $material_stock->stock }}" placeholder="input stock" />
                        <label for="base_qty">Stock</label>
                    </div>
                    <hr>
                    <h6>Kelola Stok</h6>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="increase_stock" name="increase_stock"
                            placeholder="Jumlah stok masuk" step=".01" min=”0″/>
                        <label for="increase_stock">Tambah Stok</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="decrease_stock" name="decrease_stock"
                            placeholder="Jumlah stok keluar" step=".01" min=”0″/>
                        <label for="decrease_stock">Kurangi Stok</label>
                    </div>
                    <h6>Tanggal Lapor</h6>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="date" class="form-control" id="flatpickr-date" name="report_at" value=""
                            placeholder="DD/MM/YYYY" />
                        <label for="flatpickr-date">Tanggal Pelaporan</label>
                    </div>
                    {{-- <div class="form-floating form-floating-outline mb-4">
            <textarea id="description" class="form-control" placeholder="Menambah atau mengurangi stok untuk?" name="description" id="" cols="300" rows="100"></textarea>
            <label for="description">Catatan</label>
        </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-between mb-4">
            <a href="{{ route('material-stock.index', ['material_id' => $material->id]) }}"
                class="btn btn-label-secondary"><span class="mdi mdi-arrow-left me-2"></span>Kembali</a>
            <button type="submit" class="btn btn-primary"><span class="mdi mdi-content-save me-2"></span>Simpan</button>
        </div>
        @csrf
    </form>
@endsection
@section('script')
    <script>
        flatpickr("#flatpickr-date", {
            parseDate: true,
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            defaultDate: new Date(),
        });
    </script>
    <script>
        $('#form').on('keydown', 'input', function(event) {
            if (event.which == 13) {
                var $allInputs = $('#form input, #form select')
                var $this = $(event.target);
                var index = $allInputs.index($this);
                if (index < $allInputs.length - 1) {
                    event.preventDefault();
                    $allInputs[index + 1].focus()
                }
            }
        });
    </script>
@endsection
