@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Seluruh Perencanaan</h5>
                <div class="row">
                <table class="table table-bordered" id="monitoringfromAdmin">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Kode</th>
                                <th>Program/Kegiatan/KRO/RO/Komponen/Subkomp/Detil</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
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
        $('#monitoringfromAdmin').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.monitoring')}}",
            columns: [{
                    data: null,
                    name: 'DT_RowIndex',
                    className: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'kode',
                    name: 'kode',
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false,
                }
            ],
            order: [
                [0, 'desc']
            ]
        });
    });
</script>
@endsection
