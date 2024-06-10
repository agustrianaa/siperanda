@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Seluruh Perencanaan</h5>
                <div class="row">
                    <table class="table table-bordered" id="monitoringfromAdmin">
                        <thead>
                            <tr>
                                <!-- <th width="5px">No</th> -->
                                <th>Kode</th>
                                <th>Program/Kegiatan/KRO/RO/Komponen/dsb</th>
                                <th>Jumlah</th>
                                <th width="10%" class="text-center">RPD</th>
                                <th width="10%" class="text-center">Realisasi</th>
                                <th>Action</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
            </div>
        </div>
    </div>
    <!-- modal untuk menambahkan Realisasi -->
    <div class="modal fade" id="modalRealisasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Realisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="realisasiForm" name="realisasiForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="ket">Bulan Te - Realisasi</label>
                            <select name="bulan_realisasi" id="bulan_realisasi" class="form-select">
                                    <option value="">-Pilih Bulan</option>
                                    <option value="Januari">Januari</option>
                                    <option value="Februari">Februari</option>
                                    <option value="Maret">Maret</option>
                                    <option value="April">April</option>
                                    <option value="Mei">Mei</option>
                                    <option value="Juni">Juni</option>
                                    <option value="Juli">Juli</option>
                                    <option value="Agustus">Agustus</option>
                                    <option value="September">September</option>
                                    <option value="Oktober">Oktober</option>
                                    <option value="November">November</option>
                                    <option value="Desember">Desember</option>
                                </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="note">Jumlah</label>
                            <div class="col-sm-12">
                                <input type="text" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah" class="form-control">
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
<!-- end modal -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#monitoringfromAdmin').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.monitoring')}}",
            columns: [{
                    data: 'kode',
                    name: 'kode',
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                },
                {
                    data: 'jumlahUsulan',
                    name: 'jumlahUsulan',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
                },
                {
                    data: 'bulan_rpd',
                    name: 'bulan_rpd',
                },
                {
                    data: 'bulan_realisasi',
                    name: 'bulan_realisasi',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false,
                },
                {
                    data: 'ket',
                    name: 'ket',
                    className: 'text-center',
                    orderable: false,
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });

    var id;
    function tambahRealisasi(_id) {
        id = _id;
        $('#modalRealisasi').modal('show');
        $('#realisasiForm').trigger('reset');
    }

    $('#realisasiForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('detail_rencana_id', id);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_realisasi')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#modalRealisasi").modal('hide');
                $("#btn-save").html('Submit');
                var oTable = $('#monitoringfromAdmin').DataTable();
                oTable.ajax.reload(null, false);
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
        });
    });
</script>
@endsection
