
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
                    <table class="table table-bordered" id="satuan">
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
                    <h5 class="modal-title">Tambahkan satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="satuanForm" name="satuanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Satuan</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="nama_satuan" name="nama_satuan" placeholder="Masukkan satuan" maxlength="50" required="">
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
    });

    function tambahSatuan() {
        $('#satuanForm').trigger("resset");
        $('#satuanModal').html("Tambahkan satuan");
        $('#satuan-modal').modal('show');
        $('#id').val('');
    }
</script>
@endsection
