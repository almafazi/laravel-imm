@extends('layouts.master')

@section('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset( 'assets/vendor/libs/node-waves/node-waves.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <!-- Row Group CSS -->
    <style>
        .table-responsive {
            padding: 10px;
            margin: 2px;
        }

        .buttons-html5 {
            margin-right: 10px;
        }

        @media only screen and (max-width: 767px) {
            .button-file {
                display: flex;
                justify-content: center;
            }
        }

        @media only screen and (max-width: 463px){
            .button-file {
                display: block;
            }
            .button-export {
                margin-top: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang /</span>
        {{ $title }}
    </h5>
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h2 class="card-header mt-2 mb-3">List Stok Bahan</h2>
        <div class="card-body">
            <div class="row">
                @role('admin')
                <div class="col-12 col-md-8 col-lg-6 button-file">
                    <a class="btn btn-label-secondary" href="{{ asset('example-export/example-export.xlsx') }}">
                        <span class="mdi mdi-file-document-outline me-2"></span>
                        format import
                    </a>
                    <a href="{{ route('material.export') }}" class="btn btn-label-primary mx-2 button-export">
                        <span class="mdi mdi-export-variant me-2"></span>
                        Export data
                    </a>
                </div>
                <div class="col-12 col-md-8 col-lg-6  mt-4 mt-lg-0 button-file">
                    <form action="{{ route('material-stock.import') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <input class="form-control" type="file" name="file" id="import">
                            <button class="btn btn-label-primary " type="submit"><span
                                    class="mdi mdi-file-import me-2"></span>Import</button>
                        </div>
                    </form>
                </div>
                @endrole
                @role('finance')
                <div class="col-12 col-md-8 col-lg-6 button-file">
                    <a href="{{ route('material.export') }}" class="btn btn-label-primary mx-2 button-export">
                        <span class="mdi mdi-export-variant me-2"></span>
                        Export data
                    </a>
                </div>
                @endrole
            </div>
            <div class="table-responsive text-nowrap mt-3 ">
                <table class="datatables-basic table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>id</th>
                            <th>nama bahan</th>
                            <th>kriteria 1</th>
                            <th>kriteria 2</th>
                            <th>informasi</th>
                            <th>grade</th>
                            <th>total stok</th>
                            <th class="text-center">aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($materials as $material)
                            <tr>
                                <td> {{ $material->id }}</td>
                                <td><a href="{{ route('material-stock.index', ['material_id' => $material->id]) }}"
                                        class="text-secondary fw-bold">{{ $material->name }}</a></td>
                                <td> {{ $material->criteria_1 }}</td>
                                <td> {{ $material->criteria_2 }}</td>
                                <td> {{ $material->information }}</td>
                                <td> {{ $material->grade }}</td>
                                <td>
                                    {{ $material->stock_mutations()->sum('amount') }}
                                    {{-- {{ $material->material_stocks()->withSum('stockMutations', 'amount')->get()->sum('stock_mutations_sum_amount') }} --}}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-primary"
                                        href="{{ route('material-stock.index', ['material_id' => $material->id]) }}"><span
                                            class="mdi mdi-pencil me-2"></span>
                                        Pilih Bahan
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({});
        });
    </script>
@endsection
