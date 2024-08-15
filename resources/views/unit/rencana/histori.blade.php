@extends('template')
@section('page-title')
<h4 class="fw-semibold">Histori</h4>
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
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataHistori" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Anggaran</th>
                                <th width="15%">Ket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        dataRencana();

        function dataRencana() {
            var fkategori = $('#fkategori').val();
            var ftahun = $('#ftahun').val();
            $('#dataHistori').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{route('unit.histori')}}",
                    type: 'GET',
                    data: {
                        kategori_id: fkategori,
                        tahun: ftahun,
                    }
                },
                columns: [{
                        data: 'tahun',
                        name: 'tahun',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    },
                    {
                        data: 'anggaran',
                        name: 'anggaran',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    },
                    {
                        data: 'ket',
                        name: 'ket',
                        className: 'text-center',
                        render: function(data, type, row) {
                            return data ? data : '';
                        }
                    }
                ]
            });
        }

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

    });

    function showHistori(idRencana){
        window.location.href = '{{ route("unit.show_histori") }}' + '?id=' + idRencana;
    }
</script>

@endsection
