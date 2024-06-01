@extends('template')
@section('content')

<div class="row">

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4>Profile</h4>
            </div>
            <div class="card-body">
                <!-- Baris Nama -->
                <div class="row">
                    <hr>
                    <div class="col-lg-4 ">
                        <h5><b>Nama</b></h5>
                    </div>
                    <div class="col-lg-1">
                        :
                    </div>
                    <div class="col-lg-6">
                        <h6>{{$profileAdmin->name}}</h6>
                    </div>
                    <hr>
                </div>
                <!-- Baris instansi -->
                <div class="row">
                    <!-- <hr> -->
                    <div class="col-lg-4 ">
                        <h5><b>Nama Instansi</b></h5>
                    </div>
                    <div class="col-lg-1">
                        :
                    </div>
                    <div class="col-lg-6">
                        <h6>Politeknik Negeri Indramayu</h6>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <img src="../assets2/img/features-2.png" class="img-fluid">

    </div>
</div>
@endsection
