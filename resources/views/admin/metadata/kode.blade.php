@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3>Kode</h3>
    </div>
    <div class="row">
        <div class="row mb-3">
            <div class="col">
            </div>
            <div class="col-auto">
                <a class="btn btn-secondary" onclick="tambahkode()" href="javascript:void(0)"><i class="ti ti-plus"></i> Tambahkan kode</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table table-bordered" id="tabelKode">
                        <thead>
                            <tr>
                                <th width="5px">No</th>
                                <th>Kode</th>
                                <th>Kode Parent</th>
                                <th>Kategori</th>
                                <th class="text-center">Uraian</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="kode-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan kode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="kodeForm" name="kodeForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kode</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kode Parent</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode_parent_display" placeholder="Cari Kode Parent" maxlength="50" >
                                <input type="hidden" id="kode_parent" name="kode_parent" >
                                <div id="kode-results" class="dropdown-menu" style="display: none; position: absolute; width: 100%;" required=""></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select" aria-required="true" required>
                                <option disabled selected>- Pilih Kategori - </option>
                                @foreach($kategori as $item )
                                <option value="{{ $item->id}}">{{ $item->nama_kategori}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Uraian</label>
                            <div class="col-sm-12">
                                <textarea name="uraian" id="uraian" class="form-control" placeholder="Masukkan Keterangan" required=""></textarea>
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
        $('#tabelKode').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.kode')}}",
            columns: [
                {
                    data: null,
                    name: 'DT_RowIndex',
                    className: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'kode',
                    name: 'kode',
                    className: 'text-center',
                },
                {
                    data: 'parent_kode',
                    name: 'parent_kode',
                    className: 'text-center',
                },
                {
                    data: 'nama_kategori',
                    name: 'nama_kategori',
                    className: 'text-center',
                },
                {
                    data: 'uraian',
                    name: 'uraian',
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

        $('#kode_parent_display').on('input', function() {
            let searchValue = $(this).val();
            if (searchValue.length > 0) {
                $.ajax({
                    url: '/admin/search/code_parent',
                    method: 'GET',
                    data: {
                        search: searchValue
                    },
                    success: function(data) {
                        console.log('Data received:', data);
                        let results = $('#kode-results');
                        results.empty();
                        if (data.length > 0) {
                            $.each(data, function(index, item) {
                                results.append(`<div class="dropdown-item" data-id="${item.id}" data-kode="${item.kode}" data-uraian="${item.uraian || ''}">${item.kode}.${item.kode_parent|| ''} - ${item.uraian || 'Uraian Kosong'}</div>`);
                            });
                            results.show();
                        } else {
                            results.hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                    }
                });
            } else {
                $('#kode-results').hide();
            }
        });


        // Handle click on search results
        $(document).on('click', '#kode-results .dropdown-item', function() {
            let selectedId = $(this).data('id');
            let selectedKode = $(this).data('kode');
            let selectedUraian = $(this).data('uraian');
            $('#kode_parent').val(selectedId);
            $('#kode_parent_display').val(`${selectedKode} - ${selectedUraian}`);
            $('#kode-results').hide();
        });

        // Hide results when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#kode_parent_display').length && !$(event.target).closest('#kode-results').length) {
                $('#kode-results').hide();
            }
        });
    });

    function tambahkode() {
        $('#kodeForm').trigger("reset");
        $('#kode-modal .modal-title').html("Tambahkan Kode");
        $('#kode-modal').modal('show');
        $('#id').val('');
    }

    function editKode(id) {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.edit_kode')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                console.log(res);
                $('#kode-modal .modal-title').html("Edit Kode");
                $('#kode-modal').modal('show');
                $('#id').val(res.id);
                $('#kode').val(res.kode);
                $('#kode_parent').val(res.kode_parent);
                $('#uraian').val(res.uraian);
                $('#kategori_id').val(res.kategori_id).change();
            }
        });
    }

    function hapusKode(id) {
        Swal.fire({
            title: 'Delete Record?',
            text: "Anda yakin ingin menghapus data ini?",
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
                    url: "{{ route('admin.hapus_kode')}}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        var oTable = $('#tabelKode').DataTable();
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


    $('#kodeForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_kode')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#kode-modal").modal('hide');
                var oTable = $('#tabelKode').DataTable();
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
</script>
@endsection
