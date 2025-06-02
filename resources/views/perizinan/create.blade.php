@extends('layouts.app')
@push('styles')
    <style>
        .btn-radio {
            --bs-btn-active-color: black;
        }
    </style>
@endpush
@section('content')
    <div class="container pt-3 pb-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header border-0 rounded-top-4" style="background: linear-gradient(135deg, #018183f3);">
                <h4 class="mb-0 text-center fw-bold py-3 text-white">Penambahan Izin Santri</h4>
            </div>
            <div class="card-body px-5 py-4">
                <form action="{{ isset($perizinan) ? route('perizinan.update', $perizinan->id_izin) : route('perizinan.store') }}" method="POST" enctype="multipart/form-data">
                    @if(isset($perizinan))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="row">
                        <div class="col-md-6 pe-md-5">
                            <div class="mb-4">
                                <label for="id_santri" class="form-label fw-semibold text-black">ID Santri</label>
                                <input type="text" name="id_santri" id="id_santri" class="form-control border border-secondary shadow-sm py-2" oninput="setSantri()" value="{{ isset($perizinan) ? $perizinan->id_santri : '' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="nama_santri" class="form-label fw-semibold text-black">Nama Santri</label>
                                <input type="text" name="nama_santri" id="nama_santri" class="form-control border border-secondary shadow-sm py-2" disabled>
                            </div>
                            <div class="mb-4">
                                <label for="waktu" class="form-label fw-semibold text-black">Izin Pada Tanggal</label>
                                <input type="date" name="waktu" id="waktu" class="form-control border border-secondary shadow-sm" value="{{ isset($perizinan) ? $perizinan->waktu : '' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="jenis_izin" class="form-label fw-semibold text-black d-block">Jenis Izin</label>
                                <input type="radio" class="btn-check" name="jenis_izin" id="sakit" autocomplete="off" value="sakit" {{ isset($perizinan) ? ($perizinan->jenis_izin == 'sakit' ? 'checked' : '') : '' }}>
                                <label class="btn btn-outline-info btn-radio" for="sakit">Sakit</label>
                                <input type="radio" class="btn-check" name="jenis_izin" id="Izin" autocomplete="off" value="izin" {{ isset($perizinan) ? ($perizinan->jenis_izin == 'izin' ? 'checked' : '') : 'checked' }}>
                                <label class="btn btn-outline-primary btn-radio" for="Izin">Izin Lain</label>
                            </div>
                            <div class="mb-4">
                                <label for="keterangan" class="form-label fw-semibold text-black">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" class="form-control border border-secondary shadow-sm" rows="3" required>{{ isset($perizinan) ? $perizinan->keterangan : '' }}</textarea>
                            </div>
                            <div class="text-right mt-4">
                                <button type="reset" class="btn btn-danger px-4 py-2 rounded-3 shadow me-2">Reset</button>
                                <button type="submit" class="btn btn-success px-4 py-2 rounded-3 shadow">Selanjutnya</button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        var dataSantri = {!! isset($perizinan) ? json_encode($perizinan->toArray()) : 'null' !!};
        dataSantri != null ? setSantri() : '';
        function setSantri() {
            var dataNamaSantri = {!! json_encode($santri->toArray()) !!};
            for (let index = 0; index < dataNamaSantri.length; index++) {
                var id_santri = document.getElementById('id_santri').value;
                if (dataNamaSantri[index]['id_santri'] == id_santri) {
                    document.getElementById('nama_santri').value = dataNamaSantri[index]['nama'];
                    break;
                }
                document.getElementById('nama_santri').value = '';
            }
        }
    </script>
@endpush