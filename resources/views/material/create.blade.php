@extends('layouts.master')

@section('style')
    <style>
        .no-rezise {
            resize: none;
            height: 15vh;
        }
    </style>
@endsection
@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang / Master Bahan /</span>
        {{ $title }}
    </h5>
    <!-- Form controls -->

    <form action="{{ route('material.store') }}" method="post">
        <div class="col-md-6">
            <div class="card mb-4">
                <h5 class="card-header">Input Master Bahan</h5>
                <div class="card-body demo-vertical-spacing demo-only-element">
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Input nama bahan" />
                        <label for="name">Nama Bahan</label>
                        @error('name')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="criteria_1" name="criteria_1"
                            placeholder="Input kriteria 1" />
                        <label for="criteria_1">Kriteria 1</label>
                        @error('criteria_1')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="criteria_2" name="criteria_2"
                            placeholder="Input kriteria 2" />
                        <label for="criteria_2">Kriteria 2</label>
                    </div>
                    <h5 class="">Informasi</h5>
                    <div class="">
                        <textarea class="form-control no-rezise" id="information" name="information" placeholder="Input Informasi"></textarea>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <select class="form-control" id="grade" placeholder="input grade" name="grade">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                        <label for="grade">Grade</label>
                        @error('grade')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-between">
            <a href="{{ route('material.index') }}" class="btn btn-label-secondary">
                <span class="mdi mdi-arrow-left me-2"></span>
                Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <span class="mdi mdi-content-save me-2"></span>
                Simpan Data
            </button>
        </div>
        @csrf
    </form>
@endsection
