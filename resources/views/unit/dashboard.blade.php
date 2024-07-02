@extends('template')
@section('content')

<!--  Row 1 -->
<div class="row">
<div class="col-lg-4">
    <div class="row">
        <div class="col-lg-12">
            <!-- Yearly Breakup -->
            <div class="card overflow-hidden">
                <div class="card-body p-4">
                    <h5 class="card-title mb-9 fw-semibold">Jumlah Rencana Anggaran</h5>
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 class="fw-semibold mb-3">Rp. {{number_format($totalAnggaran, 0, ',', '.')}}</h4>
                            <div class="d-flex align-items-center mb-3">

                            </div>
                            <div class="d-flex align-items-center">
                                <div class="me-4">
                                    <span class="round-8 bg-primary rounded-circle me-2 d-inline-block"></span>
                                    <span class="fs-2">2023</span>
                                </div>
                                <div>
                                    <span class="round-8 bg-light-primary rounded-circle me-2 d-inline-block"></span>
                                    <span class="fs-2">2023</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-center">
                                <div id="breakup"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="col-lg-4">
    <!-- Monthly Earnings -->
    <div class="card">
        <div class="card-body">
            <div class="row alig n-items-start">
                <div class="col-8">
                    <h5 class="card-title mb-9 fw-semibold"> Jumlah Usulan </h5>
                    <h4 class="fw-semibold mb-3">{{$totalRencana}}</h4>
                    <div class="d-flex align-items-center pb-1">
                        <span class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                            <i class="ti ti-arrow-down-right text-danger"></i>
                        </span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end">
                        <div class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                            <i class="ti ti-currency-dollar fs-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="earning"></div>
    </div>
</div>
</div>

<div class="row">
</div>

<script>

</script>
@endsection
