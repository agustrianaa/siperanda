@extends('template')
@section('page-title')
<h4 class="fw-semibold align-text-center">Usulan</h4>
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
            <div class="row mb-2">
                <div class="col">
                    @if (isset($rencanaId) && $rencanaId)
                    @if ($rencanaId->status == 'revisi')
                    <div class="btn btn-danger status-btn">Revisi</div>
                    @elseif ($rencanaId->status == 'approved')
                    <div class="btn btn-success status-btn">Disetujui</div>
                    @elseif ($rencanaId->status == 'rejected')
                    <div class="btn btn-dark status-btn" disabled>Tidak Disetujui</div>
                    @elseif ($rencanaId->status == 'draft')
                    <div class="btn btn-secondary status-btn">Draft</div>
                    @endif
                    @else
                    <div class="btn btn-warning status-btn">Status Tidak Tersedia</div>
                    @endif
                </div>
                <div class="col-auto">
                    <a class="btn btn-success m-1" onclick="tambahRencana()" href="javascript:void(0)"><i class="ti ti-plus"></i> Rencana</a>
                </div>
            </div>
            <!-- @if($total > $rencanaId->anggaran) -->
            <div class="row">
                <div id="alert-warning" class="alert alert-warning d-none">
                    Anggaran melebihi Pagu
                </div>
            </div>
            <!-- @endif -->
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title fw-semibold mb-3">Detail Rencana Anggaran {{$rencanaId->tahun}}</h5>
                    </div>
                    <div class="col-auto">
                        <h5 class="card-title fw-semibold mb-3">Pagu : Rp. {{number_format($rencanaId->anggaran, 0, ',', '.')}}</h5>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="usulan" style="width:100%">
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
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right">Total:</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="row {{ $is_rev ? '' : 'd-none'}}" id="last-div">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3">Usulan Lama</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="last">
                        <thead>
                            <tr>
                                <!-- <th width="3px">No</th> -->
                                <th>Kode</th>
                                <th width="30%">Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="usulanLain-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rencana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="rencana2Form" name="rencana2Form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" id="rencana_id" name="rencana_id" value="{{ $rencanaId->id }}">
                        <input type="hidden" name="noparent_id" id="noparent_id">
                        <input type="hidden" name="created_by" id="created_bye" value="unit">
                        <div class="form-group mb-2">
                            <label for="kategori">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="#" disabled selected>-Pilih jika tidak ada Kode nya-</option>
                                <option value="detil">Detil</option>
                                <option value="#">Memiliki Kode</option>
                            </select>
                            <div class="invalid-feedback">Pilih satu</div>
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
                                <div class="invalid-feedback">Harus diisi</div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name" class="col-sm-4 control-label">Satuan</label>
                            <select name="satuan_id" id="satuan_id" class="form-select" required>
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

    <!-- modal untuk keterangan dan status -->

    <div class="modal fade" id="showKet" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Status</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <form>
                            <label for="ket">Catatan</label>
                            <textarea id="ket" class="form-control" rows="4" disabled>{{ $noteRev->note ?? 'Tidak ada keterangan.' }}</textarea>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
            if (kategoriSelect.value === 'detil') {
                kodeInput.value = ''; // Clear the kode input value
                kodeInput.disabled = true;
                uraianGroup.style.display = 'block';
                $('#uraian').prop('disabled', false).show();
            } else {
                kodeInput.disabled = false;
                uraianGroup.style.display = 'none';
                $('#uraian').prop('disabled', true).hide();
            }
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

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#usulan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.new')}}",
            columns: [{
                    data: 'number',
                    name: 'number',
                    className: 'text-center',
                    orderable: false,
                },
                {
                    data: 'allkode',
                    name: 'allkode',
                    className: 'text-center',
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
                    name: 'volume',
                    className: 'text-center',
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
            ],
            footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // Total over all pages
                total = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Total over this page
                pageTotal = api
                    .column(6, {
                        page: 'current'
                    })
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(6).footer()).html(
                    'Rp ' + formatNumber(pageTotal, 0)
                );
            }
        });

        $('#last').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('unit.last')}}",
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
                                let kodeParent = item.kode_parent ? item.kode_parent : ''; // Jika tidak ada kode_parent, berikan string kosong
                                results.append(`
                            <div class="dropdown-item" data-id="${item.id}" data-kode="${item.kode}" data-uraian="${item.uraian || ''}">
                                ${item.kode}.${kodeParent} - ${item.uraian || 'Uraian Kosong'}
                            </div>
                        `);
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

        $('.status-btn').on('click', function() {
            $('#showKet').modal('show');
        });

    });

    // untuk menambahkan detail usulan
    function tambahRencana() {
        $('#rencana2Form').trigger("reset");
        var rencanaId = $('#rencana_id').val(); // Ambil ID rencana dari hidden input
        $.ajax({
            type: "GET",
            url: "{{ route('unit.checkStatus') }}", // Sesuaikan dengan URL endpoint Anda untuk memeriksa status
            data: {
                id: rencanaId
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'approved') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Rencana sudah disetujui dan tidak bisa ditambahkan lagi'
                    });
                } else {
                    // Tampilkan modal jika status bukan 'approved'
                    $('#usulanLain-modal .modal-title').html("Tambahkan Rencana");
                    $('#rencana2Form').trigger("reset");
                    $('#usulanLain-modal').modal('show');
                    $('#parent_id').val('');
                    $('#id').val('');

                    if ($('#kategori').val() === 'detil') {
                        $('#kode').prop('disabled', true);
                    } else {
                        $('#kode').prop('disabled', false);
                    }
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memeriksa status rencana'
                });
            }
        });
    }

    function tambahRencanaLain(parentId) {
        var rencanaId = $('#rencana_id').val();
        $.ajax({
            type: "GET",
            url: "{{ route('unit.checkStatus') }}", // Sesuaikan dengan URL endpoint Anda untuk memeriksa status
            data: {
                id: rencanaId
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'approved') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Rencana sudah disetujui dan tidak bisa ditambahkan lagi'
                    });
                } else {
                    // Tampilkan modal jika status bukan 'approved'
                    $('#usulanLain-modal .modal-title').html("Tambahkan Rencana");
                    $('#rencana2Form').trigger("reset");
                    $('#noparent_id').val(parentId);
                    $('#usulanLain-modal').modal('show');
                    $('#parent_id').val('');
                    $('#id').val('');

                    if ($('#kategori').val() === 'detil') {
                        $('#kode').prop('disabled', true);
                    } else {
                        $('#kode').prop('disabled', false);
                    }
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memeriksa status rencana'
                });
            }
        });
    }

    function hapusUsulan(id) {
        console.log(id);
        Swal.fire({
            title: 'Hapus Data?',
            text: "Anda yakin ingin menghapus data rencana ini?",
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
                        if (res.exceeds_budget) {
                            $('#alert-warning').removeClass('d-none').html('Anggaran melebihi Pagu');
                            localStorage.setItem('alert-warning', 'visible');
                        } else {
                            $('#alert-warning').addClass('d-none');
                            localStorage.removeItem('alert-warning');
                        }
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.success
                        });
                    },
                    error: function(xhr, status, error) {
                        var res = xhr.responseJSON;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Terjadi kesalahan saat menghapus data'
                        });
                    }

                });
            }
        });
    }

     // Cek status dari localStorage saat halaman dimuat
     if (localStorage.getItem('alert-warning') === 'visible') {
        $('#alert-warning').removeClass('d-none').html('Anggaran melebihi Pagu');
    } else {
        $('#alert-warning').addClass('d-none');
    }
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
                console.log(data);
                $("#usulanLain-modal").modal('hide');
                var usulanTable = $('#usulan').DataTable();
                usulanTable.ajax.reload();
                var lastTable = $('#last').DataTable();
                lastTable.ajax.reload();
                $("#btn-simpan").html('Submit');
                $("#btn-simpan").attr("disabled", false);
                if (data.status === 'revisi') {
                    $('#last-div').removeClass('d-none');
                    var lastTable = $('#last').DataTable();
                    lastTable.ajax.reload();
                };
                if (data.exceeds_budget) {
                    $('#alert-warning').removeClass('d-none').html('Anggaran melebihi Pagu');
                    localStorage.setItem('alert-warning', 'visible');
                } else {
                    $('#alert-warning').addClass('d-none');
                    localStorage.removeItem('alert-warning');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.success
                    });
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    });

    function editUsulan(id) {

        $.ajax({
            type: "POST",
            url: "{{ route('unit.edit_usulan')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                if (res.is_revised3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Sudah melebihi batas revisi'
                    });
                } else if (res.status === 'approved') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Usulan sudah disetujui dan tidak bisa diubah'
                    });
                } else {
                    $('#usulanLain-modal .modal-title').html("Edit Usulan");
                    // $('#usulanLain-modal').modal('show');
                    $('#id').val(res.id);
                    $('#kode').val(res.kode_uraian); // Mengisi input dengan gabungan kode dan uraian
                    $('#kode_komponen_id').val(res.kode_komponen_id); // Isi input tersembunyi
                    $('#uraian').val(res.uraian);
                    $('#volume').val(res.volume);
                    $('#satuan_id').val(res.satuan_id); // Pilih satuan yang sesuai di dropdown
                    $('#harga').val(res.harga);

                    if (res.kode_komponen_id === null) {
                        $('#kategori').val('detil').change();
                        $('#uraian').val(res.uraian).prop('disabled', false).show();
                        $('#kode').prop('disabled', true);
                        $('#uraian-group').show();
                    } else {
                        $('#kategori').val('#').change();
                        $('#kode').prop('disabled', false);
                        $('#uraian-group').hide();
                    }
                    $('#usulanLain-modal').modal('show');
                }
            },
        });

        $('#usulanLain-modal').on('hidden.bs.modal', function() {
            // Mengaktifkan kembali input kode jika kategori bukan "detil"
            if ($('#kategori').val() !== 'detil') {
                $('#kode').prop('disabled', false);
            }
            // Reset form modal
            $('#usulanLain-modal form').trigger("reset");
        });

    }
</script>
@endsection
