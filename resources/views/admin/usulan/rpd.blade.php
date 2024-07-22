@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Rencana Penarikan Dana</h5>
@endsection
@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- filter -->
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold">Filter</h5>
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
    </div>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabelRPD" style="width:100%">
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

            dataRencana();

            function dataRencana() {
                var funit = $('#funit').val();
                var fkategori = $('#fkategori').val();
                var ftahun = $('#ftahun').val();

                $('#tabelRPD').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: "{{route('admin.realisasi')}}",
                        type: 'GET',
                        data: {
                            unit_id: funit,
                            kategori_id: fkategori,
                            tahun: ftahun,
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
                            data: 'total_rpd',
                            name: 'total_rpd',
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
