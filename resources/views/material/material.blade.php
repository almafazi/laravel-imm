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
        }

        .buttons-html5 {
            margin-right: 10px;
        }
    </style>
@endsection

@section('content')
    <!-- Basic Bootstrap Table -->
    <div class="card">
        <h5 class="card-header">Tabel Input Bahan</h5>
        @if (\Session::has('success'))
            <div class="alert alert-success mx-3">
                {!! \Session::get('success') !!}
            </div>
        @endif
        <div class="table-responsive text-nowrap">
            <a href="{{ route('material.create') }}" class="btn btn-primary mb-3 mx-2">Tambah Data</a>
            <a href="{{ route('material.export') }}" class="btn btn-primary mb-3 mx-2">Export Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>nama bahan</th>
                        <th>kriteria 1</th>
                        <th>kriteria 2</th>
                        <th>informasi</th>
                        <th>grade</th>
                        <th>aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
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

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <script>
        setTimeout(() => {
            $('.alert-success').slideUp(1000);
        }, 2000);
        const deleteMaterial = (url) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Saja',
                cancelButtonText: "Tidak Jadi",
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                ajax: '{{ route('material.datatable') }}',
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'criteria_1'
                    },
                    {
                        data: 'criteria_2'
                    },
                    {
                        data: 'information'
                    },
                    {
                        data: 'grade'
                    },
                    {
                        data: 'actions'
                    },
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                "oLanguage": {
                    "sSearch": "Cari:"
                }
            });
        });
    </script>
@endsection
