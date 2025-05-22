@extends('layouts.app')

@section('title', 'Laporan Kehadiran')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h1>Laporan Kehadiran</h1>
            {{-- <p class="text-muted">Pantau kehadiran berdasarkan filter bulan, tahun, dan waktu shalat.</p> --}}
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Semua</option>
                    @foreach (range(1, 12) as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $b)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" name="tahun" id="tahun" value="{{ request('tahun') }}" class="form-control"
                    placeholder="Contoh: 2025">
            </div>
            <div class="col-md-3">
                <label for="santri" class="form-label">Santri</label>
                <select name="santri" id="santri" class="form-select">
                    <option value="">Semua Santri</option>
                    @foreach ($listSantri as $santri)
                        <option value="{{ $santri->id_santri }}"
                            {{ request('santri') == $santri->id_santri ? 'selected' : '' }}>
                            {{ $santri->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="shalat" class="form-label">Waktu Shalat</label>
                <select name="shalat" id="shalat" class="form-select">
                    <option value="">Semua</option>
                    @foreach (['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'] as $s)
                        <option value="{{ $s }}" {{ request('shalat') == $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">🎯 Rata-Rata Kehadiran</h6>
                        <h3 class="fw-bold">{{ $rataRataKehadiran }}%</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">🏅 Kehadiran Tertinggi</h6>
                        <h5 class="fw-bold mb-0">{{ $kehadiranTertinggi['nama'] }}</h5>
                        <small class="text-success">{{ $kehadiranTertinggi['persen'] }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="text-muted">⚠️ Kehadiran Terendah</h6>
                        <h5 class="fw-bold mb-0">{{ $kehadiranTerendah['nama'] }}</h5>
                        <small class="text-danger">{{ $kehadiranTerendah['persen'] }}%</small>
                    </div>
                </div>
            </div>
        </div>

        @if ($semuaKehadiran->count())
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="tabelKehadiran">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Santri</th>
                                    <th>Tanggal</th>
                                    <th>Waktu Shalat</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($semuaKehadiran as $r)
                                    <tr>
                                        <td>{{ $r->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($r->waktu)->translatedFormat('d M Y') }}</td>
                                        <td>{{ $r->waktu_shalat }}</td>
                                        <td>{{ $r->jam_masuk ?? '-' }}</td>
                                        <td>{{ $r->jam_keluar ?? '-' }}</td>
                                        <td>
                                            @if (!$r->jam_masuk)
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                            @else
                                                @php
                                                    $waktuBatas = match ($r->waktu_shalat) {
                                                        'Subuh' => now()->setTime(5, 0),
                                                        'Dzuhur' => now()->setTime(12, 0),
                                                        'Ashar' => now()->setTime(15, 0),
                                                        'Maghrib' => now()->setTime(18, 0),
                                                        'Isya' => now()->setTime(19, 30),
                                                        default => now()->setTime(0, 0),
                                                    };
                                                    $waktuBatas = $waktuBatas->addMinutes(5);
                                                    $status = \Carbon\Carbon::parse($r->jam_masuk)->lte($waktuBatas)
                                                        ? 'Hadir'
                                                        : 'Terlambat';
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $status === 'Hadir' ? 'success' : 'warning' }}">{{ $status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center">Tidak ada data kehadiran yang ditemukan.</div>
        @endif


    </div>
@endsection


@push('scripts')
    <!-- DataTables CSS dan JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>
    <!-- DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            $('#tabelKehadiran').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '📥 Export Excel',
                        className: 'btn btn-success mb-2'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '📄 Export PDF',
                        className: 'btn btn-danger mb-2',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        customize: function(doc) {
                            // Improve PDF styling
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 11;
                            doc.styles.title.fontSize = 14;

                            // Add header
                            doc.content.splice(0, 0, {
                                text: 'Laporan Kehadiran Santri',
                                style: 'header',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });

                            // Add filter information
                            const bulan = $('#bulan').find('option:selected').text();
                            const tahun = $('#tahun').val();
                            const santri = $('#santri').find('option:selected').text();
                            const shalat = $('#shalat').find('option:selected').text();

                            let filterText = 'Filter: ';
                            if (bulan !== 'Semua') filterText += `Bulan ${bulan}, `;
                            if (tahun) filterText += `Tahun ${tahun}, `;
                            if (santri !== 'Semua Santri') filterText += `Santri ${santri}, `;
                            if (shalat !== 'Semua') filterText += `Shalat ${shalat}`;

                            // Remove trailing comma if exists
                            filterText = filterText.replace(/, $/, '');

                            doc.content.splice(1, 0, {
                                text: filterText,
                                style: 'subheader',
                                alignment: 'center',
                                margin: [0, 0, 0, 10]
                            });

                            // Add summary information
                            doc.content.splice(2, 0, {
                                columns: [{
                                        text: `Rata-rata Kehadiran: ${$('#tabelKehadiran').data('rata-rata')}%`,
                                        width: '*'
                                    },
                                    {
                                        text: `Tertinggi: ${$('#tabelKehadiran').data('tertinggi-nama')} (${$('#tabelKehadiran').data('tertinggi-persen')}%)`,
                                        width: '*'
                                    },
                                    {
                                        text: `Terendah: ${$('#tabelKehadiran').data('terendah-nama')} (${$('#tabelKehadiran').data('terendah-persen')}%)`,
                                        width: '*'
                                    }
                                ],
                                margin: [0, 0, 0, 10]
                            });

                            // Add footer with page numbers
                            doc['footer'] = function(currentPage, pageCount) {
                                return {
                                    text: `Halaman ${currentPage.toString()} dari ${pageCount}`,
                                    alignment: 'center',
                                    fontSize: 9,
                                    margin: [0, 10, 0, 0]
                                };
                            };

                            // Define styles
                            doc.styles = {
                                header: {
                                    fontSize: 16,
                                    bold: true,
                                    color: '#333333'
                                },
                                subheader: {
                                    fontSize: 10,
                                    bold: false,
                                    color: '#666666'
                                },
                                tableHeader: {
                                    bold: true,
                                    fontSize: 11,
                                    color: '#333333',
                                    fillColor: '#f5f5f5'
                                }
                            };

                            // Table styling
                            // Table styling (safely apply if table is found)
                            const tableContent = doc.content.find(item => item.table);

                            if (tableContent && tableContent.table) {
                                tableContent.table.widths = ['*', 'auto', 'auto', 'auto', 'auto',
                                    'auto'
                                ];

                                tableContent.table.body[0].forEach(function(cell) {
                                    if (typeof cell === 'object') {
                                        cell.fillColor = '#f5f5f5';
                                        cell.bold = true;
                                    }
                                });
                            }


                            // Add current date to the document
                            const now = new Date();
                            const dateString = now.toLocaleDateString('id-ID', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });

                            doc.content.splice(0, 0, {
                                text: `Dicetak pada: ${dateString}`,
                                alignment: 'right',
                                fontSize: 8,
                                color: '#999999',
                                margin: [0, 0, 0, 10]
                            });
                        }
                    }
                ],
                initComplete: function() {
                    // Store the summary data in the table element for PDF export
                    $('#tabelKehadiran').data({
                        'rata-rata': '{{ $rataRataKehadiran }}',
                        'tertinggi-nama': '{{ $kehadiranTertinggi['nama'] }}',
                        'tertinggi-persen': '{{ $kehadiranTertinggi['persen'] }}',
                        'terendah-nama': '{{ $kehadiranTerendah['nama'] }}',
                        'terendah-persen': '{{ $kehadiranTerendah['persen'] }}'
                    });
                }
            });
        });
    </script>
@endpush
