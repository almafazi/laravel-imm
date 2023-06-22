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
    <h5 class="card-header">Table Basic</h5>
    
    <div class="table-responsive text-nowrap">
        <a href="{{ route('material-stock.create', ['material_id' => $material_id]) }}" class="btn btn-primary mb-3">Tambah Data</a>
      <table class="table">
        <thead>
          <tr>
            <th>material</th>
            <th>base qty</th>
            <th>input qty</th>
            <th>output qty</th>
            <th>code</th>
            <th>action</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($stocks as $stock)
              <tr>
                <td>{{ $stock->material->name }}</td>
                <td>{{ $stock->base_qty }}</td>
                <td>{{ $stock->input_qty }}</td>
                <td>{{ $stock->output_qty }}</td>
                <td>{{ $stock->code }}</td>
                <td></td>
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
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function () {
        $('.table').DataTable();
    });
</script>
@endsection

