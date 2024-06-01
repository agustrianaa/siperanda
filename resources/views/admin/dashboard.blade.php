@extends('template')
@section('content')

<!--  Row 1 -->
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h5 class="card-title mb-3 fw-semibold">Jumlah Anggaran</h5>
                        <h4 class="fw-semibold mb-3">$6,820</h4>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <i class="ti ti-currency-dollar fs-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h5 class="card-title mb-3 fw-semibold">Jumlah Rencana Anggaran</h5>
                        <h4 class="fw-semibold mb-3">Rp. {{number_format($totalAnggaran, 0, ',', '.')}}</h4>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                                <i class="ti ti-currency-dollar fs-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h5 class="card-title mb-3 fw-semibold">Jumlah Usulan RKA</h5>
                        <h4 class="fw-semibold mb-3">{{$totalRKA}}</h4>
                    </div>
                    <div class="col-4">
                        <div class="d-flex justify-content-end">
                            <div class="text-white bg-secondary rounded-circle p-3 d-flex align-items-center justify-content-center">
                            <i class="ti ti-clipboard-text fs-6"></i>
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
