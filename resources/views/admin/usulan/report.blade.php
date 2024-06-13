@extends('template')
@section('page-title')
<h4 class="fw-semibold mb-3 text-center">Report</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3">Report</h5>
                <div class="row">
                <div class="col">
                    <div class="col-lg-2 mb-2">
                    ini report yang perlu di report!
                    </div>

                    <br>
                    <div class="col-lg-2">
                        <a href="{{route('admin.export_kode')}}" class="btn btn-primary">Export data</a>
                    </div>
                </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
