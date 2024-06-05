@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Rencana Penarikan Dana</h5>
                <div class="row">
                    <table class="table table-bordered" id="tabelRPD">
                        <thead>
                            <tr>
                                <!-- <th width="5px">No</th> -->
                                <th>Kode</th>
                                <th>Program/Kegiatan/KRO/RO/Komponen/Subkomp/Detil</th>
                                <th>Jumlah</th>
                                <th>Skedul</th>
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
                columns: [
                    {
                        data: 'kode',
                        name: 'kode',
                    },
                    {
                        data: 'uraian',
                        name: 'uraian',
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah',
                        render: function(data, type, row) {
                        return formatNumber(data);
                    }
                    },
                    {
                        data: 'skedul',
                        name: 'skedul',
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
        }
    </script>
    @endsection
