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
                    <table class="table table-bordered" id="RPD">
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

    <!-- modal untuk rencana penarikan dana -->
    <div class="modal fade" id="rpd-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rencana Penarikan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="rpdForm" name="rpdForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Skedul</label>
                        <div class="col-sm-12">
                            <input type="date" class="form-control" id="skedul" name="skedul" placeholder="Masukkan Harga" maxlength="50" required="">
                        </div>
                    </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger mr-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
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
        $('#RPD').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.rpd')}}",
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
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
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

    function tambahRPD(id) {
        console.log('Menjalankan fungsi tambahRPD() dengan id:', id);
        $('#rpdForm').trigger("reset");
        $('#rpd-modal').modal('show');
    }

    $('#rpdForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_skedul')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#rpd-modal").modal('hide');
                var oTable = $('#RPD').DataTable();
                oTable.ajax.reload();
                $("#btn-save").html('Submit');
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
        })
    });
</script>
@endsection
