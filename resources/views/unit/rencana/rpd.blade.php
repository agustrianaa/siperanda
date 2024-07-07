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
                    <div class="table-responsive">
                        <table class="table table-bordered" id="RPD">
                            <thead>
                                <tr>
                                    <!-- <th width="5px">No</th> -->
                                    <th>Kode</th>
                                    <th>Program/Kegiatan/KRO/RO/dsb</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga/sat</th>
                                    <th>Jumlah</th>
                                    <th width="15%">RPD</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
            </div>
        </div>
    </div>

    <!-- modal untuk rencana penarikan dana -->
    <div class="modal fade" id="rpd-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rencana Penarikan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="rpdForm" name="rpdForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="detail_rencana_id" id="detail_rencana_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Skedul</label>
                            <div class="col-sm-12 mb-4">
                                <select name="bulan_rpd" id="bulan_rpd" class="form-select" required>
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
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah Duit nya</label>
                            <div class="col-sm-12 mb-4">
                                <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah duit nya" required>
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

    <!-- EDIT RPD MODAL -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit RPD</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Bulan RPD</th>
                                    <th class="text-center">Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data RPD akan diisi melalui JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END EDIT RPD MODAL -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#RPD').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.rpd')}}",
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
                        // Logika untuk menampilkan uraian dari kode komponen atau uraian rencana
                        if (row.uraian_kode_komponen) {
                            return row.uraian_kode_komponen;
                        } else {
                            return row.uraian_rencana;
                        }
                    }
                },
                {
                    data: 'volume',
                    name: 'volume'
                },
                {
                    data: 'satuan',
                    name: 'satuan'
                },
                {
                    data: 'harga',
                    name: 'harga',
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
                },
                {
                    data: 'jumlahUsulan',
                    name: 'jumlahUsulan',
                    render: function(data, type, row) {
                        return formatNumber(data);
                    }
                },
                {
                    data: 'bulan_rpd',
                    name: 'bulan_rpd',
                    render: function(data, type, row) {
                        if (data) {
                            return data; // Jika data tidak kosong, kembalikan nilainya
                        } else {
                            return ''; // Jika data kosong, kembalikan string kosong
                        }
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    orderable: false
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

    function tambahRPD(_id) {
        id = _id;
        console.log('Menjalankan fungsi tambahRPD() dengan id:', id);
        $('#rpd-modal').modal('show');
        $('#rpdForm').trigger("reset");
    }

    $('#rpdForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('detail_rencana_id', id);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_skedul')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#rpd-modal").modal('hide');
                $("#btn-save").html('Submit');
                var oTable = $('#RPD').DataTable();
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

    function editRPD(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('unit.getRealisasi') }}",
            data: {
                id: id
            },
            success: function(data) {
                $('#editModal tbody').empty();
                const rpdData = data.rpd || [];

                const bulanOptions = `
                    <option value="">-Pilih Bulan-</option>
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
                `;

                if (rpdData.length > 0) {
                    rpdData.forEach(function(item) {
                        const selectedBulan = item.bulan_rpd || '';

                        $('#editModal tbody').append(
                            '<tr>' +
                            '<td>' +
                            '<div class="form-group mb-3">' +
                            '<label for="bulan_rpd">Bulan RPD</label>' +
                            '<select name="bulan_rpd[]" class="form-select" required>' +
                            bulanOptions +
                            '</select>' +
                            '</div>' +
                            '</td>' +
                            '<td>' +
                            '<div class="form-group mb-3">' +
                            '<label for="jumlah">Jumlah</label>' +
                            '<input type="number" name="jumlah[]" value="' + (item.jumlah || '') + '" class="form-control">' +
                            '</div>' +
                            '</td>' +
                            '<input type="hidden" name="id[]" value="' + item.id + '">' +

                            '</tr>'
                        );

                        $('select[name="bulan_rpd[]"]').last().val(selectedBulan);
                    });
                } else {
                    $('#editModal tbody').append(
                        '<tr>' +
                        '<td colspan="2" class="text-center">Tidak ada data rpd</td>' +
                        '</tr>'
                    );
                }

                $('#editModal').modal('show');
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data rpd');
            }
        });
    }

    $('#editForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('unit.updateRPD') }}",
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                var oTable = $('#RPD').DataTable();
                    oTable.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data RPD berhasil di update'
                    });
                },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengupdate data RPD');
            }
        });
    });

</script>
@endsection
