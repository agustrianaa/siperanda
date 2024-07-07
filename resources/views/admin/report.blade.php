@extends('template')
@section('page-title')
<h4 class="fw-semibold mb-3 text-center">Report</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
    <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Seluruh Rencana</h5>
                    <form id="exportForm" method="GET" action="{{route('admin.export_Rencana')}}">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="unit_id">Pilih Unit:</label>
                            <select name="unit_id" id="unit_id" class="form-control">
                                <option selected disabled>-- Unit --</option>
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tahun">Pilih Tahun:</label>
                            <select name="tahun" id="tahun" class="form-control" required="">
                                <option value="#" disabled selected> -- Tahun -- </option>
                                @for($i = 2020; $i <= date('Y'); $i++) <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-info mt-3 float-end">Export Data</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Kode Komponen</h5>
                    <div class="row">
                        <div class="col">
                            <div class="row mb-2">
                                <div class="col">
                                Jika dibutuhkan!
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <a href="{{route('admin.export_kode')}}" class="btn btn-info float-end">Export data</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
