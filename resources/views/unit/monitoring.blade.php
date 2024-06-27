@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Seluruh Perencanaan</h5>
                <div class="row">
                    <table class="table table-bordered" id="monitoringfromUnit">
                        <thead>
                            <tr>
                                <!-- <th width="5px">No</th> -->
                                <th>Kode</th>
                                <th>Program/Kegiatan/KRO/RO/dsb</th>
                                <th>Jumlah</th>
                                <th>RPD</th>
                                <th>Realisasi</th>
                                <th>Ket</th>
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
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#monitoringfromUnit').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.monitoring')}}",
            columns: [
                {
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
                    data: 'jumlahUsulan',
                    name: 'jumlahUsulan',
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
                },
                {
                    data: 'bulan_rpd',
                    name: 'bulan_rpd',
                },
                {
                    data: 'bulan_realisasi',
                    name: 'bulan_realisasi',
                },
                {
                    data: 'ket',
                    name: 'ket',
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
