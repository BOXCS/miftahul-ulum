@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush

@section('content')

{{-- <div class="container-fluid min-vh-100 d-flex flex-column">
    <div class="row flex-grow-1 g-3"> --}}
<div class="container-fluid">
    <div class="d-flex flex-row mb-3 gap-2" >

        <!-- Sidebar Chat List -->
        <div class="col-12 col-md-4 col-lg-3 d-flex flex-column">
            <div class="bg-white p-3 rounded shadow-sm flex-grow-1 overflow-auto">
                <h1 class="h5 fw-bold mb-4">Chat</h1>
                <input type="text" placeholder="Search" class="form-control mb-4">
                @foreach($chats as $chat)
                <div class="d-flex align-items-center p-2 border-bottom hover:bg-light" style="cursor: pointer;">
                    <img src="{{ asset('image/profile.png') }}" class="rounded-circle me-3" style="width: 48px; height: 48px;">
                    <div class="flex-grow-1">
                        <div class="fw-bold">{{ $chat['nama'] }}</div>
                        <small class="text-muted">Wali dari {{ $chat['wali_dari'] }}</small><br>
                        <small class="text-secondary text-truncate d-block" style="max-width: 150px;">{{ $chat['pesan'] }}</small>
                    </div>
                    <div class="text-end">
                        <small>{{ $chat['waktu'] }}</small><br>
                        <span class="badge bg-primary rounded-pill">{{ $chat['jumlah_pesan_baru'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Chat Box -->
        <div class="col-12 col-md-8 col-lg-6 d-flex flex-column">
            <div class="bg-white p-3 rounded shadow-sm flex-grow-1 d-flex flex-column overflow-auto">
                <div class="flex-grow-1 overflow-auto mb-3">
                    @foreach($messages as $msg)
                        @if($msg['sender'] == 'yanto')
                            <div class="d-flex align-items-start mb-3">
                                <img src="{{ asset('image/profile.png') }}" class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                <div class="bg-light p-2 rounded-3">
                                    <p class="mb-1">{{ $msg['text'] }}</p>
                                    <small class="text-muted">{{ $msg['time'] }}</small>
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-end mb-3">
                                <div class="bg-teal-600 text-white p-2 rounded-3" style="max-width: 250px;">
                                    <p class="mb-1">{{ $msg['text'] }}</p>
                                    <small class="text-white-50">{{ $msg['time'] }}</small>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Input Chat -->
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-outline-secondary btn-sm">+</button>
                    <input type="text" class="form-control" placeholder="Type a message">
                    <button class="btn btn-outline-secondary btn-sm">😊</button>
                    <button class="btn btn-outline-secondary btn-sm">🎤</button>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="col-12 col-lg-3 d-flex flex-column">
            <div class="bg-white p-3 rounded shadow-sm flex-grow-1 text-center overflow-auto">
                <img src="{{ asset('image/profile.png') }}" class="rounded-circle mb-3" style="width: 100px; height: 100px;">
                <h5 class="fw-bold">{{ $userProfile['nama'] }}</h5>
                <small class="text-muted">Wali dari {{ $userProfile['wali_dari'] }}</small>
                <p class="mt-2 small">{{ $userProfile['asal'] }}</p>
                <button class="btn btn-outline-danger btn-sm mt-3">Blokir User</button>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
@endpush
