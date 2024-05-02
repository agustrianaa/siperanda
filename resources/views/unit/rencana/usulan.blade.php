@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Usulan</h5>
                <div class="row">
                <div class="col">
                    Tahun Anggaran
                </div>
                    <div class="col-auto">
                        <a class="btn btn-secondary" onclick="tambahUsulan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Pengajuan</a>
                    </div>
                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="usulan">
                    <thead>
                        <tr>
                            <th width="5px">No</th>
                            <th>Kode</th>
                            <th>Program/Kegiatan/KRO/RO/Komponen/Subkomp/Detil</th>
                            <th>Volume</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="usulan-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="usulanForm" name="usulanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="usulan_id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="usulan">Struktur Anggaran</label>
                            <select class="form-control" name="usulan" id="usulan">
                            <option value="" disabled selected>- Pilih Struktur -</option>
                                <option value="Program">Program</option>
                                <option value="Kegiatan">Kegiatan</option>
                                <option value="KRO">KRO</option>
                                <option value="RO">RO</option>
                                <option value="Komponen">Komponen</option>
                                <option value="Sub Komponen">Sub Komponen</option>
                                <option value="Detil">Detil</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Role</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="role" name="role" placeholder="Enter Role" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10"><br />
                            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
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

    function tambahUsulan(){
        $('#UsulanForm').trigger("resset");
        $('#UsulanModal').html("Tambahkan Usulan");
        $('#usulan-modal').modal('show');
        $('#id').val('');
    }
</script>
@endsection
