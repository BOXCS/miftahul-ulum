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
            /* pastikan teksnya hitam biar kebaca */
        }

        /* Pastikan text di dalam bubble staf juga terlihat */
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
                    <input type="text" placeholder="Search" class="form-control mb-4">

                    @foreach ($sessions as $chat)
                        <div class="d-flex align-items-center p-2 border-bottom hover:bg-light" style="cursor: pointer;"
                            data-session-id="{{ $chat->id_session }}" {{-- Tambahkan ini --}}
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
                    <div id="chatMessages" class="flex-grow-1 overflow-auto mb-3">
                        @foreach ($messages as $msg)
                            @if ($msg->pengirim == 'staf')
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="p-2 rounded-3 text-white"
                                        {{-- style="max-width: 250px; background-color: #20c997;"> --}}

                                        <p class="mb-1">{{ $msg->pesan }}</p>
                                        <small
                                            class="text-white-50">{{ \Carbon\Carbon::parse($msg->waktu)->format('H:i') }}</small>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex align-items-start mb-3">
                                    <img src="{{ asset('image/profile.png') }}" class="rounded-circle me-2"
                                        style="width: 32px; height: 32px;">
                                    <div class="bg-light p-2 rounded-3">
                                        <p class="mb-1">{{ $msg->pesan }}</p>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($msg->waktu)->format('H:i') }}</small>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Input Chat -->
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-outline-secondary btn-sm">+</button>
                        <input type="text" id="inputMessage" class="form-control" placeholder="Type a message">
                        <button class="btn btn-outline-secondary btn-sm">😊</button>
                        <button class="btn btn-outline-secondary btn-sm">🎤</button>
                        <button class="btn btn-primary btn-sm" onclick="sendMessage()">Kirim</button>
                    </div>

                </div>
            </div>

            <!-- User Info -->
            <div class="col-12 col-lg-3 d-flex flex-column">
                <div class="bg-white p-3 rounded shadow-sm flex-grow-1 text-center overflow-auto">
                    <img src="{{ asset('image/profile.png') }}" class="rounded-circle mb-3"
                        style="width: 100px; height: 100px;">
                    <h5 class="fw-bold" id="userNama">Nama Orang Tua</h5>
                    <small class="text-muted" id="userWali">Wali dari ...</small>
                    <p class="mt-2 small" id="userInfoDaerah">Asal Daerah</p>
                    <button class="btn btn-outline-danger btn-sm mt-3">Blokir User</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ably.io/lib/ably.min-1.js"></script>
    <script>
        // Default session id aktif (bisa ganti saat klik sidebar)
        let activeSessionId = '{{ $activeSessionId ?? '' }}';

        // Pilih chat di sidebar
        function selectChat(idSession, namaOrtu, waliDari) {
            console.log('Chat selected:', idSession); // Debug log
            activeSessionId = idSession;
            document.getElementById('userNama').textContent = namaOrtu;
            document.getElementById('userWali').textContent = 'Wali dari ' + waliDari;

            // Reset badge unread jadi 0 saat kita buka
            const chatItem = document.querySelector(`[data-session-id="${idSession}"]`);
            if (chatItem) {
                chatItem.querySelector('.unread-badge')?.classList.add('d-none');
            }

            // Fetch pesan
            fetch(`/api/chat/session/${idSession}`)
                .then(response => response.json())
                .then(data => {
                    const chatBox = document.getElementById('chatMessages');
                    chatBox.innerHTML = ''; // Kosongkan chatBox

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

                        chatBox.insertAdjacentHTML('beforeend', html);
                        chatBox.scrollTop = chatBox.scrollHeight;
                    });
                });

            // Fetch User Info (Asal Daerah)
            fetch(`/api/chat/user-info/${idSession}`)
                .then(response => response.json())
                .then(user => {
                    document.querySelector('#userInfoDaerah').textContent = user.asal_daerah || 'Tidak diketahui';
                })
                .catch(err => console.error('User info error:', err));
        }


        // Kirim Pesan
        function sendMessage() {
            const inputMessage = document.getElementById('inputMessage');
            if (!inputMessage) {
                return alert('Input pesan tidak ditemukan!');
            }

            const text = inputMessage.value;
            if (!text || !activeSessionId) {
                return alert('Pilih chat dulu dan isi pesan!');
            }

            fetch('{{ url('/api/chat/send') }}', {
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

                    // Jangan tampilkan pesan manual di sini, biar Ably yang handle
                    inputMessage.value = ''; // kosongkan input saja
                })
                .catch(error => {
                    console.error('Error kirim pesan:', error);
                });
        }


        // Subscribe Ably realtime
        const ably = new Ably.Realtime('{{ env('ABLY_API_KEY') }}');

        // Subscribe ke semua session channel
        @foreach ($sessions as $chat)
            ably.channels.get('chat-session-{{ $chat->id_session }}').subscribe('new-message', function(message) {
                console.log('Pesan baru:', message);

                const msg = message.data;
                const isStaf = msg.pengirim === 'staf';

                // 1. Update Chat Box jika session aktif
                if (activeSessionId === '{{ $chat->id_session }}') {
                    const html = isStaf ?
                        `<div class="d-flex justify-content-end mb-3">
                    <div class="bg-teal-600 text-white p-2 rounded-3" style="max-width: 250px;">
                        <p class="mb-1">${msg.pesan}</p>
                        <small class="text-white-50">${msg.waktu.slice(11,16)}</small>
                    </div>
                </div>` :
                        `<div class="d-flex align-items-start mb-3">
                    <img src="{{ asset('image/profile.png') }}" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                    <div class="bg-light p-2 rounded-3">
                        <p class="mb-1">${msg.pesan}</p>
                        <small class="text-muted">${msg.waktu.slice(11,16)}</small>
                    </div>
                </div>`;

                    document.getElementById('chatMessages').insertAdjacentHTML('beforeend', html);
                }

                // 2. Update Sidebar Chat List
                const chatItem = document.querySelector(`[data-session-id="{{ $chat->id_session }}"]`);
                if (chatItem) {
                    // Update last message text
                    chatItem.querySelector('.last-message').textContent = msg.pesan;

                    // Update time (pakai waktu slice aja)
                    chatItem.querySelector('.last-time').textContent = msg.waktu.slice(11, 16);

                    // Update unread badge (+1 kecuali session sedang aktif)
                    const badge = chatItem.querySelector('.unread-badge');
                    let unreadCount = parseInt(badge?.textContent || '0');
                    if (activeSessionId === '{{ $chat->id_session }}') {
                        badge?.classList.add('d-none'); // Sembunyikan kalau lagi aktif
                    } else {
                        badge?.classList.remove('d-none');
                        badge.textContent = unreadCount + 1;
                    }
                }
            });
        @endforeach
    </script>
@endpush
