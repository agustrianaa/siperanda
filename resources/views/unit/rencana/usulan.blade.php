@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Usulan</h5>
                <!-- <div class="row"> -->
                    <!-- <div class="col-lg-2"> -->
                    <a class="btn btn-secondary m-1" onclick="tambahUsulan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Usulan</a>
                    <!-- </div> -->
                    <!-- <div class="col-lg-2"> -->
                    <a class="btn btn-secondary m-1" onclick="tambahProgram()" href="javascript:void(0)"><i class="ti ti-plus"></i> Program</a>
                    <!-- </div> -->
                    <!-- <div class="col-lg-2"> -->
                        <a class="btn btn-secondary m-1" onclick="tambahKegiatan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Kegiatan</a>
                    <!-- </div> -->
                    <!-- <div class="col"> -->
                        <a class="btn btn-secondary m-1" onclick="tambahKRO()" href="javascript:void(0)"><i class="ti ti-plus"></i> KRO</a>
                    <!-- </div> -->
                    <!-- <div class="col"> -->
                        <a class="btn btn-secondary m-1" onclick="tambahRO()" href="javascript:void(0)"><i class="ti ti-plus"></i> RO</a>
                    <!-- </div> -->
                    <!-- <div class="col"> -->
                    <a class="btn btn-secondary m-1" onclick="tambahKomponen()" href="javascript:void(0)"><i class="ti ti-plus"></i> Komponen</a>
                    <!-- </div> -->
                    <!-- <div class="col"> -->
                    <a class="btn btn-secondary m-1" onclick="tambahSubKomp()" href="javascript:void(0)"><i class="ti ti-plus"></i> SubKomp</a>
                    <!-- </div> -->
                    <!-- <div class="col"> -->
                        <a class="btn btn-secondary m-1" onclick="tambahDetil()" href="javascript:void(0)"><i class="ti ti-plus"></i> Detil</a>
                    <!-- </div> -->
                <!-- </div> -->
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
                            <th width="7px">Volume</th>
                            <th width="7px">Satuan</th>
                            <th width="10%">Harga</th>
                            <th width="15%">Jumlah</th>
                            <th width="7px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- modal Usulan-->
    <div class="modal fade" id="usulan-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Usulan</h5>
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
    <!-- end bootstrap modal usulan -->

    <!-- modal Program-->
    <div class="modal fade" id="program-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Program</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="programForm" name="programForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="program">Pilihan Program</label>
                            <select class="form-control" name="program" id="program">
                                <option value="" disabled selected>- Pilih Program -</option>
                                <option value="Program">Dari database</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Jumlah Biaya</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="biaya" name="biaya" placeholder="Masukkan Jumlah Biaya" maxlength="50" required="">
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
    <!-- end bootstrap modal Program -->

    <!-- modal Kegiatan-->
    <div class="modal fade" id="kegiatan-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="kegiatanForm" name="kegiatanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="kegiatan">Pilihan Kegiatan</label>
                            <select class="form-control" name="kegiatan" id="kegiatan">
                                <option value="" disabled selected>- Pilih Program -</option>
                                <option value="Program">Dari database</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Jumlah Biaya</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="biaya" name="biaya" placeholder="Masukkan Jumlah Biaya" maxlength="50" required="">
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
    <!-- end bootstrap modal Kegiatan -->

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    function tambahUsulan() {
        $('#UsulanForm').trigger("resset");
        $('#UsulanModal').html("Tambahkan Usulan");
        $('#usulan-modal').modal('show');
        $('#id').val('');
    }

    function tambahProgram() {
        $('#ProgramForm').trigger("resset");
        $('#ProgramModal').html("Tambahkan Program");
        $('#program-modal').modal('show');
        $('#id').val('');
    }

    function tambahKegiatan() {
        $('#KegiatanForm').trigger("resset");
        $('#KegiatanModal').html("Tambahkan Kegiatan");
        $('#kegiatan-modal').modal('show');
        $('#id').val('');
    }
</script>
@endsection
