@extends('layouts.master')

@section('content')
 <!-- Form controls -->

<form action="{{ route('material.store') }}" method="post">
 <div class="col-md-6">
    <div class="card mb-4">
      <h5 class="card-header">Input Bahan</h5>
      <div class="card-body demo-vertical-spacing demo-only-element">
        <div class="form-floating form-floating-outline mb-4">
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="input nama" />
          <label for="name">Nama</label>
          @error('name')
                <div class="error">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-floating form-floating-outline mb-4">
            <input
              type="text"
              class="form-control"
              id="criteria_1"
              name="criteria_1"
              placeholder="input criteria 1" />
            <label for="criteria_1">criteria 1</label>
            @error('criteria_1')
                <div class="error">{{ $message }}</div>
           @enderror
          </div>
          <div class="form-floating form-floating-outline mb-4">
            <input
              type="text"
              class="form-control"
              id="criteria_2"
              name="criteria_2"
              placeholder="input criteria 2" />
            <label for="criteria_2">criteria 2</label>
          </div>
          <div class="form-floating form-floating-outline mb-4">
            <textarea
              class="form-control"
              id="information"
              name="information"
              placeholder="input information" ></textarea>
            <label for="information">information</label>
          </div>
          <div class="form-floating form-floating-outline mb-4">
            <select class="form-control"
            id="grade" placeholder="input grade"  name="grade">
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
  <button type="submit" class="btn btn-primary">Simpan Data</button>
  @csrf
</form>
@endsection