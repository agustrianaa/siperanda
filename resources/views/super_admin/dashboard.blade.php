@extends('template')
@section('page-title')
<h4 class="fw-semibold">Dashboard</h4>
@endsection
@section('content')

<!--  Row 1 -->
<div class="row">
    <div class="col-lg-4">
        <div class="row">
            <div class="col-lg-12">
                <!-- Yearly Breakup -->

            </div>
            <div class="col-lg-12">
                <!-- Monthly Earnings -->
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold">Seluruh User </h5>
                                <h4 class="fw-semibold mb-3">{{$totalUser}}</h4>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-users fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="earning"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">

</div>
<div class="row">

</div>
@endsection
