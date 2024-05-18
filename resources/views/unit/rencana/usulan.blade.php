@extends('template')
@section('content')

<div class="container-fluid">
    <div class="row">
        <h3 class="card-title fw-semibold mb-4">Usulan</h3>
        <div class="row">
            <div class="row mb-3">
                <div class="col"></div>
                <div class="col-auto">
                    <a class="btn btn-secondary m-1" onclick="tambahUsulan()" href="javascript:void(0)"><i class="ti ti-plus"></i> Usulan</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-success m-1" onclick="tambahRencana()" href="javascript:void(0)"><i class="ti ti-plus"></i> Rencana</a>
                </div>
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
<div class="modal fade" id="usulan-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Usulan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="rencanaForm" name="rencanaForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="rencana_id" id="rencana_id">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option disabled selected>-Pilih Tahun-</option>
                            @for ($year = 2020; $year <= date('Y'); $year++) <option value="{{$year}}">{{$year}}</option>
                                @endfor
                        </select>
                        @if ($errors->has('year'))
                        <span class="text-danger">{{$errors->first('year')}}</span>
                        @endif
                    </div>
                    <div class="col-sm-offset-2 col-sm-10"><br />
                        <btype="submit" class="btn btn-primary" id="btn-save">Save changes</btype=>
                    </div>
                </form>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
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
                    <input type="hidden" id="rencana_id" name="rencana_id" value="1">
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Kode</label>
                        <div class="col-sm-12">
                        <input type="text" class="form-control" id="kode" name="kode" placeholder="Masukkan kode" maxlength="50" required="">
        <input type="hidden" id="kode_komponen_id" name="kode_komponen_id">
        <div id="kode-results" name="kode-results" class="dropdown-menu" style="display: none; position: absolute; width: 100%;"></div>
                        </div>
                    </div>
                    <!-- <div class="form-group" id="search-results" style="display:none;">
                        <label for="kode-results" class="col-sm-4 control-label">Hasil Pencarian</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="kode-results" name="kode-results"> -->
                    <!-- Hasil pencarian akan dimasukkan di sini oleh jQuery -->
                    <!-- </select>
                        </div>
                    </div> -->
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
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
</div>
<!-- end bootstrap model -->
<script type="text/javascript">
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
                },
                {
                    data: 'jumlah',
                    name: 'jumlah',
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
        let kode = $(this).val();
        if (kode.length > 0) {
            $.ajax({
                url: '/unit/search/code',
                method: 'GET',
                data: {
                    kode: kode
                },
                success: function(data) {
                    console.log('Data received:', data); // Log response dari server
                    let results = $('#kode-results');
                    results.empty();
                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                            results.append(`<div class="dropdown-item" data-id="${item.id}" data-kode="${item.kode}" data-uraian="${item.uraian}">${item.kode} - ${item.uraian}</div>`);
                        });
                        results.show();
                    } else {
                        results.hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error); // Log error jika terjadi
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

    function tambahUsulan() {
        $('#rencanaForm').trigger("reset");
        $('#UsulanModal').html("Tambahkan Usulan");
        $('#usulan-modal').modal('show');
        $('#id').val('');
    }

    function tambahRencana() {
        $('#rencana2Form').trigger("reset");
        $('#usulanLain-modal').modal('show');
        $('#id').val('');
    }

    function tambahRencanaLain() {
        $('#usulanLain-modal').modal('show');
    }

    $('#rencanaForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_rencana')}}",
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
    $('#rencana2Form').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: "{{ route('unit.simpan_rencana2')}}",
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
        })
    });
</script>
@endsection
