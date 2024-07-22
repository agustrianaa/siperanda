@extends('template')
@section('page-title')
<h4 class="fw-semibold">Rencana Penarikan Dana</h4>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="RPD" style="width: 100%">
                            <thead>
                                <tr>
                                    <!-- <th width="5px">No</th> -->
                                    <th>Kode</th>
                                    <th>Program/Kegiatan/KRO/RO/dsb</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga/sat</th>
                                    <th>Jumlah</th>
                                    <th width="5%">RPD</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="5" style="text-align:right">Total:</th>
                                    <th id="totalUsulan"></th>
                                    <th id="totalRPD"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal untuk rencana penarikan dana -->
    <div class="modal fade" id="rpd-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rencana Penarikan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="alert-warning" class="alert alert-warning d-none">
                    Anggaran melebihi Pagu
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="rpdForm" name="rpdForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="detail_rencana_id" id="detail_rencana_id">
                        <div class="row">
                            <div class="col-md-6">
                                @for ($month = 1; $month <= 6; $month++) <div class="mb-3">
                                    <label for="jumlah_{{ $month }}" class="form-label">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</label>
                                    <input type="number" class="form-control jumlah-input" id="jumlah_{{ $month }}" name="jumlah[{{ DateTime::createFromFormat('!m', $month)->format('F') }}]" placeholder="Masukkan anggaran untuk bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }}">
                            </div>
                            @endfor
                        </div>
                        <div class="col-md-6">
                            @for ($month = 7; $month <= 12; $month++) <div class="mb-3">
                                <label for="jumlah_{{ $month }}" class="form-label">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</label>
                                <input type="number" class="form-control jumlah-input" id="jumlah_{{ $month }}" name="jumlah[{{ DateTime::createFromFormat('!m', $month)->format('F') }}]" placeholder="Masukkan anggaran untuk bulan {{ DateTime::createFromFormat('!m', $month)->format('F') }}">
                        </div>
                        @endfor
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4>Total: <span id="totalJumlah">0</span></h4>
                </div>
            </div>
            <button type="submit" class="btn btn-primary float-end">Submit</button>
            </form>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
</div>

<!-- modal untuk show -->
<div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Detail RPD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <div class="row"> -->
                <table class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">Bulan RPD</th>
                            <th class="text-center">Anggaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- dari js -->
                    </tbody>
                </table>
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

        var table = $('#RPD').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.rpd')}}",
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
                    name: 'volume'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'harga',
                    name: 'harga',
                    render: function(data, type, row) {
                        return formatNumber(data);
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
                    data: 'rpd',
                    name: 'rpd',
                    className: 'text-center',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages for jumlahUsulan
                totalUsulan = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                // Update footer
                $(api.column(5).footer()).html(formatNumber(totalUsulan));
            }
        });
    });

    function formatNumber(num) {
        // Ubah ke tipe number jika num bukan number
        if (typeof num !== 'number') {
                num = parseFloat(num);
            }
            // Format angka dengan pemisah ribuan
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0

            });
    }

    function calculateTotal() {
        let total = 0;
        $('.jumlah-input').each(function() {
            let val = $(this).val();
            if (val) {
                total += parseFloat(val);
            }
        });
        $('#totalJumlah').text(total);
    }
    $(document).on('input', '.jumlah-input', function() {
        calculateTotal();
    });

    var id;

    function tambahRPD(_id) {
        id = _id;
        console.log('Menjalankan fungsi tambahRPD() dengan id:', id);
        $('#rpd-modal').modal('show');
        $('#rpdForm').trigger("reset");
        $('#detail_rencana_id').val(id);
    }

    $('#rpdForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var detailRencanaId = parseInt($('#detail_rencana_id').val()); // Pastikan ini integer
        formData.set('detail_rencana_id', detailRencanaId);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_skedul')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#rpd-modal").modal('hide');
                $("#btn-save").html('Submit');
                var oTable = $('#RPD').DataTable();
                oTable.ajax.reload(null, false);
                $("#btn-save").attr("disabled", false);
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.success
                });
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    function editRPD(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('unit.editRPD') }}",
            data: {
                id: id
            },
            success: function(data) {
                $('#rpd-modal').modal('show');
                $('#id').val(data.id);
                $('#detail_rencana_id').val(data.detail_rencana_id);
                for (let month = 1; month <= 12; month++) {
                    let monthName = new Date(0, month - 1).toLocaleString('default', {
                        month: 'long'
                    });
                    $('#jumlah_' + month).val(data.jumlah[monthName] || '');
                }
                calculateTotal();
            }
        });
    }

    $('#editForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('unit.updateRPD') }}",
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                var oTable = $('#RPD').DataTable();
                oTable.ajax.reload(null, false);
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data RPD berhasil di update'
                });
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengupdate data RPD');
            }
        });
    });

    function showRPD(id) {
        console.log(id);
        $.ajax({
            type: "GET",
            url: "{{ route('unit.getRealisasi',) }}",
            data: {
                id: id
            },
            success: function(data) {
                // Kosongkan tabel sebelum mengisi data baru
                $('#showModal tbody').empty();

                const rpdData = data.rpd;
                const maxLength = Math.max(rpdData.length);
                let totalRpdAnggaran = 0;

                for (let i = 0; i < maxLength; i++) {
                    const rpdItem = rpdData[i] || {};

                    const rpdAnggaran = parseFloat(rpdItem.jumlah || 0);

                    totalRpdAnggaran += rpdAnggaran;

                    $('#showModal tbody').append(
                        '<tr>' +
                        '<td class="text-center">' + (rpdItem.bulan_rpd || '-') + '</td>' +
                        '<td class="text-center">' + (rpdAnggaran.toLocaleString('id-ID') || '-') + '</td>' +
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
