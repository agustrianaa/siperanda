@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">Sample Page</h5>
            <p class="mb-0">This is a sample page </p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
        <table class="table table-bordered" id="user">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>role</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    </div>

</div>

@endsection
