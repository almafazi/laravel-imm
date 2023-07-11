@extends('layouts.master')

@section('style')
    <style>
        .opacity {
            opacity: 0.9;
        }

        @media only screen and (max-width: 767px) {
            .responsive-rounded-parent {
                border-radius: 10px 10px 0px 0px;
            }
        }

        @media only screen and (min-width: 768px) {
            .responsive-rounded-parent {
                border-radius: 0px 10px 10px 0px;
            }
        }
    </style>
@endsection

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ $title }} /</span>
        {{ $title }}
    </h5>
    <!-- Gamification Card -->
    <div class="col-md-12 col-lg-8">
        <div class="card">
            <div class="d-flex align-items-end row">
                {{-- Side A --}}
                <div class="col-md-6 order-2 order-md-1">
                    <div class="card-body">
                        <h4 class="card-title pb-xl-2">Selamat Datang Di<strong> IMM Gudang!</strong>🎉</h4>
                        <hr class="border">
                        <p class="mb-0">Ini adalah halaman utama dari sistem <span class="fw-semibold">Gudang IMM</span>.
                        </p>
                        <p>Segera cek stok gudang anda.</p>
                        <a href="material" class="btn btn-primary">Masuk Gudang</a>
                    </div>
                </div>
                {{-- Side B --}}
                <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                    <div class="card-body pb-0 px-0 px-md-4 ps-0  bg-primary bg-gradient responsive-rounded-parent">
                        <div class="float-start text-dark card-header rounded-start bg-white bg-gradient opacity ms-3 shadow">
                            <span class="fw-semobild">Sing Bener Kerjane Mbok !</span> <br>
                            <hr class="border border-primary">
                            <span class="fw-bold mt-2">Monkey D.Dragon</span>
                        </div>
                        <img src="{{ asset('assets/img/illustrations/dragon.png') }}" height="250" alt="View Profile"
                            data-app-light-img="illustrations/dragon.png" data-app-dark-img="illustrations/dragon.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Gamification Card -->
@endsection
