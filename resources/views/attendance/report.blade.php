@extends('layouts.app')

@section('title', 'Laporan Presensi')
@section('breadcrumb', 'Laporan Presensi')

@push('styles')
<style>
    :root {
        --teal: #0d9488;
        --teal-dark: #0f766e;
        --sh-teal: 0 8px 28px rgba(13,148,136,.22);
        --sh-teal-sm: 0 4px 14px rgba(13,148,136,.18);
    }

    .rp-wrap {
        max-width: 1100px;
        margin: 0 auto;
        padding: 24px 20px 40px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* ── Page header ───────────────────────────────────── */
    .rp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .rp-header-left { display: flex; align-items: center; gap: 14px; }

    .rp-header-ico {
        width: 48px; height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f0fdfa, #ccfbf1);
        display: flex; align-items: center; justify-content: center;
        box-shadow: var(--sh-teal-sm);
        flex-shrink: 0;
    }

    .rp-title { font-size: 1.15rem; font-weight: 800; color: #1e293b; margin: 0 0 2px; letter-spacing: -.02em; }
    .rp-sub   { font-size: .76rem; color: #94a3b8; margin: 0; }

    .btn-teal {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 12px;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: #fff; font-size: .82rem; font-weight: 700;
        border: none; cursor: pointer; text-decoration: none;
        box-shadow: var(--sh-teal-sm); transition: all .25s; font-family: inherit;
    }
    .btn-teal:hover { background: linear-gradient(135deg, #0f766e, #115e59); transform: translateY(-2px); box-shadow: var(--sh-teal); color: #fff; }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 16px; border-radius: 12px;
        background: #fff; color: #475569; font-size: .82rem; font-weight: 700;
        border: 1.5px solid #e2e8f0; cursor: pointer; text-decoration: none;
        transition: all .2s; font-family: inherit;
    }
    .btn-outline:hover { background: #f0fdfa; border-color: #0d9488; color: #0d9488; }

    /* ── Card ──────────────────────────────────────────── */
    .rp-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf3;
        box-shadow: 0 2px 10px rgba(15,23,42,.055);
        overflow: hidden;
    }

    .rp-card-head {
        display: flex; align-items: center; gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .rp-card-head-ico {
        width: 32px; height: 32px; border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .rp-card-head h2 { font-size: .88rem; font-weight: 700; color: #1e293b; margin: 0 0 1px; }
    .rp-card-head p  { font-size: .7rem; color: #94a3b8; margin: 0; }

    /* ── Summary stats ─────────────────────────────────── */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .sum-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15,23,42,.06);
        padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
        position: relative; overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .sum-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(15,23,42,.1); }

    .sum-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 16px 16px 0 0;
    }
    .sum-card.tc-teal::before  { background: linear-gradient(90deg, #0d9488, #5eead4); }
    .sum-card.tc-blue::before  { background: linear-gradient(90deg, #2563eb, #60a5fa); }
    .sum-card.tc-green::before { background: linear-gradient(90deg, #16a34a, #4ade80); }

    .sum-ico {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }

    .sum-val  { font-size: 1.6rem; font-weight: 800; color: #1e293b; line-height: 1; margin-bottom: 3px; letter-spacing: -.03em; }
    .sum-lbl  { font-size: .72rem; color: #64748b; font-weight: 600; }

    /* ── Chart area ────────────────────────────────────── */
    .chart-box { padding: 20px; }

    canvas { width: 100% !important; }

    /* ── Top alpha table ───────────────────────────────── */
    .alpha-table { width: 100%; border-collapse: collapse; }

    .alpha-table th {
        padding: 10px 16px;
        text-align: left;
        font-size: .72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }

    .alpha-table td {
        padding: 12px 16px;
        font-size: .82rem;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }

    .alpha-table tr:last-child td { border-bottom: none; }
    .alpha-table tbody tr:hover td { background: #f8fafc; }

    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px;
        font-size: .72rem; font-weight: 800;
    }

    .rank-1 { background: #fef3c7; color: #d97706; }
    .rank-2 { background: #f1f5f9; color: #475569; }
    .rank-3 { background: #fef3c7; color: #92400e; }
    .rank-n { background: #f1f5f9; color: #94a3b8; }

    .alpha-bar-wrap { display: flex; align-items: center; gap: 8px; }
    .alpha-bar-bg   { flex: 1; height: 6px; border-radius: 3px; background: #f1f5f9; overflow: hidden; }
    .alpha-bar-fill { height: 100%; border-radius: 3px; background: linear-gradient(90deg, #ef4444, #fca5a5); transition: width .6s ease; }
    .alpha-count    { font-size: .75rem; font-weight: 700; color: #ef4444; min-width: 28px; text-align: right; }

    /* ── Empty state ───────────────────────────────────── */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: .83rem;
    }

    /* ── Responsive ────────────────────────────────────── */
    @media (max-width: 640px) {
        .summary-grid { grid-template-columns: 1fr; }
        .rp-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="rp-wrap">

    {{-- Page Header --}}
    <div class="rp-header">
        <div class="rp-header-left">
            <div class="rp-header-ico">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                     stroke="#0d9488" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <div>
                <h1 class="rp-title">Laporan Presensi</h1>
                <p class="rp-sub">Rekap 6 bulan terakhir · {{ now()->locale('id')->isoFormat('MMMM Y') }}</p>
            </div>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('attendance.index') }}" class="btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <a href="{{ route('attendance.export.excel') }}" class="btn-outline" style="border-color:#16a34a;color:#16a34a;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/>
                </svg>
                Export Excel
            </a>
            <a href="{{ route('attendance.export.pdf') }}" class="btn-teal">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="summary-grid">
        <div class="sum-card tc-teal">
            <div class="sum-ico" style="background:#f0fdfa;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="#0d9488" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="sum-val">{{ $summary['total_santri'] }}</div>
                <div class="sum-lbl">Total Santri Aktif</div>
            </div>
        </div>

        <div class="sum-card tc-blue">
            <div class="sum-ico" style="background:#eff6ff;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <div class="sum-val">{{ $summary['total_hari_efektif'] }}</div>
                <div class="sum-lbl">Hari Efektif Tercatat</div>
            </div>
        </div>

        <div class="sum-card tc-green">
            <div class="sum-ico" style="background:#f0fdf4;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                </svg>
            </div>
            <div>
                <div class="sum-val">{{ $summary['rata_kehadiran'] }}<span style="font-size:.9rem;font-weight:600;">%</span></div>
                <div class="sum-lbl">Rata-rata Kehadiran</div>
            </div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="rp-card">
        <div class="rp-card-head">
            <div class="rp-card-head-ico" style="background:#f0fdfa;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
            </div>
            <div>
                <h2>Tren Kehadiran 6 Bulan Terakhir</h2>
                <p>Hadir, Izin, Sakit, Alpha per bulan</p>
            </div>
        </div>
        <div class="chart-box">
            <canvas id="trendChart" height="80"></canvas>
        </div>
    </div>

    {{-- Top Alpha --}}
    <div class="rp-card">
        <div class="rp-card-head">
            <div class="rp-card-head-ico" style="background:#fef2f2;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <h2>Santri Alpha Terbanyak</h2>
                <p>5 santri dengan ketidakhadiran tanpa keterangan tertinggi</p>
            </div>
        </div>

        @if($topAlpha->isEmpty())
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                     stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 10px;display:block;">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Tidak ada data alpha tercatat.
            </div>
        @else
            @php $maxAlpha = $topAlpha->first()->alpha_count ?? 1; @endphp
            <table class="alpha-table">
                <thead>
                    <tr>
                        <th style="width:48px;">#</th>
                        <th>Nama Santri</th>
                        <th>Kelas</th>
                        <th>Jumlah Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topAlpha as $i => $item)
                        @php
                            $rankClass = match($i) { 0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-n' };
                            $pct = $maxAlpha > 0 ? ($item->alpha_count / $maxAlpha) * 100 : 0;
                        @endphp
                        <tr>
                            <td><span class="rank-badge {{ $rankClass }}">{{ $i + 1 }}</span></td>
                            <td style="font-weight:600;color:#1e293b;">{{ $item->student?->name ?? '-' }}</td>
                            <td style="color:#64748b;">{{ $item->student?->class ?? '-' }}</td>
                            <td>
                                <div class="alpha-bar-wrap">
                                    <div class="alpha-bar-bg">
                                        <div class="alpha-bar-fill" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="alpha-count">{{ $item->alpha_count }}x</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Monthly Detail Table --}}
    <div class="rp-card">
        <div class="rp-card-head">
            <div class="rp-card-head-ico" style="background:#f0fdfa;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="#0d9488" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
            <div>
                <h2>Detail Per Bulan</h2>
                <p>Rekap lengkap status kehadiran 6 bulan terakhir</p>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table class="alpha-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th style="color:#0d9488;">Hadir</th>
                        <th style="color:#d97706;">Izin</th>
                        <th style="color:#2563eb;">Sakit</th>
                        <th style="color:#ef4444;">Alpha</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyStats as $row)
                        @php $total = $row['hadir'] + $row['izin'] + $row['sakit'] + $row['alpha']; @endphp
                        <tr>
                            <td style="font-weight:600;color:#1e293b;">{{ $row['bulan'] }}</td>
                            <td><span style="color:#0d9488;font-weight:700;">{{ $row['hadir'] }}</span></td>
                            <td><span style="color:#d97706;font-weight:700;">{{ $row['izin'] }}</span></td>
                            <td><span style="color:#2563eb;font-weight:700;">{{ $row['sakit'] }}</span></td>
                            <td><span style="color:#ef4444;font-weight:700;">{{ $row['alpha'] }}</span></td>
                            <td style="color:#64748b;">{{ $total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    const labels = @json(array_column($monthlyStats, 'bulan'));
    const hadir  = @json(array_column($monthlyStats, 'hadir'));
    const izin   = @json(array_column($monthlyStats, 'izin'));
    const sakit  = @json(array_column($monthlyStats, 'sakit'));
    const alpha  = @json(array_column($monthlyStats, 'alpha'));

    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadir,
                    backgroundColor: 'rgba(13,148,136,.75)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Izin',
                    data: izin,
                    backgroundColor: 'rgba(217,119,6,.65)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Sakit',
                    data: sakit,
                    backgroundColor: 'rgba(37,99,235,.6)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Alpha',
                    data: alpha,
                    backgroundColor: 'rgba(239,68,68,.65)',
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12, weight: '600' }, padding: 16, usePointStyle: true, pointStyleWidth: 10 }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12, weight: '700' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 10,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11 }, color: '#94a3b8', precision: 0 }
                }
            },
            interaction: { mode: 'index', intersect: false }
        }
    });
})();
</script>
@endpush
