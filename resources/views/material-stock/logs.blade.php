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
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang /</span>
        {{ $title }}
    </h5>
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h2 class="card-header mb-2 mt-3">Log Stok Bahan</h2>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="datatables-basic table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>id</th>
                            <th>name</th>
                            <th>kriteria 1</th>
                            <th>kriteria 2</th>
                            <th>informasi</th>
                            <th>grade</th>
                            <th>jumlah</th>
                            <th>akumulasi</th>
                            <th>kode produksi</th>
                            <th>tanggal lapor</th>
                            <th>deskripsi</th>
                            <th>timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($material_stocks as $material_stock)
                            @php
                                $accumulation = 0;
                            @endphp
                            @foreach ($material_stock->stockMutations as $mutation)
                                <tr>
                                    <td> {{ $material_stock->material->id }}</td>
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
                                        {{ $material_stock->report_at }}
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
                buttons: [{
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
