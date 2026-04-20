@extends('layouts.app')

@section('title', 'Pesan - Santri Monitoring')
@section('breadcrumb', 'Chat Wali')
@section('main-class', 'flex-1 overflow-hidden p-0')

@push('styles')
<style>
    .chat-layout {
        height: calc(100vh - 64px);
        display: flex;
        overflow: hidden;
    }
    .chat-sidebar {
        width: 320px;
        flex-shrink: 0;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
        overflow: hidden;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e2e8f0' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .chat-list-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
        position: relative;
    }
    .chat-list-item:hover { background: #f8fafc; }
    .chat-list-item.active {
        background: #f0fdf4;
        border-left: 3px solid #16a34a;
    }
    .chat-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .chat-input-area {
        border-top: 1px solid #e2e8f0;
        padding: 12px 16px;
        background: #ffffff;
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }
    .chat-date-separator {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 8px 0 14px;
        color: #94a3b8;
        font-size: 0.72rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .chat-date-separator::before,
    .chat-date-separator::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }
    .chat-time {
        font-size: 0.68rem;
        margin-top: 5px;
        opacity: 0.7;
    }
    .chat-bubble.sent .chat-time { text-align: right; color: rgba(255,255,255,0.8); }
    .chat-bubble.received .chat-time { text-align: left; color: #94a3b8; }
    .message-input-wrapper {
        flex: 1;
        background: #f1f5f9;
        border-radius: 24px;
        display: flex;
        align-items: flex-end;
        padding: 8px 14px;
        gap: 8px;
        transition: background 0.15s;
    }
    .message-input-wrapper:focus-within { background: #e2e8f0; }
    .message-input-wrapper textarea {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        resize: none;
        font-size: 0.875rem;
        color: #1e293b;
        line-height: 1.5;
        max-height: 120px;
        min-height: 22px;
        font-family: inherit;
    }
    .message-input-wrapper textarea::placeholder { color: #94a3b8; }
    .send-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #15803d);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(22,163,74,0.35);
    }
    .send-btn:hover { transform: scale(1.08); box-shadow: 0 4px 14px rgba(22,163,74,0.45); }
    .send-btn:active { transform: scale(0.96); }
    .chat-empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 16px;
        background: #f8fafc;
    }
    .online-dot {
        width: 9px; height: 9px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid #fff;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(34,197,94,0.2);
        animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { box-shadow: 0 0 0 2px rgba(34,197,94,0.2); }
        50% { box-shadow: 0 0 0 5px rgba(34,197,94,0.05); }
    }
    .search-chat { padding: 12px 14px; border-bottom: 1px solid #f1f5f9; }
    .search-chat-wrapper { position: relative; }
    .search-chat-icon { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
    .search-chat input {
        width: 100%;
        background: #f1f5f9;
        border: none;
        border-radius: 20px;
        padding: 8px 14px 8px 36px;
        font-size: 0.8rem;
        outline: none;
        color: #1e293b;
        font-family: inherit;
        transition: background 0.15s;
    }
    .search-chat input:focus { background: #e9f5ee; }
    .search-chat input::placeholder { color: #94a3b8; }
    .unread-badge {
        min-width: 20px; height: 20px;
        background: #16a34a;
        color: #fff;
        border-radius: 10px;
        font-size: 0.68rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        flex-shrink: 0;
    }
    .chat-header-bar {
        padding: 13px 20px;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .sidebar-header-chat {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    }
    .av1 { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .av2 { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
    .av3 { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .av4 { background: linear-gradient(135deg, #10b981, #059669); }
    .av5 { background: linear-gradient(135deg, #ec4899, #db2777); }
    .av6 { background: linear-gradient(135deg, #14b8a6, #0891b2); }
    .chat-list-scroll { overflow-y: auto; flex: 1; }
    .attach-btn {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.15s;
        flex-shrink: 0;
    }
    .attach-btn:hover { background: #f1f5f9; color: #16a34a; }
    .icon-btn-chat {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #475569;
        transition: all 0.15s;
    }
    .icon-btn-chat:hover { background: #e2e8f0; color: #16a34a; }
</style>
@endpush

@section('content')
<div
    class="chat-layout"
    x-data="{
        activeChat: @js($activeParentId ?? 1),
        message: '',
        searchQuery: '',
        conversations: @js($parentsArray),
        messages: @js($messages),
        activeParent: @js($activeParent),
        get filtered() {
            if (!this.searchQuery.trim()) return this.conversations;
            const q = this.searchQuery.toLowerCase();
            return this.conversations.filter(c =>
                c.name.toLowerCase().includes(q) || c.child.toLowerCase().includes(q)
            );
        },
        get activeConv() {
            return this.conversations.find(c => c.id === this.activeChat) || null;
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const area = document.getElementById('msgArea');
                if (area) area.scrollTop = area.scrollHeight;
            });
        },
        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        },
        sendMessage() {
            if (!this.message.trim()) return;
            document.getElementById('messageForm').action = `/chat/${this.activeChat}`;
            document.getElementById('messageForm').submit();
        },
        listenForMessages() {
            if (!this.activeChat) return;

            // Leave old channel if any
            window.Echo.leave(`chat.${this.activeChat}`);

            window.Echo.private(`chat.${this.activeChat}`)
                .listen('MessageSent', (e) => {
                    this.messages.push({
                        id: e.id,
                        pesan: e.pesan,
                        is_from_admin: e.is_from_admin,
                        time: e.time
                    });
                    this.scrollToBottom();
                });
        }
    }"
    x-init="scrollToBottom(); $watch('activeChat', (val) => listenForMessages()); listenForMessages();"
>
    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- LEFT PANEL: Conversation List                          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <aside class="chat-sidebar">

        {{-- Sidebar header --}}
        <div class="sidebar-header-chat">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-bold text-slate-800">Pesan</h2>
                <button class="icon-btn-chat" title="Pesan baru">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
            </div>
            {{-- Search --}}
            <div class="search-chat-wrapper">
                <span class="search-chat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input
                    type="text"
                    x-model="searchQuery"
                    placeholder="Cari wali santri..."
                />
            </div>
        </div>

        {{-- Conversation list --}}
        <div class="chat-list-scroll">
            <template x-for="conv in filtered" :key="conv.id">
                <div
                    class="chat-list-item"
                    :class="{ 'active': activeChat === conv.id }"
                    @click="activeChat = conv.id"
                >
                    {{-- Avatar --}}
                    <div class="relative flex-shrink-0">
                        <div
                            class="avatar avatar-md flex items-center justify-center text-white text-sm font-bold"
                            :class="conv.av"
                            x-text="conv.initials"
                        ></div>
                        <span
                            x-show="conv.online"
                            class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"
                        ></span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1">
                            <span class="font-semibold text-slate-800 text-sm truncate" x-text="conv.name"></span>
                            <span class="text-xs text-slate-400 flex-shrink-0" x-text="conv.time"></span>
                        </div>
                        <div class="flex items-center justify-between gap-1 mt-0.5">
                            <span class="text-xs text-slate-400 truncate">
                                Wali: <span class="text-green-700 font-medium" x-text="conv.child + ' ' + conv.kelas"></span>
                            </span>
                        </div>
                        <div class="flex items-center justify-between gap-1 mt-1">
                            <span class="text-xs text-slate-500 truncate" x-text="conv.lastMessage"></span>
                            <span
                                x-show="conv.unread > 0"
                                class="unread-badge flex-shrink-0"
                                x-text="conv.unread"
                            ></span>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Empty search result --}}
            <div x-show="filtered.length === 0" class="flex flex-col items-center justify-center py-12 text-slate-400 gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p class="text-sm">Tidak ada percakapan</p>
            </div>
        </div>
    </aside>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- RIGHT PANEL: Chat View                                 --}}
    {{-- ═══════════════════════════════════════════════════════ --}}

    {{-- Active chat --}}
    <div class="chat-main" x-show="activeChat !== null">

        {{-- Chat header --}}
        <div class="chat-header-bar" x-show="activeConv">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div
                        class="avatar avatar-md flex items-center justify-center text-white text-sm font-bold"
                        :class="activeConv?.av"
                        x-text="activeConv?.initials"
                    ></div>
                    <span x-show="activeConv?.online" class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <div class="font-bold text-slate-800 text-sm" x-text="activeConv?.name"></div>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span x-show="activeConv?.online" class="online-dot"></span>
                        <span class="text-xs text-slate-500" x-text="activeConv?.online ? 'Online' : 'Terakhir aktif beberapa jam lalu'"></span>
                        <span class="text-xs text-slate-400 ml-1">
                            &bull; Wali <span class="text-green-700 font-medium" x-text="activeConv?.child + ' (' + activeConv?.kelas + ')'"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="icon-btn-chat" title="Hubungi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.42 2 2 0 0 1 3.6 1.25h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 5.5 5.5l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/></svg>
                </button>
                <button class="icon-btn-chat" title="Info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </button>
                <button class="icon-btn-chat" title="Opsi lainnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
            </div>
        </div>

        {{-- Messages area --}}
        <div class="chat-messages-area" id="msgArea">

            {{-- Date separator --}}
            <div class="chat-date-separator">Hari ini, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>

            {{-- Messages from database --}}
            <template x-if="messages.length === 0">
                <div class="flex items-center justify-center h-full text-slate-400">
                    <p class="text-sm">Belum ada pesan dalam percakapan ini</p>
                </div>
            </template>

            <template x-for="msg in messages" :key="msg.id">
                <div
                    class="flex"
                    :class="msg.is_from_admin ? 'flex-col items-end' : 'flex-col items-start'"
                >
                    <div
                        class="chat-bubble"
                        :class="msg.is_from_admin ? 'sent' : 'received'"
                    >
                        <p x-text="msg.pesan"></p>
                        <div class="chat-time flex items-center justify-end gap-1">
                            <span x-text="msg.time"></span>
                            <template x-if="msg.is_from_admin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:0.8"><polyline points="20 6 9 17 4 12"/></svg>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Spacer so last message is not glued to input --}}
            <div class="h-2"></div>
        </div>

        {{-- Input area --}}
        <div class="chat-input-area">
            {{-- Attach button --}}
            <button class="attach-btn" title="Lampiran">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            </button>

            {{-- Message form --}}
            <form id="messageForm" method="POST" class="flex flex-1 items-end gap-2" style="display: flex; flex: 1; gap: 8px;">
                @csrf
                <input type="hidden" name="parent_id" :value="activeChat">

                {{-- Emoji --}}
                <button type="button" class="attach-btn" style="width:28px;height:28px;flex-shrink:0;" title="Emoji">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </button>
                <div class="message-input-wrapper" style="flex: 1;">
                    <textarea
                        name="pesan"
                        x-model="message"
                        @input="autoResize($el)"
                        @keydown.enter.prevent.exact="sendMessage()"
                        placeholder="Ketik pesan..."
                        rows="1"
                    ></textarea>
                </div>
            </form>

            {{-- Send button --}}
            <button type="button" class="send-btn" @click="sendMessage()" title="Kirim pesan">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </div>
    </div>

    {{-- Empty / no active chat state --}}
    <div class="chat-empty-state" x-show="activeChat === null">
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#bbf7d0,#dcfce7);display:flex;align-items:center;justify-content:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="text-center">
            <p class="font-semibold text-slate-600 text-base">Pilih Percakapan</p>
            <p class="text-sm text-slate-400 mt-1">Pilih wali santri dari daftar untuk memulai percakapan</p>
        </div>
    </div>

</div>
@endsection
