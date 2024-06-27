@extends('template')
@section('page-title')
<h4 class="fw-semibold">Histori</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="histori">
                    <thead>
                        <th>Kode</th>
                        <th>Uraian</th>
                        <th>Total</th>
                    </thead>
                </table>
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
        $('#histori').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.histori')}}",
            columns: [{
                    data: 'allkode',
                    name: 'allkode',
                    className: 'text-center',
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
                    data: 'total',
                    name: 'total',
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
                },
            ]
        });
    });
</script>

@endsection
