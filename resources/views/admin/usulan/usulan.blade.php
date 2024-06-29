@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Rencana Kerja Anggaran</h5>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="row">
            <!-- filter -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-3">Filter</h5>
                        <div class="row">
                            <div class="col-lg-3 mb-2">
                                <!-- <label for="unit">Pilih Unit </label> -->
                                <select name="funit" id="funit" class="form-select">
                                    <option value="#" disabled selected>- Pilih Unit -</option>
                                    @if($unit->isEmpty())
                                    <option disabled>Tidak ada Unit</option>
                                    @else
                                    @foreach($unit as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <select name="fkategori" id="fkategori" class="form-select">
                                    <option value="#" disabled selected> - Pilih Kategori - </option>
                                    @if($kategoris->isEmpty())
                                    <option disabled>Tidak ada kategori</option>
                                    @else
                                    @foreach($kategoris as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-3 mb-2">
                                <select name="fkategori" id="ftahun" class="form-select">
                                    <option value="#" disabled selected> - Pilih Tahun - </option>
                                    @for ($year = 2020; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
                                        @endfor
                                </select>
                            </div>
                            <div class="col-lg-1 mb-1">
                                <button class="btn btn-dark" id="resetFilter">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end filter -->
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- <h5 class="card-title fw-semibold mb-3">Buka Usulan</h5> -->
                            <div class="col"></div>
                            <h5 class="card-title fw-semibold mb-3 text-center">Buka Rencana Awal</h5>
                            <a href="javascript:void(0)" onclick="buttonbukaRencana()" class="btn btn-dark"><i class="ti ti-plus"></i> Rencana Awal</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!-- card tabel rencana awal -->
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Rencana Awal</h5>
                    <div class="row">
                        <table class="table table-bordered" id="rencanaAwalTabel">
                            <thead>
                                <tr>
                                    <th>Unit</th>
                                    <th>Anggaran</th>
                                    <th>Tahun</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- card table detail renacana-->
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">Detail Rencana</h5>
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
                                    <!-- <th width="15%">Action</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- modal untuk menambahkan rencana untuk membuka usulan per unit nya -->
    <div class="modal fade" id="bukaRencana" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambahkan Awal Rencana</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="bukaRencanaForm" name="bukaRencanaForm" class="form-horizontal" method="POST" enctype="multipart/form-data" class="needs-validation">
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
                                @for ($year = 2020; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
                                    @endfor
                            </select>
                            @if ($errors->has('year'))
                            <span class="text-danger">{{$errors->first('year')}}</span>
                            @endif
                        </div>
                        <div class="form-group mb-2">
                            <label for="note">Anggaran</label>
                            <div class="col-sm-12">
                                <input type="text" name="anggaran" id="anggaran" class="form-control" placeholder="Masukkan Anggaran"></input>
                            </div>
                        </div>
                        <div class="col-sm-8 offset-sm-8"><br />
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary" id="btn-simpan1">Simpan</button>
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

        dataRencana();

        function dataRencana() {
            var funit = $('#funit').val();
            var fkategori = $('#fkategori').val();
            var ftahun = $('#ftahun').val();

            $('#rencanaTabel').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.tabelRencana') }}",
                    type: 'GET',
                    data: {
                        unit_id: funit,
                        kategori_id: fkategori,
                        tahun: ftahun,
                    },
                    dataSrc: function(json) {
                        console.log(json); // Log the data received from server
                        return json.data;
                    }
                },
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

            $('#rencanaAwalTabel').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.tabelRencanaAwal') }}",
                    type: 'GET',
                    data: {
                        unit_id: funit,
                        tahun: ftahun,
                    },
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
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                    }
                ]
            })
        }

        $('#funit').on('change', function() {
            dataRencana();
        });

        $('#fkategori').on('change', function() {
            dataRencana();
        });

        $('#ftahun').on('change', function() {
            dataRencana();
        });

        $('#resetFilter').click(function() {
            $('#funit').val("#").trigger('change');
            $('#fkategori').val("#").trigger('change');
            $('#ftahun').val("#").trigger('change');
            dataRencana();
        })

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    });

    // buka usulan per unit
    function buttonbukaRencana() {
        $('#bukaRencana').modal('show');
        $('#bukaRencanaForm').trigger('reset');
    }

    // submit rencana awal
    $('#bukaRencanaForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{route('admin.bukaRencana')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $('#bukaRencana').modal('hide');
                var oTable = $('#rencanaAwalTabel').DataTable();
                oTable.ajax.reload();
                $("#btn-simpan1").html('Submit');
                $("#btn-simpan1").attr("disabled", false);
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
                    title: 'eror',
                    text: data.error
                })
            },
        })
    });

    function showUsulan(id) {
        window.location.href = '{{ route("admin.show_rencana") }}' + '?id=' + id;
        console.log(id);
    }

    function editUsulan(id) {
        window.location.href = '{{ route("admin.edit_rencana") }}' + '?id=' + id;
        console.log(id);
    }

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
                    url: "{{ route('admin.hapus_RA')}}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        var oTable = $('#rencanaAwalTabel').DataTable();
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
