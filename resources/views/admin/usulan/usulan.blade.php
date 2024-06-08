@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Usulan</h5>
                <div class="row">
                    <table class="table table-bordered" id="rencanaTabel">
                        <thead>
                            <tr>
                                <!-- <th width="5px">No</th> -->
                                <th>Kode</th>
                                <th>Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal untuk menambahkan keterangan usulan -->
    <div class="modal fade" id="ketUsulan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Keterangan Usulan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="ketUsulanForm" name="ketUsulanForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="detail_rencana_id" name="detail_rencana_id">
                        <div class="form-group mb-3">
                            <label for="status">Validasi</label>
                            <select name="status" id="status" class="form-select">
                                <option disabled selected>- Pilih Validasi -</option>
                                <option value="revisi"> Revisi</option>
                                <option value="disetujui"> Disetujui</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="note">Catatan Usulan</label>
                            <div class="col-sm-12">
                                <!-- <input type="textarea" id="" name="" placeholder="Masukkan Keterangan" class="form-control"> -->
                                <textarea name="note" id="note" class="form-control" placeholder="Masukkan Keterangan"></textarea>
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



<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#rencanaTabel').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.usulan') }}",
                type: 'GET',
                dataSrc: function(json) {
                    console.log(json); // Log the data received from server
                    return json.data;
                }
            },
            columns: [{
                    data: 'kodeUsulan',
                    name: 'kodeUsulan',
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                },
                {
                    data: 'volume',
                    name: 'volume',
                },
                {
                    data: 'satuan',
                    name: 'satuan',
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

    function tambahKetUsulan(id) {
        $('#ketUsulan').modal('show');
        console.log('id nya adalah', id);
        // $('#detail_rencana_id').val(id);
        $('#ketUsulanForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('id', id); // Tambahkan ID ke formData

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.simpan_ketUsulan') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (data) => {
                    $("#ketUsulan").modal('hide');
                    var oTable = $('#rencanaTabel').DataTable();
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
