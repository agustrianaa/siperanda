@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3>Kategori</h3>
    </div>
    <div class="row">
        <div class="row mb-3">
            <div class="col">
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" onclick="tambahkategori()" href="javascript:void(0)"><i class="ti ti-plus"></i> Tambahkan Kategori</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered" id="kategori">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Nama Kategori</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="kategori-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="kategoriForm" name="kategoriForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Nama Kategori</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" placeholder="Masukkan Kategori" maxlength="50" required="">
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
        $('#kategori').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.kategori')}}",
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
                    data: 'nama_kategori',
                    name: 'nama_kategori',
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

    function tambahkategori() {
        $('#kategoriForm').trigger("resset");
        $('#kategoriModal').html("Tambahkan kategori");
        $('#kategori-modal').modal('show');
        $('#id').val('');
    }

    $('#kategoriForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.tambah_kategori')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#kategori-modal").modal('hide');
                var oTable = $('#kategori').dataTable();
                oTable.fnDraw(false);
                $("#btn-save").html('Submit');
                $("#btn-save").attr("disabled", false);
                Swal.fire(
                    'Success!',
                    'Data berhasil ditambahkan/diubah.',
                    'success'
                );
            },
            error: function(data) {
                console.log(data);
            }
        })
    });
</script>
@endsection
