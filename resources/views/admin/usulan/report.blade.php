@extends('template')
@section('page-title')
<h4 class="fw-semibold mb-3 text-center">Report</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Kode Komponen</h5>
                    <div class="row">
                        <div class="col">
                            <div class="col mb-2">
                                Jika membutuhkan!
                            </div>
                            <br>
                            <div class="col-lg-6">
                                <a href="{{route('admin.export_kode')}}" class="btn btn-primary">Export data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Seluruh Rencana</h5>
                    <form id="exportForm" method="POST" action="{{route('admin.export_allRencana')}}">
                        @csrf
                        <div class="form-group">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="#" disabled selected> - Pilih Tahun - </option>
                                @for ($year = 2020; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
                                    @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Export Data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Rencana per Unit</h5>
                    <form id="exportForm" method="POST" action="{{route('admin.export_rencanaUnit')}}">
                        @csrf
                        <div class="form-group">
                            <select id="unit_id" name="unit_id" class="form-select" required>
                                <option value="" selected disabled>-- Pilih Unit --</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Export Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
