@extends('layouts.app')

@section('title', 'Manajemen Pengumuman')
@section('breadcrumb', 'Pengumuman')

@section('content')

<div x-data="{
    modalOpen: false,
    editMode: false,
    form: { id: '', judul: '', kategori: 'umum', konten: '', publish: true },
    announcements: @js($announcements),
    stats: @js($stats),

    openCreate() {
        this.editMode = false;
        this.form = { id: '', judul: '', kategori: 'umum', konten: '', publish: true };
        this.modalOpen = true;
    },
    openEdit(ann) {
        this.editMode = true;
        this.form = { id: ann.id, judul: ann.judul, kategori: ann.kategori, konten: ann.konten, publish: ann.is_published };
        this.modalOpen = true;
    }
}">

<div class="page-header animate-fade-in">
    <div>
        <h1 class="page-title">Manajemen Pengumuman</h1>
        <p class="page-subtitle">Kelola pengumuman untuk santri dan wali</p>
    </div>
    <button @click="openCreate()" class="btn btn-primary flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Buat Pengumuman
    </button>
</div>

{{-- STATS --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card blue">
        <p class="text-xs font-bold text-slate-400 uppercase">Total</p>
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
    </div>
    <div class="stat-card green">
        <p class="text-xs font-bold text-slate-400 uppercase">Aktif</p>
        <p class="text-2xl font-bold">{{ $stats['published'] }}</p>
    </div>
    <div class="stat-card amber">
        <p class="text-xs font-bold text-slate-400 uppercase">Draft</p>
        <p class="text-2xl font-bold">{{ $stats['draft'] }}</p>
    </div>
    <div class="stat-card red">
        <p class="text-xs font-bold text-slate-400 uppercase">Darurat</p>
        <p class="text-2xl font-bold">{{ $stats['darurat'] }}</p>
    </div>
</div>

{{-- GRID --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <template x-for="ann in announcements" :key="ann.id">
        <div class="card p-5 flex flex-col gap-3 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full" :class="{
                'bg-blue-500': ann.kategori === 'umum',
                'bg-green-500': ann.kategori === 'kegiatan',
                'bg-purple-500': ann.kategori === 'akademik',
                'bg-red-500': ann.kategori === 'darurat'
            }"></div>

            <div class="flex items-center justify-between">
                <span class="badge" :class="{
                    'badge-blue': ann.kategori === 'umum',
                    'badge-green': ann.kategori === 'kegiatan',
                    'badge-purple': ann.kategori === 'akademik',
                    'badge-red': ann.kategori === 'darurat'
                }" x-text="ann.kategori"></span>
                <span class="text-xs text-slate-400" x-text="ann.published_at || 'Belum terbit'"></span>
            </div>

            <h3 class="font-bold text-slate-800" x-text="ann.judul"></h3>
            <p class="text-sm text-slate-600 line-clamp-3" x-text="ann.konten"></p>

            <div class="mt-auto flex items-center justify-between pt-3 border-t border-slate-50">
                <span class="badge" :class="ann.is_published ? 'badge-green' : 'badge-gray'" x-text="ann.is_published ? 'Aktif' : 'Draft'"></span>
                <div class="flex items-center gap-1">
                    <button @click="openEdit(ann)" class="btn btn-ghost btn-sm text-blue-500">Edit</button>
                    <form :action="`/announcements/${ann.id}`" method="POST" onsubmit="return confirm('Hapus pengumuman?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm text-red-500">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <div x-show="announcements.length === 0" class="col-span-full py-20 text-center card bg-slate-50 border-dashed">
        <p class="text-slate-400 italic">Belum ada pengumuman</p>
    </div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" x-show="modalOpen" style="display:none;" @click.self="modalOpen = false">
    <div class="modal-box max-w-xl w-full mx-4">
        <div class="modal-header">
            <h3 class="font-semibold text-slate-800" x-text="editMode ? 'Edit Pengumuman' : 'Buat Pengumuman'"></h3>
            <button @click="modalOpen = false">&times;</button>
        </div>
        <form :action="editMode ? `/announcements/${form.id}` : '{{ route('announcements.store') }}'" method="POST">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="modal-body space-y-4">
                <div>
                    <label class="form-label">Judul</label>
                    <input type="text" name="judul" class="form-control" x-model="form.judul" required>
                </div>
                <div>
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" x-model="form.kategori" required>
                        <option value="umum">Umum</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="akademik">Akademik</option>
                        <option value="darurat">Darurat</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Konten</label>
                    <textarea name="konten" class="form-control" rows="5" x-model="form.konten" required></textarea>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="publish" x-model="form.publish" value="1">
                    <span class="text-sm font-medium text-slate-700">Publikasikan langsung</span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" @click="modalOpen = false" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
