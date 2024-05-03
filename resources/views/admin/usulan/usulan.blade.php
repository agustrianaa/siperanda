@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Usulan</h5>
                <p class="mb-0">This is a sample page </p>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="usulan">
                    <thead>
                        <tr>
                            <th width="5px">No</th>
                            <th>Kode</th>
                            <th>Program/Kegiatan/KRO/RO/Komponen/Subkomp/Detil</th>
                            <th>Volume</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endsection
