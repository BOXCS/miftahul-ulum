@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ═══════════════════════════════════════════════════ --}}
{{-- PAGE HEADER                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="page-header">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="page-title animate-fade-in">Dashboard</h1>
            <p class="page-subtitle animate-fade-in stagger-1">
                Selamat datang, <span class="font-semibold" style="color: #16a34a;">Admin!</span>
                Hari ini {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </p>
        </div>
        <div class="flex items-center gap-2 animate-fade-in stagger-2">
            <span class="badge badge-green flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Sistem Aktif
            </span>
        </div>
    </div>
</div>

@if($jadwalSholat)
{{-- ═══════════════════════════════════════════════════ --}}
{{-- JADWAL SHOLAT — NEW SECTION                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="card mb-6 overflow-hidden animate-fade-in stagger-1" style="border-left: 4px solid #16a34a;">
    <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M2 12h20"/></svg>
            <h2 class="text-sm font-bold text-slate-800">Jadwal Waktu Sholat — Jakarta & Sekitarnya</h2>
        </div>
        <span class="text-xs font-medium text-slate-500">{{ $jadwalSholat['tanggal'] }}</span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 divide-x divide-slate-100">
        @php
            $times = [
                ['name' => 'Subuh', 'time' => $jadwalSholat['subuh'], 'icon' => 'sun'],
                ['name' => 'Terbit', 'time' => $jadwalSholat['terbit'], 'icon' => 'sunrise'],
                ['name' => 'Dzuhur', 'time' => $jadwalSholat['dzuhur'], 'icon' => 'sun'],
                ['name' => 'Ashar', 'time' => $jadwalSholat['ashar'], 'icon' => 'cloud-sun'],
                ['name' => 'Maghrib', 'time' => $jadwalSholat['maghrib'], 'icon' => 'sunset'],
                ['name' => 'Isya', 'time' => $jadwalSholat['isya'], 'icon' => 'moon'],
            ];
        @endphp
        @foreach($times as $t)
        <div class="px-6 py-4 flex flex-col items-center justify-center gap-1 hover:bg-slate-50 transition-colors">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $t['name'] }}</span>
            <span class="text-lg font-black text-slate-700">{{ $t['time'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══════════════════════════════════════════════════ --}}
{{-- STAT CARDS — 6 COLUMN GRID                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

    {{-- 1. Total Santri (green) --}}
    <div class="stat-card green animate-fade-in stagger-1 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Total Santri</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['total_santri'] }}</p>
        </div>
        <div class="stat-icon green flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>

    {{-- 2. Hadir Hari Ini (blue) --}}
    <div class="stat-card blue animate-fade-in stagger-2 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Hadir Hari Ini</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['hadir_hari_ini'] }}</p>
        </div>
        <div class="stat-icon blue flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
    </div>

    {{-- 3. Izin / Sakit (amber) --}}
    <div class="stat-card amber animate-fade-in stagger-3 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Izin / Sakit</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['izin_hari_ini'] }}</p>
        </div>
        <div class="stat-icon amber flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="10" y1="15" x2="14" y2="15"/></svg>
        </div>
    </div>

    {{-- 4. Alpha (red) --}}
    <div class="stat-card red animate-fade-in stagger-4 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Alpha</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['alpha_hari_ini'] }}</p>
        </div>
        <div class="stat-icon red flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
    </div>

    {{-- 5. Wali Santri (purple) --}}
    <div class="stat-card purple animate-fade-in stagger-5 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Wali Santri</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['total_ortu'] }}</p>
        </div>
        <div class="stat-icon purple flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
    </div>

    {{-- 6. Pengumuman Aktif (teal) --}}
    <div class="stat-card teal animate-fade-in stagger-6 xl:col-span-1">
        <div class="flex flex-col flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2"
               style="color: #94a3b8; letter-spacing: 0.08em;">Pengumuman</p>
            <p class="text-3xl font-bold mb-1" style="color: #1e293b;">{{ $stats['pengumuman_aktif'] }}</p>
        </div>
        <div class="stat-icon teal flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14"/><path d="M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
        </div>
    </div>

</div>

{{-- Aktivitas & Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4">

    {{-- Recent Activity --}}
    <div class="card lg:col-span-3 animate-fade-in stagger-3">
        <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-bold" style="color: #1e293b;">Aktivitas Terbaru</h2>
            </div>
        </div>

        <ul class="divide-y divide-slate-50">
            @forelse($recent_activities as $activity)
            <li class="flex items-start gap-4 px-6 py-4 hover:bg-slate-50/60 transition-colors">
                <span class="mt-1.5 flex-shrink-0 w-2.5 h-2.5 rounded-full"
                      style="background: {{ $activity['color'] == 'green' ? '#16a34a' : ($activity['color'] == 'red' ? '#dc2626' : ($activity['color'] == 'blue' ? '#2563eb' : '#f59e0b')) }};"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium" style="color: #1e293b;">{{ $activity['text'] }}</p>
                </div>
                <span class="text-xs flex-shrink-0 font-medium" style="color: #cbd5e1;">{{ $activity['time'] }}</span>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-slate-400 text-sm italic">Belum ada aktivitas terbaru</li>
            @endforelse
        </ul>
    </div>

    {{-- Quick Actions --}}
    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('students.create') }}" class="card p-5 flex flex-col items-center justify-center gap-3 text-center cursor-pointer hover:scale-[1.02] transition-all no-underline">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #dcfce7; color: #16a34a;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="12" y1="13" x2="12" y2="19"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
                </div>
                <p class="text-xs font-bold" style="color: #1e293b;">Tambah Santri</p>
            </a>
            <a href="{{ route('attendance.index') }}" class="card p-5 flex flex-col items-center justify-center gap-3 text-center cursor-pointer hover:scale-[1.02] transition-all no-underline">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: #dbeafe; color: #2563eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><polyline points="9 16 11 18 15 14"/></svg>
                </div>
                <p class="text-xs font-bold" style="color: #1e293b;">Input Absensi</p>
            </a>
        </div>
    </div>

</div>

@endsection
