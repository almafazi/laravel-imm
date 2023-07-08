@extends('layouts.master')

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang / List Stock Bahan / Stock Bahan /</span>
        {{ $title }}
    </h5>
    <!-- Form controls -->

    <form action="{{ route('material-stock.store') }}" method="post">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Input Stock Bahan</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" disabled class="form-control" id="name" value="{{ $material->name }}" />
                        <input type="hidden" name="material_id" value="{{ $material->id }}" id="">
                        <label for="name">Nama Bahan</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="stock" name="stock"
                            placeholder="Input stok awal" />
                        <label for="stock">Stock</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-control" id="code" placeholder="Input Kode Produksi" name="code">
                            @if ($grade === 2) {
                                <option value="generate code">Kode Produksi Baru</option>
                            }
                            @endif
                            @if ($grade !== 2) {
                                @foreach ($codes as $code)
                                    <option value="{{ $code }}">{{ $code }}</option>
                                @endforeach
                            }
                            @endif
                        </select>
                        <label for="code">Kode Produksi</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-between">
            <a href="{{ route('material-stock.index', ['material_id' => $material->id]) }}"
                class="btn btn-label-secondary"><span class="mdi mdi-arrow-left me-2"></span>Kembali</a>
            <button type="submit" class="btn btn-primary">
                <span class="mdi mdi-content-save me-2"></span>
                Simpan
            </button>
            @csrf
    </form>
@endsection
