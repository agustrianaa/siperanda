@extends('template')
@section('page-title')
<h4 class="fw-semibold">Dashboard</h4>
@endsection
@section('content')
@php
$year = \Carbon\Carbon::parse($latestYear)->year;
@endphp
<!--  Row 1 -->
<div class="row">
<div class="col-lg-4">
        <div class="card h-75">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h6 class="card-title mb-3 fw-semibold">Jumlah Pagu Anggaran</h6>
                        <h5 class="fw-semibold mb-3">Rp. {{number_format($totalAnggaranGlobal->all_anggaran, 0, ',', '.')}}</h5>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="d-flex justify-content-end mb-4">
                                <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-currency-dollar fs-6"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-end">
                                <div class="me-2">
                                    <span class="round-8 bg-primary rounded-circle d-inline-block"></span>
                                    <span class="fs-3">
                                        <h7 class="fw-semibold">{{ $year }}</h7>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-75">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h6 class="card-title mb-3 fw-semibold">Jumlah Rencana Anggaran</h6>
                        <h5 class="fw-semibold mb-3">Rp. {{number_format($totalAnggaran, 0, ',', '.')}}</h5>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="d-flex justify-content-end mb-4">
                                <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-currency-dollar fs-6"></i>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-end">
                                <div class="me-2">
                                    <span class="round-8 bg-primary rounded-circle d-inline-block"></span>
                                    <span class="fs-3">
                                        <h7 class="fw-semibold">{{ $year }}</h7>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-75">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h6 class="card-title mb-3 fw-semibold">Jumlah Realisasi Anggaran</h6>
                        <h5 class="fw-semibold mb-3">Rp. {{number_format($totalRealisasi, 0, ',', '.')}}</h5>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="row">
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-currency-dollar fs-6"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-end">
                                    <div class="me-2">
                                        <span class="round-8 bg-primary rounded-circle d-inline-block"></span>
                                        <span class="fs-3">
                                            <h7 class="fw-semibold">{{ $year }}</h7>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-75">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h6 class="card-title mb-3 fw-semibold">Sisa Anggaran</h6>
                        <h5 class="fw-semibold mb-3">Rp. {{number_format($sisaAnggaran, 0, ',', '.')}}</h5>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="row">
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-currency-dollar fs-6"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-end">
                                    <div class="me-2">
                                        <span class="round-8 bg-primary rounded-circle d-inline-block"></span>
                                        <span class="fs-3">
                                            <h7 class="fw-semibold">{{ $year }}</h7>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="card h-75">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h6 class="card-title mb-3 fw-semibold">Jumlah Usulan RKA</h6>
                        <h5 class="fw-semibold mb-3">{{$totalRKA}}</h5>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="row">
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-clipboard-text fs-6"></i>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-end">
                                    <div class="me-2">
                                        <span class="round-8 bg-primary rounded-circle d-inline-block"></span>
                                        <span class="fs-3">
                                            <h7 class="fw-semibold">{{ $year }}</h7>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
</div>
@endsection
