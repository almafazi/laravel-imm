@extends('layouts.master')

@section('style')
 <!-- Vendors CSS -->
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/node-waves/node-waves.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/typeahead-js/typeahead.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
 <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/flatpickr/flatpickr.css') }}" />
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
 <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" />
 <!-- Row Group CSS -->
 <style>
    .table-responsive {
        padding: 20px;
    }
    .buttons-html5 {
        margin-right: 10px;
    }
 </style>
@endsection

@section('content')

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <h5 class="card-header">Pilih Bahan</h5>
    
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>id</th>
            <th>name</th>
            <th>criteria_1</th>
            <th>criteria_2</th>
            <th>information</th>
            <th>grade</th>
            <th>total base qty</th>
            <th>action</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @foreach ($materials as $material)      
                <tr>
                    <td> {{ $material->id }}</td>
                    <td> {{ $material->name }}</td>
                    <td> {{ $material->criteria_1 }}</td>
                    <td> {{ $material->criteria_2 }}</td>
                    <td> {{ $material->information }}</td>
                    <td> {{ $material->grade }}</td>
                    <td> {{ $material->material_stocks()->sum('base_qty') }}</td>
                    <td> <a class="btn btn-danger" href="{{ route('material-stock.index', ["material_id" => $material->id]) }}">Pilih Bahan Ini</a></td>
                </tr>
            @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <!--/ Basic Bootstrap Table -->

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table').DataTable({
        });
    });
</script>
@endsection
