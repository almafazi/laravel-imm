@extends('layouts.master')

@section('style')
    <!-- Vendors CSS -->
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
    {{-- Sweet Alert --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    {{-- toaster --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <style>
        .table-responsive {
            padding: 20px;
        }

        .buttons-html5 {
            margin-right: 10px;
        }

        @media only screen and (max-width: 767px) {
            .button-file{
                display: flex;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Gudang / List Stock Bahan /</span>
        {{ $title }}
    </h5>

    <div class="card">
        <h2 class="card-header mt-2 mb-3">Stok Bahan</h2>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6  mt-4 mt-lg-0 button-file">
                    <a href="{{ route('material-stock.material-list') }}" class="btn btn-label-secondary mb-3 me-2"><span
                            class="mdi mdi-arrow-left me-2"></span>Kembali</a>
                    <a href="{{ route('material-stock.create', ['material_id' => $material_id]) }}"
                        class="btn btn-primary mb-3"><span class="mdi mdi-plus me-2"></span>Stok</a>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive text-nowrap">
                <table class="datatables-basic table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>nama bahan</th>
                            <th>stok</th>
                            <th>kode produksi</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($stocks as $stock)
                            @if ($stock->stock != 0)
                                <tr>
                                    <td>{{ $stock->material->name }}</td>
                                    <td>{{ $stock->stock }}</td>
                                    <td>{{ $stock->code }}</td>
                                    <td>
                                        <a href="{{ route('material-stock.edit', ['material_id' => $material_id, 'material_stock_id' => $stock->id]) }}"
                                            class="btn btn-warning me-1"><span class="mdi mdi-pencil me-2"></span>Kelola
                                            Stok</a>
                                        <a onclick="deleteMaterialStock('{{ route('material-stock.destroy', ['id' => $stock->id]) }}')"
                                            href="javascript:;" class="btn btn-danger me-1"><span
                                                class="mdi mdi-delete me-2"></span>Hapus</a>

                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!--/ Table -->
        </div>
    </div>
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
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/js/ui-toasts.js') }}"></script>
    <script src="{{ asset('assets/js/extended-ui-sweetalert2.js') }}"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "2000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            @if (\Session::has('error'))
                toastr.error('{{ \Session::get('error') }}');
            @elseif (\Session::has('success'))
                toastr.success('{{ \Session::get('success') }}', 'Success');
            @endif
        });
        setTimeout(() => {
            $('.alert-success').slideUp(1000);
        }, 2000);
        $(document).ready(function() {
            $('.table').DataTable();
        });
        const deleteMaterialStock = (url) => {
            Swal.fire({
                title: 'Yakin ingin hapus?',
                text: "Kamu mungkin akan kehilangan data selamanya!",
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
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Tidak Jadi',
                        text: 'Selamat Data Kamu Masih Utuh :)',
                        icon: 'error',
                        customClass: {
                            confirmButton: 'btn btn-success waves-effect'
                        }
                    });
                }
            });
        }
    </script>
@endsection
