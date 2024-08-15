@extends('template')
@section('page-title')
<h4 class="fw-semibold">Histori</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title fw-semibold mb-3">Detail Rencana</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="histori" style="width: 100%;">
                    <input type="hidden" id="rencana_id" value="{{ $rencana->id }}">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th width="30%">Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3">List Revision</h5>
                <div class="row mb-2">
                    <div class="form-group">
                        <select class="form-control" id="revision" name="'revision">
                            <option value="" selected disabled>Pilih Revisi...</option>
                            @foreach ($dataRevisi as $item )
                            <option value="{{$item}}">Revisi {{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="last" style="width:100%">
                        <thead>
                            <tr>
                                <!-- <th width="3px">No</th> -->
                                <th>Kode</th>
                                <th width="30%">Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        dataRencana();

        function dataRencana() {
            var rencanaId = $('#rencana_id').val();
            $('#histori').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('unit.detailHistori')}}",
                    type: 'GET',
                    data: {
                        id: rencanaId
                    },
                },
                columns: [{
                        data: 'allkode',
                        name: 'allkode',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    },
                    {
                        data: 'uraian',
                        name: 'uraian',
                        render: function(data, type, row) {
                            // Logika untuk menampilkan uraian dari kode komponen atau uraian rencana
                            if (row.uraian_kode_komponen) {
                                return row.uraian_kode_komponen;
                            } else {
                                return row.uraian_rencana;
                            }
                        }
                    },
                    {
                        data: 'volume',
                        name: 'volume',
                        className: 'text-center',
                    },
                    {
                        data: 'satuan',
                        name: 'satuan',
                    },
                    {
                        data: 'harga',
                        name: 'harga',
                        render: function(data, type, row) {
                            return formatNumber(data);
                        }
                    },
                    {
                        data: 'total',
                        name: 'total',
                        render: function(data, type, row) {
                            return formatNumber(data);
                        }
                    },
                ]
            });

        }
        dataRevisi();

function dataRevisi() {
    var revision = $('#revision').val();
    $('#last').DataTable({
        "dom": 't',
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: "{{route('unit.last')}}",
            type: 'GET',
            data: {
                revision: revision,
            }
        },
        columns: [{
                data: 'allkode',
                name: 'allkode',
                render: function(data, type, row) {
                    return data ? data : '';
                }
            },
            {
                data: 'uraian',
                name: 'uraian',
                render: function(data, type, row) {
                    // Logika untuk menampilkan uraian dari kode komponen atau uraian rencana
                    if (row.uraian_kode_komponen) {
                        return row.uraian_kode_komponen;
                    } else {
                        return row.uraian_rencana;
                    }
                }
            },
            {
                data: 'volume',
                name: 'volume',
            },
            {
                data: 'satuan',
                name: 'satuan',
            },
            {
                data: 'harga',
                name: 'harga',
                render: function(data, type, row) {
                    return formatNumber(data);
                }
            },
            {
                data: 'total',
                name: 'total',
                render: function(data, type, row) {
                    return formatNumber(data);
                }
            },
        ],
        order: [
            [0, 'desc']
        ]
    });
}
$('#revision').on('change', function() {
    dataRevisi();
});
    });
</script>

@endsection
