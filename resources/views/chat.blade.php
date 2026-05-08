@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

    <style>
        /* Bubble chat staf (kanan) */
        .bg-teal-600 {
            background-color: #0d6efd !important;
            /* Bootstrap primary biru */
        }

        /* Bubble chat user (kiri) */
        .bg-light {
            background-color: #f8f9fa !important;
            /* abu muda */
            color: #000 !important;
            /* teks hitam */
        }

        /* Text dalam bubble staf */
        .bg-teal-600 p,
        .bg-teal-600 small {
            color: #fff !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid d-flex flex-column" style="height: 94vh">
        <div class="row flex-grow-1 g-3">

            <!-- Sidebar Chat List -->
            <div class="col-12 col-md-4 col-lg-3 d-flex flex-column">
                <div class="bg-white p-3 rounded shadow-sm flex-grow-1 overflow-auto">
                    <h1 class="h5 fw-bold mb-4">Chat</h1>
                    <input type="text" placeholder="Search" class="form-control mb-4" id="chatSearch">

                    @foreach ($sessions as $chat)
                        <div class="d-flex align-items-center p-2 border-bottom hover:bg-light" style="cursor: pointer;"
                            data-session-id="{{ $chat->id_session }}"
                            onclick="selectChat('{{ $chat->id_session }}', '{{ $chat->nama_orang_tua }}', '{{ $chat->wali_dari }}')">
                            <img src="{{ asset('image/profile.png') }}" class="rounded-circle me-3"
                                style="width: 48px; height: 48px;">
                            <div class="flex-grow-1">
                                <div class="fw-bold">{{ $chat->nama_orang_tua }}</div>
                                <small class="text-muted">Wali dari {{ $chat->wali_dari }}</small><br>
                                <small class="text-secondary text-truncate d-block last-message" style="max-width: 150px;">
                                    {{ $chat->last_message }}
                                </small>
                            </div>
                            <div class="text-end">
                                <small
                                    class="last-time">{{ \Carbon\Carbon::parse($chat->last_message_time)->diffForHumans() }}</small><br>
                                @if ($chat->unread_count > 0)
                                    <span
                                        class="badge bg-primary rounded-pill unread-badge">{{ $chat->unread_count }}</span>
                                @else
                                    <span class="badge bg-primary rounded-pill unread-badge d-none">0</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <!-- Chat Box -->
            <div class="col-12 col-md-8 col-lg-6 d-flex flex-column">
                <div class="bg-white p-3 rounded shadow-sm flex-grow-1 d-flex flex-column overflow-auto">

                    <!-- Chat Messages -->
                    <div id="chatMessages" class="flex-grow-1 overflow-auto mb-3" style="max-height: calc(100vh - 100px);">
                        <div id="chatPlaceholder" class="d-flex justify-content-center align-items-center h-100 text-muted">
                            <p class="text-center">Pilih percakapan dari daftar untuk memulai chat.</p>
                        </div>

                        <div id="chatContent" style="display: none;"></div>
                    </div>

                    <!-- Input Chat -->
                    <div id="inputChatContainer" class="d-flex align-items-center gap-2" style="display: none;">
                        <button class="btn btn-outline-secondary btn-sm">+</button>
                        <input type="text" id="inputMessage" class="form-control" placeholder="Type a message"
                            autocomplete="off">
                        <button class="btn btn-outline-secondary btn-sm">😊</button>
                        <button class="btn btn-outline-secondary btn-sm">🎤</button>
                        <button class="btn btn-primary btn-sm" onclick="sendMessage()">Kirim</button>
                    </div>

                </div>
            </div>

            <!-- User Info -->
            <div class="col-12 col-lg-3 d-flex flex-column">
                <div class="bg-white p-3 rounded shadow-sm flex-grow-1 text-center overflow-auto">
                    <div id="userInfoPanel" class="text-center d-none">
                        <img src="{{ asset('image/profile.png') }}" class="rounded-circle mb-3"
                            style="width: 100px; height: 100px;">
                        <h5 class="fw-bold" id="userNama">-</h5>
                        <small class="text-muted" id="userWali">Wali dari -</small>
                        <p class="mt-2 small" id="userInfoDaerah">-</p>
                        <button class="btn btn-outline-danger btn-sm mt-3">Blokir User</button>
                    </div>
                    <p id="userInfoPlaceholder" class="text-muted mt-5 text-center">Tidak ada pengguna yang dipilih.</p>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const BASE_URL = "{{ url('/') }}";
    </script>
    <script src="https://cdn.ably.io/lib/ably.min-1.js" defer></script>

    <script defer>
        document.addEventListener('DOMContentLoaded', () => {
            let activeSessionId = '{{ $activeSessionId ?? '' }}';

            // Fungsi selectChat harus global agar onclick dari HTML bisa akses
            window.selectChat = function(idSession, namaOrtu, waliDari) {
                console.log('Chat selected:', idSession);
                activeSessionId = idSession;

                // Show user info panel
                document.getElementById('userInfoPanel').classList.remove('d-none');
                document.getElementById('userInfoPlaceholder').classList.add('d-none');

                // Clear chat box & show loading placeholder
                const chatMessages = document.getElementById('chatMessages');
                chatMessages.innerHTML = '';

                // Remove unread badge on chat item
                const chatItem = document.querySelector(`[data-session-id="${idSession}"]`);
                if (chatItem) {
                    chatItem.querySelector('.unread-badge')?.classList.add('d-none');
                }

                // Show chat input
                document.getElementById('inputChatContainer').style.display = 'flex';

                // Load chat messages via API
                fetch(`${BASE_URL}/api/chat/session/${idSession}`)
                    .then(res => res.json())
                    .then(data => {
                        if (!Array.isArray(data)) data = [];

                        chatMessages.innerHTML = ''; // Clear existing

                        data.forEach(msg => {
                            const isStaf = msg.pengirim === 'staf';
                            const waktu = msg.waktu.slice(11, 16);
                            const profileImage = "{{ asset('image/profile.png') }}";

                            const html = isStaf ?
                                `<div class="d-flex justify-content-end mb-3">
                                <div class="bg-teal-600 text-white p-2 rounded-3" style="max-width: 250px;">
                                    <p class="mb-1">${msg.pesan}</p>
                                    <small class="text-white-50">${waktu}</small>
                                </div>
                            </div>` :
                                `<div class="d-flex align-items-start mb-3">
                                <img src="${profileImage}" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                <div class="bg-light p-2 rounded-3">
                                    <p class="mb-1">${msg.pesan}</p>
                                    <small class="text-muted">${waktu}</small>
                                </div>
                            </div>`;

                            chatMessages.insertAdjacentHTML('beforeend', html);
                        });

                        // Scroll ke bawah setelah load chat
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(err => {
                        chatMessages.innerHTML =
                            '<p class="text-center text-danger">Gagal memuat pesan.</p>';
                        console.error(err);
                    });

                // Load user info
                fetch(`${BASE_URL}/api/chat/user-info/${idSession}`)
                    .then(res => res.json())
                    .then(user => {
                        document.getElementById('userNama').textContent = user.nama_orang_tua ||
                            'Tidak diketahui';
                        document.getElementById('userWali').textContent = 'Wali dari ' + (user.wali_dari ||
                            '-');
                        document.getElementById('userInfoDaerah').textContent = user.asal_daerah || '-';
                    })
                    .catch(err => {
                        console.error('User info error:', err);
                    });
            };

            // Fungsi sendMessage harus global agar tombol bisa akses
            window.sendMessage = function() {
                const inputMessage = document.getElementById('inputMessage');
                if (!inputMessage) return alert('Input pesan tidak ditemukan!');

                const text = inputMessage.value.trim();
                if (!text) return alert('Isi pesan terlebih dahulu!');
                if (!activeSessionId) return alert('Pilih chat dulu!');

                fetch(`${BASE_URL}/api/chat/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_session: activeSessionId,
                            pengirim: 'staf',
                            pesan: text
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log('Pesan terkirim:', data);
                        inputMessage.value = '';

                        // Setelah terkirim, bisa append pesan baru langsung ke chat (optional)
                        const chatMessages = document.getElementById('chatMessages');
                        const waktu = new Date().toISOString().slice(11, 16);
                        const html = `<div class="d-flex justify-content-end mb-3">
                    <div class="bg-teal-600 text-white p-2 rounded-3" style="max-width: 250px;">
                        <p class="mb-1">${text}</p>
                        <small class="text-white-50">${waktu}</small>
                    </div>
                </div>`;
                        chatMessages.insertAdjacentHTML('beforeend', html);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    })
                    .catch(err => {
                        alert('Gagal mengirim pesan!');
                        console.error(err);
                    });
            };

            // Jika ada session aktif dari blade, load langsung
            if (activeSessionId) {
                // Cari data session di sidebar untuk ambil nama ortu dan wali
                const chatItem = document.querySelector(`[data-session-id="${activeSessionId}"]`);
                const namaOrtu = chatItem ? chatItem.querySelector('.fw-bold')?.textContent.trim() : '';
                const waliDari = chatItem ? chatItem.querySelector('small.text-muted')?.textContent.trim().replace(
                    'Wali dari ', '') : '';
                if (namaOrtu && waliDari) {
                    window.selectChat(activeSessionId, namaOrtu, waliDari);
                }
            }

            // Inisialisasi Ably real-time
            const ably = new Ably.Realtime('{{ config('services.ably.key') }}');
            const channel = ably.channels.get('pesantren-chat');

            // Listener untuk pesan baru
            channel.subscribe('new-message', function(message) {
                const msg = message.data;

                // Tampilkan hanya jika pesan masuk untuk sesi yang sedang aktif
                if (msg.id_session === activeSessionId && msg.pengirim === 'user') {
                    const waktu = msg.waktu.slice(11, 16);
                    const profileImage = "{{ asset('image/profile.png') }}";

                    const html = `<div class="d-flex align-items-start mb-3">
            <img src="${profileImage}" class="rounded-circle me-2" style="width: 32px; height: 32px;">
            <div class="bg-light p-2 rounded-3">
                <p class="mb-1">${msg.pesan}</p>
                <small class="text-muted">${waktu}</small>
            </div>
        </div>`;

                    const chatMessages = document.getElementById('chatMessages');
                    chatMessages.insertAdjacentHTML('beforeend', html);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } else {
                    // Tambahkan badge notifikasi jika bukan session yang aktif
                    const chatItem = document.querySelector(`[data-session-id="${msg.id_session}"]`);
                    if (chatItem) {
                        const badge = chatItem.querySelector('.unread-badge');
                        badge.classList.remove('d-none');
                        const count = parseInt(badge.textContent) || 0;
                        badge.textContent = count + 1;
                    }
                }
            });


        });
    </script>
@endpush
