@extends('layouts.master')

@section('content')
 <!-- Form controls -->

<form action="{{ route('material-stock.update') }}" method="post">
 <div class="col-md-6">
    <div class="card mb-4">
      <h5 class="card-header">Input Bahan</h5>
      <div class="card-body demo-vertical-spacing demo-only-element">
        <div class="form-floating form-floating-outline mb-4">
          <input
            type="text"
            disabled
            class="form-control"
            id="name"
            value="{{ $material->name }}" />
            <input type="hidden" name="material_id" value="{{ $material->id }}" id="">
            <input type="hidden" name="material_stock_id" value="{{ $material_stock->id }}" id="">
          <label for="name">Material</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="base_qty"
              name="base_qty"
              disabled
              value="{{ $material_stock->stock }}"
              placeholder="input base qty" />
            <label for="base_qty">base qty</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
            type="text"
            class="form-control"
            id="code"
            name="code"
            disabled
            value="{{ $material_stock->code }}"
            placeholder="input kode produksi" />
          <label for="code">input kode produksi</label>
        </div>
        <hr>
        <h6>Update Stock</h6>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="increase_stock"
              name="increase_stock"
              value="{{ $material_stock->input_qty }}"
              placeholder="input tambah stok" />
            <label for="increase_stock">Tambah Stok:</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="decrease_stock"
              name="decrease_stock"
              value="{{ $material_stock->output_qty }}"
              placeholder="input kurangi stok" />
            <label for="decrease_stock">Kurangi Stok:</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <textarea id="description" class="form-control" placeholder="Menambah atau mengurangi stok untuk?" name="description" id="" cols="300" rows="100"></textarea>
            <label for="description">Catatan</label>
        </div>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Simpan Data</button>
  @csrf
</form>
@endsection