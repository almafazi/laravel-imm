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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Row Group CSS -->
    <style>
        .table-responsive {
            padding: 20px;
        }

        .buttons-html5 {
            margin-right: 10px;
        }

        .tanggal {
            background: #fff;
            cursor: pointer;
            border: 1px solid #ccc;
            text-align: center;
            padding: 5px 10px;
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
            <div class="col-6 d-flex">
                {{-- <div id="daterange" class="tanggal">
                    <i class="fas fa-calendar"></i>&nbsp;
                    <span></span>
                    <i class="fas fa-caret-down"></i>
                </div> --}}
                <a href="{{ route('material-stock.export') }}" class="btn btn-primary mx-2" id="updateExportLink">Export
                    Data</a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered" id="">
                    <thead class="table-light">
                        <tr>
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
                                    {{-- <td>
                                        {{ $mutation->report_at->format('d/m/Y') }}
                                    </td> --}}
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
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script>
        $(document).ready(function() {
            $('.table').DataTable({});
        });

        function updateExportLink(fromDate, toDate, keyword) {
            var exportUrl = "{{ route('material.export') }}?from_date=" + fromDate.format('YYYY-MM-DD') +
                "&to_date=" + toDate.format('YYYY-MM-DD') + "&keyword=" + keyword;
            $('#exportButton').attr('href', exportUrl);
        }

        /* $(function() {
            var start_date = moment().subtract(1, 'M');
            var end_date = moment();

            $('#daterange span').html(start_date.format('MMMM D, YYYY') + ' - ' + end_date.format('MMMM D, YYYY'));

            function loadData(fromDate, toDate) {
                var table = $('#daterange_table').DataTable({
                    destroy: true, // Hapus tabel yang sudah ada sebelumnya
                    processing: true,
                    // serverSide: true,
                    dataSrc: "",
                    ajax: {
                        url: "{{ route('material-stock.logs') }}",
                        data: function(data) {
                            data.from_date = fromDate.format('YYYY-MM-DD');
                            data.to_date = toDate.format('YYYY-MM-DD');
                        }
                    },
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'criteria_1',
                            name: 'criteria_1'
                        },
                        {
                            data: 'criteria_2',
                            name: 'criteria_2'
                        },
                        {
                            data: 'information',
                            name: 'information'
                        },
                        {
                            data: 'grade',
                            name: 'grade'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'akumulasi',
                            name: 'akumulasi'
                        },
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'timestamp',
                            name: 'timestamp'
                        }
                    ]
                });
            }

            function updateExportLink(fromDate, toDate) {
                var exportUrl = "{{ route('material.export') }}?from_date=" + fromDate.format('YYYY-MM-DD') +
                    "&to_date=" + toDate.format('YYYY-MM-DD');
                $('#exportButton').attr('href', exportUrl);
            }

            $('#daterange').daterangepicker({
                startDate: start_date,
                endDate: end_date
            }, function(start, end, label) {
                $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
                loadData(start, end);
                updateExportLink(start, end);
            });


            loadData(start_date, end_date);
            updateExportLink(start_date, end_date);
        }); */
    </script>
@endsection
