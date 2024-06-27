@extends('template')
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
                    <table class="table table-bordered" id="detail">
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
            ]
        });
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
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
                    var oTable = $('#rencanaTabel').DataTable();
                    oTable.ajax.reload();
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
