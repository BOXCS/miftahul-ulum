@extends('layouts.app')
@push('styles')
    <style>
        .permission-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .card-header-gradient {
            background: linear-gradient(135deg, #00b4db, #0083b0);
            position: relative;
            overflow: hidden;
        }
        
        .card-header-gradient::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        }
        
        .form-section {
            padding: 2rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0083b0;
            box-shadow: 0 0 0 3px rgba(0, 131, 176, 0.1);
        }
        
        .btn-radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }
        
        .btn-radio {
            flex: 1;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            text-align: center;
        }
        
        .btn-radio:hover {
            transform: translateY(-2px);
        }
        
        .btn-action {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-reset {
            background-color: #f8f9fa;
            color: #2d3748;
            border: 1px solid #e2e8f0;
        }
        
        .btn-reset:hover {
            background-color: #e2e8f0;
        }
        
        .btn-submit {
            background-color: #0083b0;
            border: none;
        }
        
        .btn-submit:hover {
            background-color: #006a8e;
        }
        
        @media (max-width: 768px) {
            .form-section {
                padding: 1.5rem;
            }
            
            .btn-radio-group {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card permission-card rounded-4">
                    <div class="card-header card-header-gradient rounded-top-4">
                        <h3 class="mb-0 text-center text-white fw-bold py-3">
                            <i class="fas fa-user-clock me-2"></i>Penambahan Izin Santri
                        </h3>
                    </div>
                    
                    <div class="card-body p-0">
                        <form action="{{ isset($perizinan) ? route('perizinan.update', $perizinan->id_izin) : route('perizinan.store') }}" 
                              method="POST" enctype="multipart/form-data">
                            @if (isset($perizinan))
                                @method('PUT')
                            @endif
                            @csrf
                            
                            <div class="form-section">
                                <div class="mb-4">
                                    <label for="id_santri" class="form-label">
                                        <i class="fas fa-search me-2"></i>Cari Santri
                                    </label>
                                    <select id="id_santri" name="id_santri" class="form-select" required>
                                        @if (isset($perizinan))
                                            <option value="{{ $perizinan->id_santri }}" selected>
                                                {{ $perizinan->santri->nama }}
                                            </option>
                                        @endif
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="waktu" class="form-label">
                                        <i class="far fa-calendar-alt me-2"></i>Izin Pada Tanggal
                                    </label>
                                    <input type="date" name="waktu" id="waktu" class="form-control"
                                           value="{{ isset($perizinan) ? $perizinan->waktu : '' }}" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-tag me-2"></i>Jenis Izin
                                    </label>
                                    <div class="btn-radio-group">
                                        <input type="radio" class="btn-check" name="jenis_izin" id="sakit" 
                                               value="sakit" {{ isset($perizinan) && $perizinan->jenis_izin == 'sakit' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info btn-radio" for="sakit">
                                            <i class="fas fa-thermometer-half me-2"></i>Sakit
                                        </label>
                                        
                                        <input type="radio" class="btn-check" name="jenis_izin" id="Izin" 
                                               value="izin" {{ isset($perizinan) ? ($perizinan->jenis_izin == 'izin' ? 'checked' : '') : 'checked' }}>
                                        <label class="btn btn-outline-primary btn-radio" for="Izin">
                                            <i class="fas fa-home me-2"></i>Izin Lain
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="keterangan" class="form-label">
                                        <i class="fas fa-align-left me-2"></i>Keterangan
                                    </label>
                                    <textarea name="keterangan" id="keterangan" class="form-control" rows="4" required
                                    >{{ isset($perizinan) ? $perizinan->keterangan : '' }}</textarea>
                                </div>
                                
                                <div class="d-flex justify-content-end mt-5 gap-3">
                                    <button type="reset" class="btn btn-action btn-reset">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-action btn-submit text-white">
                                        <i class="fas fa-arrow-right me-2"></i>Selanjutnya
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#id_santri').select2({
                placeholder: '-- Cari Santri --',
                minimumInputLength: 2,
                ajax: {
                    url: '{{ route("santri.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (santri) {
                                return {
                                    id: santri.id_santri,
                                    text: santri.id_santri + ' - ' + santri.nama
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush