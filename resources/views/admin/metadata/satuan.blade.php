
@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3>Satuan</h3>
    </div>
    <div class="row">
        <div class="row mb-3">
            <div class="col">
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" onclick="tambahSatuan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Tambahkan satuan</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered" id="tabelSatuan">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Nama Satuan</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="satuan-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="satuanForm" name="satuanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Satuan</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Masukkan satuan" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger mr-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    <!-- end bootstrap model -->


</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#tabelSatuan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.satuan')}}",
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
                    data: 'satuan',
                    name: 'satuan',
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

    function tambahSatuan() {
        $('#satuanForm').trigger("resset");
        $('#satuan-modal .modal-title').html("Tambahkan satuan");
        $('#satuan-modal').modal('show');
        $('#id').val('');
    }

    function editSatuan(id){
        $.ajax({
            type: "POST",
            url: "{{ route('admin.edit_satuan')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#satuan-modal .modal-title').html("Edit Satuan");
                $('#satuan-modal').modal('show');
                $('#id').val(res.id);
                $('#satuan').val(res.satuan);
                console.log(res)
            },
        });
    }

    function hapusSatuan(id){
        Swal.fire({
        title: 'Delete Record?',
        text: "Anda yakin ingin menghapus data ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ajax request
            $.ajax({
                type: "POST",
                url: "{{ route('admin.hapus_satuan')}}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    var oTable = $('#tabelSatuan').DataTable();
                oTable.ajax.reload();
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                }
            });
        }
    });
    }

    $('#satuanForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.tambah_satuan')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#satuan-modal").modal('hide');
                var oTable = $('#satuan').DataTable();
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
