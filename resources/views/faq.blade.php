@extends('layouts.app')

@section('content')
<style>
    .tab-nav button {
        border: none;
        background-color: transparent;
        padding: 10px 20px;
        font-weight: bold;
        color: #0f4c75;
        border-bottom: 3px solid transparent;
    }

    .tab-nav button.active {
        border-bottom: 3px solid #0f4c75;
    }

    .tab-content {
        display: none;
        padding-top: 20px;
    }

    .tab-content.active {
        display: block;
    }

    .upload-box {
        border: 2px dashed #ccc;
        padding: 20px;
        text-align: center;
        color: #aaa;
        border-radius: 10px;
    }

    .upload-box:hover {
        background-color: #f9f9f9;
    }
</style>

<div class="container mt-4">
    <h2 class="mb-4">Pengumuman & FAQ</h2>

    <div class="tab-nav mb-3">
        <button class="active" onclick="switchTab('pengumuman')">Pengumuman</button>
        <button onclick="switchTab('faq')">FAQ</button>
    </div>

    <!-- PENGUMUMAN -->
    <div id="pengumuman" class="tab-content active">
        <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Judul Pengumuman</label>
                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul..." required>
            </div>

            <div class="mb-3">
                <label>Isi Pengumuman</label>
                <textarea name="isi" class="form-control" rows="3" placeholder="Masukkan isi pengumuman..." required></textarea>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    <option value="akademik">Akademik</option>
                    <option value="administrasi">Administrasi</option>
                    <option value="kegiatan">Kegiatan</option>
                </select>
            </div>

            <div class="row">
                <div class="col">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control" required>
                </div>
                <div class="col">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control" required>
                </div>
            </div>

            <div class="my-3 upload-box">
                <input type="file" name="foto" class="form-control-file">
                <p class="mt-2">Click to upload or drag and drop (max. 800x400px)</p>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>

    <!-- FAQ -->
    <div id="faq" class="tab-content">
        <div class="row">
            <div class="col-md-5">
                <h5>5/5 Pertanyaan</h5>
                <ul class="list-group">
                    @foreach ($faqs as $faq)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $faq->question }}</strong><br>
                                <small>{{ $faq->answer }}</small>
                            </div>
                            <button onclick="showQuestionForm('{{ $faq->id }}', '{{ $faq->question }}', '{{ $faq->answer }}', '{{ $faq->category }}')" class="btn btn-sm btn-danger">✕</button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-7">
                <form action="" method="POST" id="faqForm">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Pertanyaan</label>
                        <input type="text" name="question" id="question" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Jawaban</label>
                        <textarea name="answer" id="answer" class="form-control" rows="4" placeholder="Tulis jawaban di sini..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <input type="text" name="category" id="category" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary mt-2">Simpan Jawaban</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-nav button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

        document.querySelector(`.tab-nav button[onclick="switchTab('${tabId}')"]`).classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }

    function showQuestionForm(id, question, answer, category) {
        document.getElementById('question').value = question;
        document.getElementById('answer').value = answer;
        document.getElementById('category').value = category;
        document.getElementById('faqForm').action = '/faq/' + id;
    }
</script>
@endsection
