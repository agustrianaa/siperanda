@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Rencana Penarikan Dana</h5>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered" id="tabelRPD">
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
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal" tabindex="-1" id="validasi-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Validasi Usulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="validasiForm" name="validasiForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <!-- <input type="hidden" name="id" id="id"> -->
                    <div class="form-group">
                        <!-- <label for="name" class="col-sm-4 control-label">Validasi</label> -->
                        <select name="realisasi" id="realisasi" class="form-select" aria-label="Default select example" required="Wajib Dipilih">
                            <option selected disabled>- Pilih Validasi -</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="pending">Pending</option>
                            <option value="tidakdisetujui">Tidak Disetujui</option>
                        </select>
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
    <!-- END MODAL -->
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#tabelRPD').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.realisasi')}}",
                columns: [{
                        data: 'allkode',
                        name: 'allkode',
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    },
                    {
                        data: 'uraian_rencana',
                        name: 'uraian_rencana',
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

            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        });

        function validasiUsulan(id) {
            $('#validasi-modal').modal('show');
            console.log('ID sent for validation:', id);

            $('#validasiForm').off('submit').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.append('id', id); // Tambahkan ID ke formData

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.simpan_validasiRPD') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        $('#validasi-modal').modal('hide');
                        var oTable = $('#tabelRPD').DataTable();
                        oTable.ajax.reload();
                        $("#btn-save").html('Submit');
                        $("#btn-save").attr("disabled", false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.success
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });

            function lihatRPD(id) {

            }
        }
    </script>
    @endsection
