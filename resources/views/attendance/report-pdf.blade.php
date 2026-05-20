<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }

    .header {
        background: #0d9488;
        color: #fff;
        padding: 18px 24px;
        border-radius: 0 0 8px 8px;
        margin-bottom: 20px;
    }
    .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 3px; }
    .header p  { font-size: 10px; opacity: .8; }

    .summary-row { display: flex; gap: 12px; margin-bottom: 20px; }
    .sum-box {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 14px;
        background: #f8fafc;
    }
    .sum-val { font-size: 22px; font-weight: bold; color: #0d9488; }
    .sum-lbl { font-size: 9px; color: #64748b; margin-top: 2px; }

    .section-title {
        font-size: 11px;
        font-weight: bold;
        color: #0d9488;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 2px solid #ccfbf1;
    }

    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    thead tr { background: #0d9488; color: #fff; }
    thead th { padding: 8px 10px; text-align: left; font-size: 10px; font-weight: bold; }
    thead th.center { text-align: center; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
    tbody td.center { text-align: center; }
    tbody tr:nth-child(even) td { background: #f8fafc; }
    tbody tr:last-child td { border-bottom: none; }

    .badge-hadir  { color: #0d9488; font-weight: bold; }
    .badge-izin   { color: #d97706; font-weight: bold; }
    .badge-sakit  { color: #2563eb; font-weight: bold; }
    .badge-alpha  { color: #dc2626; font-weight: bold; }

    .alpha-head thead tr { background: #dc2626; }

    .footer {
        margin-top: 24px;
        padding-top: 10px;
        border-top: 1px solid #e2e8f0;
        font-size: 9px;
        color: #94a3b8;
        text-align: center;
    }

    /* dompdf tidak support flexbox, pakai table untuk layout */
    .sum-table { width: 100%; border-collapse: separate; border-spacing: 8px; margin-bottom: 20px; }
    .sum-table td { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px; background: #f8fafc; width: 33%; }
</style>
</head>
<body>

<div class="header">
    <h1>Laporan Presensi Santri</h1>
    <p>Pondok Pesantren Miftahul Ulum &nbsp;·&nbsp; Dicetak: {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }}</p>
</div>

{{-- Summary --}}
<p class="section-title">Ringkasan</p>
<table class="sum-table">
    <tr>
        <td>
            <div class="sum-val">{{ $summary['total_santri'] }}</div>
            <div class="sum-lbl">Total Santri Aktif</div>
        </td>
        <td>
            <div class="sum-val">{{ $summary['total_hari_efektif'] }}</div>
            <div class="sum-lbl">Hari Efektif Tercatat</div>
        </td>
        <td>
            <div class="sum-val">{{ $summary['rata_kehadiran'] }}%</div>
            <div class="sum-lbl">Rata-rata Kehadiran</div>
        </td>
    </tr>
</table>

{{-- Tren Bulanan --}}
<p class="section-title">Tren Kehadiran 6 Bulan Terakhir</p>
<table>
    <thead>
        <tr>
            <th>Bulan</th>
            <th class="center">Hadir</th>
            <th class="center">Izin</th>
            <th class="center">Sakit</th>
            <th class="center">Alpha</th>
            <th class="center">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthlyStats as $row)
        @php $total = $row['hadir'] + $row['izin'] + $row['sakit'] + $row['alpha']; @endphp
        <tr>
            <td><strong>{{ $row['bulan'] }}</strong></td>
            <td class="center badge-hadir">{{ $row['hadir'] }}</td>
            <td class="center badge-izin">{{ $row['izin'] }}</td>
            <td class="center badge-sakit">{{ $row['sakit'] }}</td>
            <td class="center badge-alpha">{{ $row['alpha'] }}</td>
            <td class="center">{{ $total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Top Alpha --}}
<p class="section-title">Santri Alpha Terbanyak</p>
@if($topAlpha->isEmpty())
    <p style="color:#94a3b8;font-size:10px;margin-bottom:20px;">Tidak ada data alpha tercatat.</p>
@else
<table class="alpha-head">
    <thead>
        <tr>
            <th style="width:40px;">#</th>
            <th>Nama Santri</th>
            <th>Kelas</th>
            <th class="center">Jumlah Alpha</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topAlpha as $i => $item)
        <tr>
            <td class="center"><strong>{{ $i + 1 }}</strong></td>
            <td><strong>{{ $item->student?->name ?? '-' }}</strong></td>
            <td>{{ $item->student?->class ?? '-' }}</td>
            <td class="center badge-alpha">{{ $item->alpha_count }}x</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    Dokumen ini digenerate otomatis oleh Sistem Monitoring Santri &nbsp;·&nbsp; {{ now()->format('Y') }} Pondok Pesantren Miftahul Ulum
</div>

</body>
</html>
