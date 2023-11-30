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

    <!-- Moment.js & Date Range Picker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Custom CSS -->
    <style>
        .table-responsive {
            padding: 20px;
        }

        .tanggal {
            background: #fff;
            cursor: pointer;
            border: 1px solid #ccc;
            text-align: center;
            padding: 5px 10px;
        }

        .dt-buttons .btn.buttons-html5 {
            margin-right: 10px;
        }

        .buttons-page-length {
            margin-left: 10px;
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
                <table class="table table-bordered" id="table-logs">
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
                            @role('purchasing|finance')
                                <th>harga</th>
                            @endrole()
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
                                    <td>
                                        {{ $mutation->id }}
                                    </td>
                                    <td> {{ $material_stock->material->name }}</td>
                                    <td> {{ $material_stock->material->criteria_1 }}</td>
                                    <td> {{ $material_stock->material->criteria_2 }}</td>
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
                                    @role('purchasing|finance')
                                        <td>
                                            {{ $mutation->price }}
                                        </td>
                                    @endrole
                                    <td>
                                        @if ($mutation->report_at)
                                            @php
                                                try {
                                                    $date = \DateTime::createFromFormat('Y-m-d', $mutation->report_at);
                                                    if ($date !== false) {
                                                        echo $date->format('d/m/Y');
                                                    } else {
                                                        echo 'Invalid date format';
                                                    }
                                                } catch (\Exception $e) {
                                                    echo 'Error: ' . $e->getMessage();
                                                }
                                            @endphp
                                        @endif

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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#table-logs').DataTable({
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
                buttons: [{
                        extend: 'excel',
                        className: 'btn btn-primary mt-2 mt-lg-0'
                    },
                    {
                        extend: 'pageLength',
                        className: 'btn btn-secondary mt-2 mt-lg-0'
                    },
                ]
            });
        });
    </script>
@endsection
