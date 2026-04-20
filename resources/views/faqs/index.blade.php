@extends('layouts.app')

@section('title', 'Manajemen FAQ')
@section('breadcrumb', 'FAQ')

@section('content')

<div x-data="{
    modalOpen: false,
    editMode: false,
    form: { id: '', pertanyaan: '', jawaban: '', kategori: 'Umum', urutan: 1, is_active: true },
    faqs: @js($faqs),
    kategoris: @js($kategoris),
    expanded: null,

    openCreate() {
        this.editMode = false;
        this.form = { id: '', pertanyaan: '', jawaban: '', kategori: 'Umum', urutan: this.faqs.length + 1, is_active: true };
        this.modalOpen = true;
    },
    openEdit(faq) {
        this.editMode = true;
        this.form = { ...faq, is_active: !!faq.is_active };
        this.modalOpen = true;
    }
}">

<div class="page-header animate-fade-in">
    <div>
        <h1 class="page-title">Manajemen FAQ</h1>
        <p class="page-subtitle">Kelola pertanyaan yang sering ditanyakan</p>
    </div>
    <button @click="openCreate()" class="btn btn-primary flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah FAQ
    </button>
</div>

{{-- LIST --}}
<div class="grid grid-cols-1 gap-4">
    <template x-for="faq in faqs" :key="faq.id">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-slate-50" @click="expanded = expanded === faq.id ? null : faq.id">
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-slate-400" x-text="faq.urutan"></span>
                    <p class="font-semibold text-slate-800" x-text="faq.pertanyaan"></p>
                    <span class="badge badge-blue text-[10px]" x-text="faq.kategori"></span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click.stop="openEdit(faq)" class="btn btn-ghost btn-sm text-blue-500 font-bold">Edit</button>
                    <form :action="`/faqs/${faq.id}`" method="POST" @click.stop onsubmit="return confirm('Hapus FAQ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-sm text-red-500 font-bold">Hapus</button>
                    </form>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="transition-transform" :class="expanded === faq.id ? 'rotate-180' : ''"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
            </div>
            <div x-show="expanded === faq.id" class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-sm text-slate-600 leading-relaxed" x-text="faq.jawaban"></div>
        </div>
    </template>

    <div x-show="faqs.length === 0" class="py-20 text-center card bg-slate-50 border-dashed">
        <p class="text-slate-400 italic">Belum ada FAQ</p>
    </div>
</div>

{{-- MODAL --}}
<div class="modal-overlay" x-show="modalOpen" style="display:none;" @click.self="modalOpen = false">
    <div class="modal-box max-w-xl w-full mx-4">
        <div class="modal-header">
            <h3 class="font-semibold text-slate-800" x-text="editMode ? 'Edit FAQ' : 'Tambah FAQ'"></h3>
            <button @click="modalOpen = false">&times;</button>
        </div>
        <form :action="editMode ? `/faqs/${form.id}` : '{{ route('faqs.store') }}'" method="POST">
            @csrf
            <template x-if="editMode">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="modal-body space-y-4">
                <div>
                    <label class="form-label">Pertanyaan</label>
                    <input type="text" name="pertanyaan" class="form-control" x-model="form.pertanyaan" required>
                </div>
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" x-model="form.kategori" placeholder="Contoh: Pendaftaran, Umum, dll" required>
                </div>
                <div>
                    <label class="form-label">Jawaban</label>
                    <textarea name="jawaban" class="form-control" rows="5" x-model="form.jawaban" required></textarea>
                </div>
                <div>
                    <label class="form-label">Urutan</label>
                    <input type="number" name="urutan" class="form-control" x-model="form.urutan">
                </div>
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
