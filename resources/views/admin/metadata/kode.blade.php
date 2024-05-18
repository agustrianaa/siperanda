
@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3>Kode</h3>
    </div>
    <div class="row">
        <div class="row mb-3">
            <div class="col">
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" onclick="tambahkode()" href="javascript:void(0)"><i class="ti ti-plus"></i> Tambahkan kode</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered" id="tabelKode">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>No Kode</th>
                                <th>Kode Parent</th>
                                <th>Kategori</th>
                                <th>Uraian</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="kode-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan kode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="kodeForm" name="kodeForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kode</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kode Parent</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode_parent" name="kode_parent" placeholder="Masukkan kode" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select">
                                <option disabled selected>- Pilih Kategori - </option>
                                @foreach($kategori as $kategori )
                                    <option value="{{ $kategori->id}}">{{ $kategori->nama_kategori}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Uraian</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="uraian" name="uraian" placeholder="Masukkan Uraian" maxlength="50" required="">
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
        $('#tabelKode').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.kode')}}",
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
                data: 'kode_parent',
                    name: 'kode_parent',
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori',
                },
                {
                    data: 'uraian',
                    name: 'uraian',
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

    function tambahkode() {
        $('#kodeForm').trigger("reset");
        $('#kode-modal .modal-title').html("Tambahkan Kode");
        $('#kode-modal').modal('show');
        $('#id').val('');
    }

    function editKode(id){
        $.ajax({
            type: "POST",
            url: "{{ route('admin.edit_kode')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                $('#kode-modal .modal-title').html("Edit Kode");
                $('#kode-modal').modal('show');
                $('#id').val(res.id);
                $('#kode').val(res.kode);
                $('#kode_parent').val(res.kode_parent);
                $('#uraian').val(res.uraian);
                $('#kategori_id').val(res.kategori_id).change();
            }
        });
    }

    $('#kodeForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_kode')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#kode-modal").modal('hide');
                var oTable = $('#tabelKode').DataTable();
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
