@extends('template')
@section('page-title')
<h4 class="fw-semibold">Usulan</h4>
@endsection
@section('content')

<div class="container-fluid">
    @if(!$rencanaId)
            <div class="alert alert-warning">
                Rencana kerja anggarannya belum dibuka
            </div>
        @else
    <div class="row">
        <div class="row">
            <div class="row mb-3">
                <div class="col"></div>
                <!-- <div class="col-auto">
                    <a class="btn btn-secondary m-1" onclick="tambahUsulan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Usulan</a>
                </div> -->
                <div class="col-auto">
                    <a class="btn btn-success m-1" onclick="tambahRencana()" href="javascript:void(0)"><i class="ti ti-plus"></i> Rencana</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="usulan">
                    <thead>
                        <tr>
                            <th width="3px">No</th>
                            <th>Kode</th>
                            <th width="30%">Uraian</th>
                            <th>Volume</th>
                            <th>Satuan</th>
                            <th>Harga</th>
                            <th width="15%">Jumlah</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- modal Usulan-->
    <!-- <div class="modal fade" id="usulan-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Tahun Usulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="rencanaForm" name="rencanaForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                        <input type="hidden" id="rencana_id" name="rencana_id">
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Kode</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" maxlength="50" required="">
                                <input type="hidden" id="kode_komponen_id" name="kode_komponen_id">
                                <div id="kode-results" name="kode-results" class="dropdown-menu" style="display: none; position: absolute; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Volume</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="volume" name="volume" placeholder="Masukkan volume" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control">
                                <option disabled selected>-Pilih Satuan-</option>
                                @foreach ($satuan as $item )
                                <option value="{{$item->id}}">{{$item->satuan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-4 control-label">Harga</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger mr-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                        </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div> -->
    <!-- end bootstrap modal usulan -->

    <!-- modal -->
    <div class="modal fade" id="usulanLain-modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Rencana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="rencana2Form" name="rencana2Form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" id="rencana_id" name="rencana_id" value="{{ $rencanaId->id }}">
                        <input type="hidden" name="noparent_id" id="noparent_id">
                        <div class="form-group mb-2">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="#" disabled selected>-Pilih jika tidak ada Kode nya-</option>
                                <option value="detil">Detil</option>
                            </select>
                        </div>
                        <div class="form-group mb-2" id="uraian-group" style="display:none;">
                            <label for="uraian" class="col-sm-4 control-label">Uraian</label>
                            <div class="col-sm-12">
                                <textarea name="uraian" id="uraian" class="form-control" placeholder="Masukkan uraian" maxlength="255"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-4 control-label">Kode</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" maxlength="50" required="">
                                <input type="hidden" id="kode_komponen_id" name="kode_komponen_id">
                                <div id="kode-results" name="kode-results" class="dropdown-menu" style="display: none; position: absolute; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-4 control-label">Volume</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="volume" name="volume" placeholder="Masukkan volume" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-4 control-label">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-control">
                                <option disabled selected>-Pilih Satuan-</option>
                                @foreach ($satuan as $item )
                                <option value="{{$item->id}}">{{$item->satuan}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-4 control-label">Harga</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger mr-2" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<!-- end bootstrap model -->
<script type="text/javascript">
    // untuk form
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const kategoriSelect = document.getElementById('kategori');
        const uraianGroup = document.getElementById('uraian-group');
        const kodeInput = document.getElementById('kode');

        // Function to toggle kode input based on kategori selection
        function toggleKodeInput() {
            kodeInput.disabled = kategoriSelect.value === 'detil';
        }

        // Add event listener for kategori select
        kategoriSelect.addEventListener('change', function() {
            if (kategoriSelect.value === 'detil') {
                uraianGroup.style.display = 'block';
            } else {
                uraianGroup.style.display = 'none';
            }
            toggleKodeInput(); // Call the function to toggle kode input
        });

        // Call the function initially to set kode input state based on initial kategori value
        toggleKodeInput();
    });

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#usulan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.usulan')}}",
            columns: [{
                    data: 'number',
                    name: 'number',
                    className: 'text-center',
                    orderable: false,
                },
                {
                    data: 'allkode',
                    name: 'allkode',
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
                    data: 'total',
                    name: 'total',
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

        $('#kode').on('input', function() {
            let searchValue = $(this).val();
            if (searchValue.length > 0) {
                $.ajax({
                    url: '/unit/search/code',
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
            $('#kode').val(`${selectedKode} - ${selectedUraian}`);
            $('#kode_komponen_id').val(selectedId);
            $('#kode-results').hide();
        });

        // Hide results when clicking outside
        $(document).on('click', function(event) {
            if (!$(event.target).closest('#kode').length && !$(event.target).closest('#kode-results').length) {
                $('#kode-results').hide();
            }
        });
    });

    // untuk menambahkan TAHUN
    function tambahUsulan() {
        $('#rencanaForm').trigger("reset");
        $('#UsulanModal').html("Tambahkan Usulan");
        $('#usulan-modal').modal('show');
        $('#id').val('');

    }

    // untuk menambahkan detail usulan
    function tambahRencana() {
        $('#rencana2Form').trigger("reset");
        $('#usulanLain-modal').modal('show');
        $('#parent_id').val('');
        $('#id').val('');

        if ($('#kategori').val() === 'detil') {
            $('#kode').prop('disabled', true);
        } else {
            $('#kode').prop('disabled', false);
        }

        $('#usulanLain-modal').on('hidden.bs.modal', function() {
            // Mengaktifkan kembali input kode jika kategori bukan "detil"
            if ($('#kategori').val() !== 'detil') {
                $('#kode').prop('disabled', false);
            }
        });
    }

    // untuk menambahkan detail usulan yang sesuai dengan usulan pertama
    // let currentKode = '';
    // if (currentKode === '') {
    // Ambil nilai kode dari input jika pertama kali
    //     currentKode = $('#kode').val();
    // }
    // $('#kode').val(currentKode);

    function tambahRencanaLain(parentId) {
        $('#rencana2Form').trigger("reset");
        $('#noparent_id').val(parentId);
        $('#usulanLain-modal').modal('show');
    }

    function hapusUsulan(id) {
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
                    url: "{{ route('unit.hapus_usulan')}}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        var oTable = $('#usulan').DataTable();
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

    // menyimpan data rencana
    $('#rencanaForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_tahun')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#usulan-modal").modal('hide');
                var oTable = $('#usulan').DataTable();
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

    // Menyimpan detail rencana
    $('#rencana2Form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var id = $('#id').val();
        var url = id ? "/unit/update-usulan/" + id : "/unit/simpan-rencana2/";
        var method = id ? "POST" : "POST"; // Menggunakan POST untuk metode spoofing PUT atau PATCH
        if (id) {
            formData.append('_method', 'PUT'); // Menambahkan spoofing metode PUT jika ID ada
        }

        $.ajax({
            type: method,
            url: url,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#usulanLain-modal").modal('hide');
                var oTable = $('#usulan').DataTable();
                oTable.ajax.reload();
                $("#btn-simpan").html('Submit');
                $("#btn-simpan").attr("disabled", false);
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
