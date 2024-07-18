@extends('template')
@section('page-title')
<h4 class="fw-semibold">Monitoring</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- filter -->
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold">Filter</h5>
                    <div class="row">
                    <div class="col-lg-3 mb-2">
                            <select name="fkategori" id="fkategori" class="form-select">
                                <option value="#" disabled selected> - Pilih Kategori - </option>
                                @if($kategoris->isEmpty())
                                <option disabled>Tidak ada kategori</option>
                                @else
                                @foreach($kategoris as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-3 mb-2">
                            <select name="ftahun" id="ftahun" class="form-select">
                                <option value="#" disabled selected> - Pilih Tahun - </option>
                                @for ($year = 2020; $year <= 9999; $year++) <option value="{{$year}}">{{$year}}</option>
                                    @endfor
                            </select>
                        </div>
                        <div class="col-lg-1 mb-1">
                            <button class="btn btn-dark" id="resetFilter">Reset</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end filter -->
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Seluruh Perencanaan</h5>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="monitoringfromUnit" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5px">No</th>
                                    <th>Kode</th>
                                    <th>Program/Kegiatan/KRO/RO/dsb</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Realisasi</th>
                                    <th>Sisa Anggaran</th>
                                    <th>Ket</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
            </div>
        </div>
    </div>

    <!-- modal untuk show -->
    <div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Detail Realisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="row"> -->
                    <div class="table-responsive">
                        <table class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Bulan RPD</th>
                                    <th class="text-center">Anggaran</th>
                                    <th class="text-center">Bulan Realisasi</th>
                                    <th class="text-center">Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- dari js -->
                            </tbody>
                        </table>
                    </div>

                    <!-- </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal show -->

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        dataRencana()

        function dataRencana() {
            var fkategori = $('#fkategori').val();
            var ftahun = $('#ftahun').val();
            var funit = $('#funit').val();
            $('#monitoringfromUnit').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('unit.monitoring')}}",
                    type: 'GET',
                    data: {
                        unit_id: funit,
                        kategori_id: fkategori,
                        tahun: ftahun,
                    }
                },
                columns: [
                    {
                        data: 'numbering',
                        name: 'numbering',
                        className: 'text-center',
                        orderable: false,
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    },
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
                            if (type === 'display') {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'jumlahUsulan',
                        name: 'jumlahUsulan',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'total_realisasi',
                        name: 'total_realisasi',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'sisa_anggaran',
                        name: 'sisa_anggaran',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return new Intl.NumberFormat('id-ID').format(data);
                            }
                            return data;
                        }
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
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Fungsi untuk menghapus format dari angka
                    var intVal = function(i) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ?
                            i : 0;
                    };

                    // Total untuk semua halaman
                    var totalUsulan = api
                        .column(6)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var totalRealisasi = api
                        .column(7)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var totalSisaAnggaran = api
                        .column(8)
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);



                    // Update footer
                    $(api.column(6).footer()).html(
                        'Rp ' + new Intl.NumberFormat('id-ID').format(totalUsulan)
                    );

                    $(api.column(7).footer()).html(
                        'Rp ' + new Intl.NumberFormat('id-ID').format(totalRealisasi)
                    );
                    $(api.column(8).footer()).html(
                        'Rp ' + new Intl.NumberFormat('id-ID').format(totalSisaAnggaran)
                    );
                }
            });
        }

        $('#funit').on('change', function() {
            dataRencana();
        });

        $('#fkategori').on('change', function() {
            dataRencana();
        });

        $('#ftahun').on('change', function() {
            dataRencana();
        });

        $('#resetFilter').click(function() {
            $('#funit').val("#").trigger('change');
            $('#fkategori').val("#").trigger('change');
            $('#ftahun').val("#").trigger('change');
            dataRencana();
        })
    });

    function show(id) {
        console.log(id);
        $.ajax({
            type: "GET",
            url: "{{ route('unit.getRealisasi') }}",
            data: {
                id: id
            },
            success: function(data) {
                // Kosongkan tabel sebelum mengisi data baru
                $('#showModal tbody').empty();

                const rpdData = data.rpd;
                const realisasiData = data.realisasi;
                const maxLength = Math.max(rpdData.length, realisasiData.length);

                let totalRpdAnggaran = 0;
                let totalRealisasiAnggaran = 0;

                for (let i = 0; i < maxLength; i++) {
                    const rpdItem = rpdData[i] || {};
                    const realisasiItem = realisasiData[i] || {};

                    const rpdAnggaran = parseFloat(rpdItem.jumlah || 0);
                    const realisasiAnggaran = parseFloat(realisasiItem.jumlah || 0);

                    totalRpdAnggaran += rpdAnggaran;
                    totalRealisasiAnggaran += realisasiAnggaran;

                    $('#showModal tbody').append(
                        '<tr>' +
                        '<td class="text-center">' + (rpdItem.bulan_rpd || '-') + '</td>' +
                        '<td class="text-center">' + (rpdAnggaran.toLocaleString('id-ID') || '-') + '</td>' +
                        '<td class="text-center">' + (realisasiItem.bulan_realisasi || '-') + '</td>' +
                        '<td class="text-center">' + (realisasiAnggaran.toLocaleString('id-ID') || '-') + '</td>' +
                        '</tr>'
                    );
                }

                if (maxLength === 0) {
                    $('#showModal tbody').append(
                        '<tr>' +
                        '<td colspan="4" class="text-center">Tidak ada data</td>' +
                        '</tr>'
                    );
                }

                // Tambahkan baris untuk total anggaran
                $('#showModal tbody').append(
                    '<tr>' +
                    '<td class="text-center fw-semibold">Total</td>' +
                    '<td class="text-center font-weight-bold">' + totalRpdAnggaran.toLocaleString('id-ID') + '</td>' +
                    '<td class="text-center fw-semibold">Total</td>' +
                    '<td class="text-center font-weight-bold">' + totalRealisasiAnggaran.toLocaleString('id-ID') + '</td>' +
                    '</tr>'
                );

                // Hitung sisa anggaran
                const sisaAnggaran = totalRpdAnggaran - totalRealisasiAnggaran;

                // Tambahkan baris untuk sisa anggaran
                $('#showModal tbody').append(
                    '<tr>' +
                    '<td class="text-center fw-semibold">Sisa Anggaran</td>' +
                    '<td colspan="3" class="text-center font-weight-bold">' + sisaAnggaran.toLocaleString('id-ID') + '</td>' +
                    '</tr>'
                );

                $('#showModal').modal('show');
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data realisasi');
            }
        });
    }
</script>
@endsection
