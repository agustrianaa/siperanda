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
                                @for ($year = 2020; $year <= 9999; $year++) <option value="{{$year}}">{{$year}}</option>
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
                <h5 class="card-title fw-semibold mb-4">Unit</h5>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataAnggaran" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Unit</th>
                                <th>Pagu</th>
                                <th>Realisasi</th>
                                <th>Sisa Anggaran/Unit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
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
            $('#dataAnggaran').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('admin.dataAnggaran')}}",
                    type: 'GET',
                    data: {
                        unit_id: funit,
                        tahun: ftahun,
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun',
                        className: 'text-center',
                    },
                    {
                        data: 'unit',
                        name: 'unit',
                        className: 'text-center',
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        className: 'text-center',
                    },
                    {
                        data: 'jumlahRealisasi',
                        name: 'jumlahRealisasi',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? new Intl.NumberFormat('id-ID').format(data) : '0';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'sisaAnggaran',
                        name: 'sisaAnggaran',
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return data ? new Intl.NumberFormat('id-ID').format(data) : '0';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                    },
                ],
                language: {
                    emptyTable: "Belum ada data di setujui" // Ganti teks di sini
                }
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

    function show(id) {
        window.location.href = '{{ route("admin.show_monitoring") }}' + '?id=' + id;
        console.log(id);
    }
</script>
@endsection
