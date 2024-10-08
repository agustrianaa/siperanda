@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Lengkapi Rencana Unit</h5>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Rencana Awal</h3>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="editRencAwal" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama Unit</th>
                                    <th>Anggaran</th>
                                    <th>Tahun</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
        </div>
        <div class="col-auto">
            <a href="javascript:void(0)" onclick="KompletRencana()" class="btn btn-info"><i class="ti ti-plus"></i>Usulan</a>
        </div>
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title mb-3">Detail Rencana Unit</h3>
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabelUsulan" style="width:100%">
                            <input type="hidden" id="rencana_id" value="{{ $rencana->id }}">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Uraian</th>
                                    <th>Volume</th>
                                    <th>Satuan</th>
                                    <th>Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="6" style="text-align:right">Total:</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- data revisi -->
    <div class="row ">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-3">List Revision</h5>
                <div class="row mb-2">
                    <div class="form-group">
                        <select class="form-control" id="revision" name="revision">
                            <option value="" selected disabled>Pilih Revisi...</option>
                            @foreach ($dataRevisi as $item )
                            <option value="{{$item}}">Revisi {{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="last" style="width:100%">
                        <thead>
                            <tr>
                                <th width="3px">No</th>
                                <th>Kode</th>
                                <th width="30%">Uraian</th>
                                <th>Volume</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th width="15%">Jumlah</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align:right">Total:</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- modal untuk menambahkan usulan -->
    <div class="modal fade" id="lengkapiUsulan-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Rencana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="lengkapiUsulan-form" name="lengkapiUsulan-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="noparent_id" id="noparent_id">
                        <input type="hidden" name="created_by" id="created_bye" value="admin">
                        <div class="form-group mb-2">
                            <div class="form-group mb-2">
                                <label for="noparent_id">Parent</label>
                                <select name="noparent_id" id="noparent_id" class="form-select" required="Harus diisi">
                                    <option disabled selected>- Pilih Parent -</option>
                                    @if($unit->isEmpty())
                                    <option disabled>Tidak ada Parent</option>
                                    @else
                                    @foreach ($parent as $data )
                                    <option value="{{$data->detail_rencana_id }}">{{$data->kode_parent}} . {{$data->kode}} . {{$data->uraian}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
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
                                <input type="number" class="form-control" id="volume" name="volume" placeholder="Masukkan volume" maxlength="50" required="">
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
                                <input type="numbe" class="form-control" id="harga" name="harga" placeholder="Masukkan Harga" maxlength="50" required="">
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

    <!-- modal untul mengedit data rencana awal -->
    <div class="modal fade" id="editRencanaAwal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambahkan Awal Rencana</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="editRencanaAwalForm" name="editRencanaAwalForm" class="form-horizontal" method="POST" enctype="multipart/form-data" class="needs-validation">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group mb-2">
                            <label for="unit_id">Unit</label>
                            <select name="unit_id" id="unit_id" class="form-select" required="Harus diisi">
                                <option disabled selected>- Pilih Unit -</option>
                                @if($unit->isEmpty())
                                <option disabled>Tidak ada Unit</option>
                                @else
                                @foreach ($unit as $data )
                                <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="invalid-feedback">
                                Unit Harus Dipilih
                            </div>
                            <div class="valid-feedback">
                                Good Job!
                            </div>
                        </div>
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
                        <div class="form-group mb-2">
                            <label for="note">Anggaran</label>
                            <div class="col-sm-12">
                                <input type="number" name="anggaran" id="anggaran" class="form-control" placeholder="Masukkan Anggaran"></input>
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

    <!-- modal untuk menambahkan parent -->
    <div class="modal fade" id="editParent" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambahkan Awal Rencana</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="editParentForm" name="editParentForm" class="form-horizontal" method="POST" enctype="multipart/form-data" class="needs-validation">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group mb-2">
                            <label for="noparent_id">Parent</label>
                            <select name="noparent_id" id="noparent_id" class="form-select" required="Harus diisi">
                                <option disabled selected>- Pilih Parent -</option>
                                @if($unit->isEmpty())
                                <option disabled>Tidak ada Unit</option>
                                @else
                                @foreach ($parent as $data )
                                <option value="{{$data->detail_rencana_id }}">{{$data->kode_parent}} . {{$data->kode}} . {{$data->uraian}}</option>
                                @endforeach
                                @endif
                            </select>
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
    <!-- end modal -->
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var rencanaId = $('#rencana_id').val();
        $('#tabelUsulan').DataTable({
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('admin.tabeldetail') }}",
                type: 'GET',
                data: {
                    id: rencanaId
                },
                dataSrc: function(json) {
                    console.log(json); // Log the data received from server
                    return json.data;
                }
            },
            columns: [{
                    data: 'numbering',
                    name: 'numbering',
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row) {
                        return data ? data : '';
                    }
                },
                {
                    data: 'allkode',
                    name: 'allkode',
                    render: function(data, type, row) {
                        return data ? data : '';
                    },
                    orderable: false,
                },
                {
                    data: 'uraian',
                    name: 'uraian',
                    render: function(data, type, row) {
                        if (row.uraian_kode_komponen) {
                            return row.uraian_kode_komponen;
                        } else {
                            return row.uraian_rencana;
                        }
                    },
                    orderable: false,
                },
                {
                    data: 'volume',
                    name: 'volume',
                    className: "text-center"
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
                    className: 'text-center ',
                    searchable: false,
                    orderable: false,
                },
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

        $('#editRencAwal').DataTable({
            "dom": 't',
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "{{ route('admin.tabeleditRA') }}",
                type: 'GET',
                data: {
                    id: rencanaId
                },
                dataSrc: function(json) {
                    console.log(json); // Log the data received from server
                    return json.data;
                }
            },
            columns: [{
                    data: 'nama_unit',
                    name: 'nama_unit',
                },
                {
                    data: 'anggaran',
                    name: 'anggaran',
                },
                {
                    data: 'tahun',
                    name: 'tahun',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'action',
                    name: 'action',
                    className: 'text-center',
                    searchable: false,
                    orderable: false,
                },
            ]
        });
        dataRevisi();

        function dataRevisi() {
            var revision = $('#revision').val();
            var rencanaId = $('#rencana_id').val();
            console.log('Sending ID:', rencanaId);
            console.log('Sending Revision:', revision);
            $('#last').DataTable({
                "dom": 't',
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('admin.tabelRevisi')}}",
                    type: 'GET',
                    data: {
                        revision: revision,
                        id: rencanaId,
                    }
                },
                columns: [{
                        data: 'numbering',
                        name: 'numbering',
                        className: 'text-center',
                        orderable: false,
                    },
                    {
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
        }
        $('#revision').on('change', function() {
            dataRevisi();
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

        // untuk mencari kode
        $('#kode').on('input', function() {
            let searchValue = $(this).val();
            if (searchValue.length > 0) {
                $.ajax({
                    url: '/admin/search/code',
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

        // untuk form
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

    function KompletRencana() {
        var rencanaId = $('#rencana_id').val();
        $('#lengkapiUsulan-modal').modal('show');
        if ($('#kategori').val() === 'detil') {
            $('#kode').prop('disabled', true);
        } else {
            $('#kode').prop('disabled', false);
        }
    }

    // submit
    var rencanaId = $('#rencana_id').val();
    $('#lengkapiUsulan-form').off('submit').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('rencana_id', rencanaId);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_rencanaLengkap')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#lengkapiUsulan-modal").modal('hide');
                var oTable = $('#tabelUsulan').DataTable();
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

    function editRenc(id) {
        console.log(id);
        $.ajax({
            type: "GET",
            url: "{{ route('admin.edit_Lrencana')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#lengkapiUsulan-modal .modal-title').html("Edit Rencana");
                $('#lengkapiUsulan-modal').modal('show');
                $('#id').val(res.id);
                $('#kode').val(res.kode_uraian); // Mengisi input dengan gabungan kode dan uraian
                $('#kode_komponen_id').val(res.kode_komponen_id); // Isi input tersembunyi
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
            }
        });
    }

    function editParent(id) {
        console.log(id);
        $('#editParent').modal('show');

        $('#editParentForm').off('submit').on('submit', function(e) {
            e.preventDefault(); // Mencegah form submit default

            var formData = new FormData(this);
            formData.append('id', id); // Menambahkan ID ke form data

            $.ajax({
                type: "POST",
                url: "{{ route('admin.simpan_parent') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#editParent").modal('hide');
                    var oTable = $('#editRencAwal').DataTable();
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        });
    }

    function editRencAwal(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('admin.editRencAwal')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(res) {
                $('#editRencanaAwal .modal-title').html("Edit Rencana Awal");
                $('#editRencanaAwal').modal('show');
                $('#editRencanaAwal #id').val(res.id);
                $('#editRencanaAwal #unit_id').val(res.unit_id);
                $('#editRencanaAwal #anggaran').val(res.anggaran);
                $('#editRencanaAwal #tahun').val(res.tahun.substring(0, 4));
            }
        });
    }

    $('#editRencanaAwalForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_RA')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#editRencanaAwal").modal('hide');
                var oTable = $('#editRencAwal').DataTable();
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
        })
    });

    function hapusRenc(id) {
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
                    url: "{{ route('admin.hapus_usulan')}}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        var oTable = $('#tabelUsulan').DataTable();
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
