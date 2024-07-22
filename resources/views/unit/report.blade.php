@extends('template')
@section('page-title')
<h4 class="fw-semibold">Report</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
    <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Seluruh Rencana</h5>
                    <form id="exportForm" method="GET" action="{{route('unit.export_rencana')}}">
                        @csrf
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-select" required="">
                                <option value="#" disabled selected> - Pilih Tahun - </option>
                                @for ($year = 2020; $year <= 9999; $year++) <option value="{{$year}}">{{$year}}</option>
                                    @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 d-block w-100">Export Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
