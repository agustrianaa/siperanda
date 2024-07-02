@extends('template')
@section('page-title')
<h5 class="fw-semibold align-text-center">Monitoring</h5>
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
                    <table class="table table-bordered" id="monitoringfromAdmin" style="width:100%">
                        <thead>
                            <tr>
                                <!-- <th width="5px">No</th> -->
                                <th>Kode</th>
                                <th>Program/Kegiatan/KRO/RO/Komponen/dsb</th>
                                <th>Jumlah</th>
                                <th width="10%" class="text-center">RPD</th>
                                <th width="10%" class="text-center">Realisasi</th>
                                <th width="12%">Action</th>
                                <th>Ket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- <p class="mb-0">This is a sample page </p> -->
            </div>
        </div>
    </div>
    <!-- modal untuk menambahkan Realisasi -->
    <div class="modal fade" id="modalRealisasi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Realisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="realisasiForm" name="realisasiForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="ket">Bulan Te - Realisasi</label>
                            <select name="bulan_realisasi" id="bulan_realisasi" class="form-select" required>
                                <option value="">-Pilih Bulan</option>
                                <option value="Januari">Januari</option>
                                <option value="Februari">Februari</option>
                                <option value="Maret">Maret</option>
                                <option value="April">April</option>
                                <option value="Mei">Mei</option>
                                <option value="Juni">Juni</option>
                                <option value="Juli">Juli</option>
                                <option value="Agustus">Agustus</option>
                                <option value="September">September</option>
                                <option value="Oktober">Oktober</option>
                                <option value="November">November</option>
                                <option value="Desember">Desember</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="jumlah">Jumlah</label>
                            <div class="col-sm-12">
                                <input type="number" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah" class="form-control" required>
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
    <!-- end modal Realisasi -->

    <!-- Edit Realisasi Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="editForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Realisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Bulan Realisasi</th>
                                    <th class="text-center">Anggaran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data realisasi akan diisi melalui JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal edit -->

    <!-- modal untuk hapus realisasi menggunakan checkbox -->
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Hapus Realisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="row"> -->
                    <table class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" id="selectAll"></th>
                                <th class="text-center">Bulan Realisasi</th>
                                <th class="text-center">Anggaran</th>
                            </tr>
                        </thead>
                        <tbody id="realisasiTableBody">
                            <!-- dari js -->
                        </tbody>
                    </table>
                    <!-- </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="deleteSelectedRealisasi">Hapus Terpilih</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal hapus -->

    <!-- modal untuk show -->
    <div class="modal fade" id="showModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Detail Realisasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- <div class="row"> -->
                    <table class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Bulan RPD</th>
                                <th class="text-center">Anggaran</th>
                                <th class="text-center">Bulan Realisasi</th>
                                <th class="text-center">Anggaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- dari js -->
                        </tbody>
                    </table>
                    <!-- </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal show -->
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

            $('#monitoringfromAdmin').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('admin.monitoring')}}",
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
                        data: 'jumlahUsulan',
                        name: 'jumlahUsulan',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return formatNumber(data);
                        }
                    },
                    {
                        data: 'bulan_rpd',
                        name: 'bulan_rpd',
                    },
                    {
                        data: 'bulan_realisasi',
                        name: 'bulan_realisasi',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                    },
                    {
                        data: 'ket',
                        name: 'ket',
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

    var id;

    function tambahRealisasi(_id) {
        id = _id;
        $('#modalRealisasi').modal('show');
        $('#realisasiForm').trigger('reset');
    }

    // BUTTON UNTUK SIMPAN REALISASI
    $('#realisasiForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('detail_rencana_id', id);
        $.ajax({
            type: "POST",
            url: "{{ route('admin.simpan_realisasi')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#modalRealisasi").modal('hide');
                $("#btn-save").html('Submit');
                var oTable = $('#monitoringfromAdmin').DataTable();
                oTable.ajax.reload(null, false);
                $("#btn-save").attr("disabled", false);
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
                        title: 'error',
                        text: data.error,
                    });
            }
        });
    });

    // UNTUK MENGEDIT REALISASI
    function editRealisasi(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('admin.getRealisasi') }}",
            data: { id: id },
            success: function(data) {
                $('#editModal tbody').empty();
                const realisasiData = data.realisasi || [];

                const bulanOptions = `
                    <option value="">-Pilih Bulan-</option>
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                `;

                if (realisasiData.length > 0) {
                    realisasiData.forEach(function(item) {
                        const selectedBulan = item.bulan_realisasi || '';

                        $('#editModal tbody').append(
                            '<tr>' +
                            '<td>' +
                                '<div class="form-group mb-3">' +
                                    '<label for="bulan_realisasi">Bulan Realisasi</label>' +
                                    '<select name="bulan_realisasi[]" class="form-select" required>' +
                                        bulanOptions +
                                    '</select>' +
                                '</div>' +
                            '</td>' +
                            '<td>' +
                                '<div class="form-group mb-3">' +
                                    '<label for="jumlah">Jumlah</label>' +
                                    '<input type="number" name="jumlah[]" value="' + (item.jumlah || '') + '" class="form-control">' +
                                '</div>' +
                            '</td>' +
                            '<input type="hidden" name="id[]" value="' + item.id + '">' +

                            '</tr>'
                        );

                        $('select[name="bulan_realisasi[]"]').last().val(selectedBulan);
                    });
                } else {
                    $('#editModal tbody').append(
                        '<tr>' +
                        '<td colspan="2" class="text-center">Tidak ada data realisasi</td>' +
                        '</tr>'
                    );
                }

                $('#editModal').modal('show');
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data realisasi');
            }
        });
    }
    // button update yang di edit
    $('#editForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{ route('admin.updateRealisasi') }}",
            data: $(this).serialize(),
            success: function(response) {
                $('#editModal').modal('hide');
                var oTable = $('#monitoringfromAdmin').DataTable();
                    oTable.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data realisasi berhasil di update'
                    });
                },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengupdate data realisasi');
            }
        });
    });

    function hapusRealisasi(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('admin.getRealisasi')}}",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                $('#realisasiTableBody').empty();
                const realisasiData = data.realisasi;
                if (realisasiData.length > 0) {
                    realisasiData.forEach(function(item) {
                        $('#realisasiTableBody').append(
                            '<tr>' +
                            '<td class="text-center"><input type="checkbox" class="realisasiCheckbox" value="' + item.id + '"></td>' +
                            '<td class="text-center">' + item.bulan_realisasi + '</td>' +
                            '<td class="text-center">' + item.jumlah + '</td>' +
                            '</tr>'
                        );
                    });
                } else {
                    $('#realisasiTableBody').append(
                        '<tr>' +
                        '<td colspan="3" class="text-center">Tidak ada data realisasi</td>' +
                        '</tr>'
                    );
                }
                $('#deleteModal').modal('show');
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data realisasi');
            }
        });
    }

    $('#selectAll').on('change', function() {
        $('.realisasiCheckbox').prop('checked', $(this).prop('checked'));
    });

    // untuk menghapus data sesuai dengan select di checkbox
    $('#deleteSelectedRealisasi').on('click', function() {
        var selectedIds = [];
        $('.realisasiCheckbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length > 0) {
            $.ajax({
                type: "POST",
                url: "{{ route('admin.deleteRealisasi') }}",
                data: {
                    ids: selectedIds,
                },
                success: function(response) {

                    $('#deleteModal').modal('hide');
                    var oTable = $('#monitoringfromAdmin').DataTable();
                    oTable.ajax.reload(null, false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Data realisasi berhasil dihapus'
                    });
                },
                error: function(error) {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'error',
                        text: 'Terjadi kesalahan saat menghapus data realisasi'
                    });
                }
            });
        } else {
            alert('Pilih setidaknya satu realisasi untuk dihapus');
        }
    });

    function show(id) {
        console.log(id);
        $.ajax({
            type: "GET",
            url: "{{ route('admin.getRealisasi',) }}",
            data: {
                id: id
            },
            success: function(data) {
                // Kosongkan tabel sebelum mengisi data baru
                $('#showModal tbody').empty();

                const rpdData = data.rpd;
                const realisasiData = data.realisasi;
                const maxLength = Math.max(rpdData.length, realisasiData.length);

                let totalRpdAnggaran = 0;
                let totalRealisasiAnggaran = 0;

                for (let i = 0; i < maxLength; i++) {
                    const rpdItem = rpdData[i] || {};
                    const realisasiItem = realisasiData[i] || {};

                    const rpdAnggaran = parseFloat(rpdItem.jumlah || 0);
                    const realisasiAnggaran = parseFloat(realisasiItem.jumlah || 0);

                    totalRpdAnggaran += rpdAnggaran;
                    totalRealisasiAnggaran += realisasiAnggaran;

                    $('#showModal tbody').append(
                        '<tr>' +
                        '<td class="text-center">' + (rpdItem.bulan_rpd || '-') + '</td>' +
                        '<td class="text-center">' + (rpdAnggaran.toLocaleString('id-ID') || '-') + '</td>' +
                        '<td class="text-center">' + (realisasiItem.bulan_realisasi || '-') + '</td>' +
                        '<td class="text-center">' + (realisasiAnggaran.toLocaleString('id-ID') || '-') + '</td>' +
                        '</tr>'
                    );
                }

                if (maxLength === 0) {
                    $('#showModal tbody').append(
                        '<tr>' +
                        '<td colspan="4" class="text-center">Tidak ada data</td>' +
                        '</tr>'
                    );
                }

                // Tambahkan baris untuk total anggaran
                $('#showModal tbody').append(
                    '<tr>' +
                    '<td class="text-center fw-semibold">Total</td>' +
                    '<td class="text-center font-weight-bold">' + totalRpdAnggaran.toLocaleString('id-ID') + '</td>' +
                    '<td class="text-center fw-semibold">Total</td>' +
                    '<td class="text-center font-weight-bold">' + totalRealisasiAnggaran.toLocaleString('id-ID') + '</td>' +
                    '</tr>'
                );
                $('#showModal').modal('show');
            },
            error: function(error) {
                console.error(error);
                alert('Terjadi kesalahan saat mengambil data realisasi');
            }
        });
    }
</script>
@endsection
