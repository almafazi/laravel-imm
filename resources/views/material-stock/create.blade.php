@extends('layouts.master')

@section('content')
 <!-- Form controls -->

<form action="{{ route('material-stock.store') }}" method="post">
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
          <label for="name">Material</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="number"
              class="form-control"
              id="stock"
              name="stock"
              placeholder="input base qty" />
            <label for="stock">Stock</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
            type="text"
            class="form-control"
            id="code"
            name="code"
            placeholder="input kode produksi" />
            <label for="code">input kode produksi</label>
        </div>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Simpan Data</button>
  @csrf
</form>
@endsection