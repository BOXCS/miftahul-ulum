@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Chat dengan Orang Tua</h3>
    <div id="chat-box" style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
        <!-- pesan-pesan akan ditampilkan di sini -->
    </div>

    <form id="chat-form">
        <input type="hidden" id="id_staf" value="{{ $id_staf }}">
        <input type="hidden" id="id_ortu" value="{{ $id_ortu }}">
        <input type="hidden" id="pengirim" value="staf">
        <div class="mt-2 d-flex">
            <input type="text" class="form-control" id="pesan" placeholder="Ketik pesan..." required>
            <button type="submit" class="btn btn-primary ms-2">Kirim</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script type="module">
    import Echo from 'laravel-echo';
    window.Pusher = Pusher;

    const echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ env("VITE_PUSHER_APP_KEY") }}',
        cluster: '{{ env("VITE_PUSHER_APP_CLUSTER") }}',
        forceTLS: true
    });

    const idStaf = document.getElementById('id_staf').value;
    const idOrtu = document.getElementById('id_ortu').value;
    const chatBox = document.getElementById('chat-box');

    // Ambil pesan awal
    fetch(`/api/chat/${idStaf}/${idOrtu}`)
        .then(res => res.json())
        .then(messages => {
            messages.forEach(m => {
                const el = document.createElement('div');
                el.textContent = `${m.pengirim}: ${m.pesan}`;
                chatBox.appendChild(el);
            });
        });

    // Submit form
    document.getElementById('chat-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const pesan = document.getElementById('pesan').value;
        const pengirim = document.getElementById('pengirim').value;

        fetch('/api/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id_staf: idStaf,
                id_ortu: idOrtu,
                pesan: pesan,
                pengirim: pengirim
            })
        });

        document.getElementById('pesan').value = '';
    });

    // Listen to channel
    echo.private(`chat.${idStaf}.${idOrtu}`)
        .listen('MessageSent', (e) => {
            const el = document.createElement('div');
            el.textContent = `${e.pengirim}: ${e.pesan}`;
            chatBox.appendChild(el);
            chatBox.scrollTop = chatBox.scrollHeight;
        });
</script>
@endsection
