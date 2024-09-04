@extends('template')
@section('page-title')
<h4 class="fw-semibold mb-3 text-center">Anggaran</h4>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col"></div>
        <div class="col-auto">
            <a onclick="tambahAnggaran()" href="javascript:void(0)" class="btn btn-info">Tambah anggaran</a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="anggaran" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Anggaran</th>
                            <th>Total Realisasi</th>
                            <th>Sisa Anggaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                </div>

            </div>
        </div>
    </div>

    <!-- modal untuk menambahkan anggaran -->
    <div class="modal fade" id="anggaran-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Anggaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="anggaranForm" name="anggaranForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group mb-2">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select" required="Pilih Tahun">
                                <option disabled selected>-Pilih Tahun-</option>
                                @for ($year = 2020; $year <= 9999; $year++) <option value="{{$year}}">{{$year}}</option>
                                    @endfor
                            </select>
                            @if ($errors->has('year'))
                            <span class="text-danger">{{$errors->first('year')}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Anggaran</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="all_anggaran" name="all_anggaran" placeholder="Masukkan Anggaran" maxlength="50" required="">
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
    <!-- end modal anggaran -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#anggaran').DataTable({
            processing: true,
            serveSide: true,
            ajax: "{{route('admin.anggaran')}}",
            columns: [{
                    data: 'tahun',
                    name: 'tahun',
                    className: 'text-center',
                },
                {
                    data: 'all_anggaran',
                    name: 'all_anggaran',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data ? formatNumber(data) : '0';
                    }
                },
                {
                    data: 'total_realisasi',
                    name: 'total_realisasi',
                    className: 'text-center',
                    render: function(data, type, row) {
                        return data ? formatNumber(data) : '0';
                    }
                },
                {
                    data: 'sisaAnggaran',
                    name: 'sisaAnggaran',
                    render: function(data, type, row) {
                        return data ? formatNumber(data) : '0';
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
        })

        function formatNumber(num) {
            // Ubah ke tipe number jika num bukan number
            if (typeof num !== 'number') {
                num = parseFloat(num);
            }
            // Format angka dengan pemisah ribuan
            return num.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0

            });
        }
    });

    function tambahAnggaran() {
        $('#anggaran-modal').modal('show');
        $('#anggaranForm').trigger('reset');
    }

    function editAnggaran(id) {
        console.log(id);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.edit_anggaran')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#anggaran-modal .modal-title').html("Edit Anggaran");
                $('#anggaran-modal').modal('show');
                $('#id').val(res.id);
                $('#all_anggaran').val(res.all_anggaran);
                $('#tahun').val(res.tahun);
            }
        });
    }

    $('#anggaranForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_anggaran')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#anggaran-modal").modal('hide');
                var oTable = $('#anggaran').DataTable();
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

    function hapusAnggaran(id){
    Swal.fire({
    title: 'Hapus Data?',
        text: "Anda yakin ingin menghapus data kategori ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ajax request
            $.ajax({
                type: "POST",
                url: "{{ route('admin.hapus_anggaran')}}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    var oTable = $('#anggaran').DataTable();
                oTable.ajax.reload();
                    Swal.fire(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    );
                }
            });
        }
    });
}
</script>
@endsection
