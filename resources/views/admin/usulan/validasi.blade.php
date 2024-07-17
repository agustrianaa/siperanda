@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Validasi Rencana Unit</h5>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            @if ($rencana->status == 'revisi')
            <div class="btn btn-danger">Revisi</div>
            @elseif ($rencana->status == 'approved')
            <div class="btn btn-success">Disetujui</div>
            @elseif ($rencana->status == 'rejected')
            <div class="btn btn-dark">Tidak Disetujui</div>
            @elseif ($rencana->status == 'draft')
            <div class="btn btn-secondary">Draft</div>
            @endif
        </div>
        <div class="col-auto">
            <a href="javascript:void(0)" onclick="tambahKetUsulan()" class="btn btn-dark">Tambah Keterangan</a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col"></div>
                <div class="col-auto">
                        <h5 class="card-title fw-semibold mb-3">Pagu : Rp. {{number_format($rencana->anggaran, 0, ',', '.')}}</h5>
                    </div>
                    <table class="table table-bordered" id="detail" style="width:100%">
                        <input type="hidden" id="rencana_id" value="{{ $rencana->id }}">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="5" style="text-align:right">Total:</th>
                                <th></th>
                                <!-- <th></th> -->
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- modal untuk menambahkan keterangan usulan -->
    <div class="modal fade" id="ketUsulan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Keterangan Usulan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="ketUsulanForm" name="ketUsulanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <!-- <input type="hidden" id="rencana_id_modal" name="rencana_id_modal"> -->
                        <div class="form-group mb-3">
                            <label for="status">Validasi</label>
                            <select name="status" id="status" class="form-select">
                                <option disabled selected>- Pilih Validasi -</option>
                                <option value="revisi"> Revisi</option>
                                <option value="approved"> Disetujui</option>
                                <option value="rejected">Tidak Disetujui</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="note">Catatan Usulan</label>
                            <div class="col-sm-12">
                                <textarea name="note" id="note" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
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
        var rencanaId = $('#rencana_id').val();
        $('#detail').DataTable({
            "dom": 't',
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('admin.tabeldetail') }}",
                type: 'GET',
                data: {
                    id: rencanaId
                },
                dataSrc: function(json) {
                    console.log(json); // Log the data received from server
                    return json.data;
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
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages
                total = api
                    .column(5)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(5, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(5).footer()).html(
                    'Rp ' + formatNumber(pageTotal, 0)
                );
            }

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
    });

    function tambahKetUsulan() {
        var rencanaId = $('#rencana_id').val();
        $('#ketUsulan').modal('show');
        console.log('id rencana adalah', rencanaId);
        $('#ketUsulanForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('id', rencanaId);

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.simpan_ketUsulan') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#ketUsulan").modal('hide');
                    location.reload();
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.success
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    }
</script>
@endsection
