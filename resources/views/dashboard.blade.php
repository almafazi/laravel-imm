@extends('layouts.master')

@section('content')
    <h5 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ $title }} /</span>
        {{ $title }}
    </h5>
    <!-- Gamification Card -->
    <div class="col-md-12 col-lg-8">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="card-body">
                        <h4 class="card-title pb-xl-2">Selamat Datang Di<strong> IMM Gudang!</strong>🎉</h4>
                        <p class="mb-0">Ini adalah halaman utama dari sistem <span class="fw-semibold">Gudang IMM</span>.</p>
                        <p>Segera cek stok gudang anda.</p>
                        <a href="material" class="btn btn-primary">Masuk Gudang</a>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                    <div class="card-body pb-0 px-0 px-md-4 ps-0">
                        <img src="{{ asset('assets/img/illustrations/illustration-john-light.png') }}" height="180"
                            alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                            data-app-dark-img="illustrations/illustration-john-dark.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Gamification Card -->
@endsection
