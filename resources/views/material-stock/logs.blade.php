@extends('layouts.master')

@section('style')
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.bootstrap5.min.css" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
        <h5 class="card-header">Log Stok Bahan</h5>
        <div class="row">
            <div class="col-5 d-flex justify-content-start mx-3">
                <a href="{{ route('material.export') }}" class="btn btn-primary mx-2">Export Data</a>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table" id="daterange_table">
                <thead>
                    <tr>
                        <th class="search-input">Nama</th>
                        <th class="search-input">Kriteria 1</th>
                        <th class="search-input">Kriteria 2</th>
                        <th class="search-input">Satuan</th>
                        <th class="search-input">Grade</th>
                        <th class="search-input">Jumlah</th>
                        <th class="search-input">Akumulasi</th>
                        <th class="search-input">Kode Produksi</th>
                        <th class="search-input">Informasi</th>
                        <th class="search-input">Deskripsi</th>
                        <th class="search-input">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($material_stocks as $material_stock)
                        @php
                            $accumulation = 0;
                        @endphp
                        @foreach ($material_stock->stockMutations as $mutation)
                            <tr>
                                <td> {{ $material_stock->material->name }}</td>
                                <td> {{ $material_stock->material->criteria_1 }}</td>
                                <td> {{ $material_stock->material->criteria_2 ?? '-' }}</td>
                                <td> {{ $material_stock->material->information }}</td>
                                <td> {{ $material_stock->material->grade }}</td>
                                <td>
                                    {{ $mutation->amount }}
                                </td>
                                <td>
                                    {{ $accumulation = $accumulation + $mutation->amount }}
                                </td>
                                <td>
                                    {{ $material_stock->code }}
                                </td>
                                <td>
                                    {{ $material_stock->informasi }}
                                </td>
                                <td>
                                    {!! $mutation->description ?? '' !!}
                                </td>
                                <td>
                                    {{ $mutation->created_at->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Basic Bootstrap Table -->
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('.table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            orthogonal: 'export',
                            columns: '.search-input:visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            orthogonal: 'export',
                            columns: '.search-input:visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            orthogonal: 'export',
                            columns: '.search-input:visible'
                        }
                    }
                ]
            });
    
            // Pencarian saat mengetik di input search
            $('.search-input input').on('keyup change', function() {
                var columnIndex = $(this).closest('th').index();
                table.column(columnIndex).search(this.value).draw();
            });
        });
    </script>
    
    
@endsection
