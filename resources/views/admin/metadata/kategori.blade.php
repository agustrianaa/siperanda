@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3>Kategori</h3>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    </div>
                    <div class="col-auto">
                        <a class="btn btn-secondary" onclick="tambahkategori()" href="javascript:void(0)"><i class="ti ti-plus"></i> Tambahkan Kategori</a>
                    </div>
                </div>
                <br>
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
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" maxlength="50" required="">
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

    function tambahkategori(){
        $('#kategoriForm').trigger("resset");
        $('#kategoriModal').html("Tambahkan kategori");
        $('#kategori-modal').modal('show');
        $('#id').val('');
    }
</script>
@endsection
