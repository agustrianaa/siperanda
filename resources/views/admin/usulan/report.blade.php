@extends('template')
@section('page-title')
<h4 class="fw-semibold">Report</h4>
@endsection
@section('content')
ini report yang perlu di report!
<br>
<a href="{{route('admin.export_kode')}}" class="btn btn-primary">Export data</a>
@endsection
