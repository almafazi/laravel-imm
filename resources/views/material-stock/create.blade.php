@extends('layouts.master')

@section('content')
 <!-- Form controls -->

<form action="{{ route('material-stock.store') }}" method="post">
 <div class="col-md-6">
    <div class="card mb-4">
      <h5 class="card-header">Input Stock Bahan</h5>
      <div class="card-body demo-vertical-spacing demo-only-element">
        <div class="form-floating form-floating-outline mb-4">
          <input
            type="text"
            disabled
            class="form-control"
            id="name"
            value="{{ $material->name }}" />
            <input type="hidden" name="material_id" value="{{ $material->id }}" id="">
          <label for="name">Nama Bahan</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="stock"
              name="stock"
              placeholder="Input stok awal" />
            <label for="stock">Stock</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="price"
              name="price"
              placeholder="Input harga" />
            <label for="price">Harga</label>
        </div>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Simpan Data</button>
  @csrf
</form>
@endsection
