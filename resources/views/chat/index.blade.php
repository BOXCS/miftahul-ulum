@extends('layouts.app')

@section('title', 'Pesan - Santri Monitoring')
@section('breadcrumb', 'Chat Wali')
@section('main-class', 'flex-1 overflow-hidden p-0')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    :root {
        --teal-900: #134e4a;
        --teal-800: #115e59;
        --teal-700: #0f766e;
        --teal-600: #0d9488;
        --teal-500: #14b8a6;
        --teal-400: #2dd4bf;
        --teal-300: #5eead4;
        --teal-100: #ccfbf1;
        --teal-50:  #f0fdfa;
        --slate-900: #0f172a;
        --slate-800: #1e293b;
        --slate-700: #334155;
        --slate-500: #64748b;
        --slate-400: #94a3b8;
        --slate-200: #e2e8f0;
        --slate-100: #f1f5f9;
        --slate-50:  #f8fafc;
        --white: #ffffff;
        --radius-pill: 9999px;
        --radius-lg: 16px;
        --radius-md: 10px;
        --shadow-teal: 0 8px 24px rgba(13,148,136,.22);
        --shadow-card: 0 2px 12px rgba(15,23,42,.07);
    }

    * { box-sizing: border-box; }

    .chat-layout {
        height: calc(100vh - 64px);
        display: flex;
        overflow: hidden;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--slate-100);
    }

    /* ─── SIDEBAR ─────────────────────────────── */
    .chat-sidebar {
        width: 310px;
        flex-shrink: 0;
        background: var(--white);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border-right: 1px solid var(--slate-200);
        box-shadow: 2px 0 16px rgba(15,23,42,.05);
        z-index: 2;
    }

    .sidebar-top {
        padding: 18px 16px 14px;
        background: linear-gradient(160deg, var(--teal-900) 0%, var(--teal-700) 100%);
        position: relative;
        overflow: hidden;
    }
    .sidebar-top::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .sidebar-top::after {
        content: '';
        position: absolute;
        bottom: -20px; left: 20px;
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .sidebar-top h2 {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--white);
        margin: 0 0 2px;
        letter-spacing: -.01em;
        position: relative; z-index:1;
    }
    .sidebar-top .sub {
        font-size: .72rem;
        color: var(--teal-300);
        font-weight: 500;
        position: relative; z-index:1;
    }
    .new-chat-btn {
        position: absolute; top: 16px; right: 16px; z-index:1;
        width: 34px; height: 34px;
        border-radius: 10px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--white);
        transition: background .2s, transform .15s;
    }
    .new-chat-btn:hover { background: rgba(255,255,255,.25); transform: scale(1.06); }

    /* search */
    .sidebar-search {
        padding: 12px 14px 10px;
        background: var(--white);
        border-bottom: 1px solid var(--slate-100);
    }
    .search-wrap {
        position: relative;
    }
    .search-wrap svg {
        position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
        color: var(--slate-400); pointer-events: none;
    }
    .search-wrap input {
        width: 100%;
        background: var(--slate-100);
        border: 1.5px solid transparent;
        border-radius: var(--radius-pill);
        padding: 8px 14px 8px 34px;
        font-size: .8rem;
        font-family: inherit;
        color: var(--slate-800);
        outline: none;
        transition: border-color .2s, background .2s;
    }
    .search-wrap input:focus {
        background: var(--teal-50);
        border-color: var(--teal-400);
    }
    .search-wrap input::placeholder { color: var(--slate-400); }

    /* conv list */
    .chat-list-scroll { overflow-y: auto; flex: 1; }
    .chat-list-scroll::-webkit-scrollbar { width: 4px; }
    .chat-list-scroll::-webkit-scrollbar-thumb { background: var(--slate-200); border-radius: 4px; }

    .conv-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid var(--slate-100);
        transition: background .15s;
        position: relative;
    }
    .conv-item:hover { background: var(--slate-50); }
    .conv-item.active {
        background: var(--teal-50);
    }
    .conv-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, var(--teal-600), var(--teal-400));
        border-radius: 0 3px 3px 0;
    }
    .conv-avatar-wrap { position: relative; flex-shrink: 0; }
    .conv-avatar {
        width: 44px; height: 44px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .01em;
    }
    .conv-item.active .conv-avatar { box-shadow: 0 4px 14px rgba(13,148,136,.35); }
    .online-indicator {
        position: absolute; bottom: -1px; right: -1px;
        width: 11px; height: 11px;
        background: #22c55e;
        border: 2px solid var(--white);
        border-radius: 50%;
    }
    .conv-info { flex: 1; min-width: 0; }
    .conv-name {
        font-size: .82rem;
        font-weight: 700;
        color: var(--slate-800);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .conv-item.active .conv-name { color: var(--teal-700); }
    .conv-meta {
        font-size: .7rem;
        color: var(--teal-600);
        font-weight: 600;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-top: 2px;
    }
    .conv-last {
        font-size: .72rem;
        color: var(--slate-400);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-top: 3px;
    }
    .conv-right { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
    .conv-time { font-size: .65rem; color: var(--slate-400); font-weight: 500; }
    .conv-item.active .conv-time { color: var(--teal-500); }
    .unread-badge {
        min-width: 18px; height: 18px;
        background: linear-gradient(135deg, var(--teal-600), var(--teal-500));
        color: #fff;
        border-radius: var(--radius-pill);
        font-size: .62rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        padding: 0 5px;
        box-shadow: 0 2px 8px rgba(13,148,136,.4);
    }

    /* ─── MAIN CHAT ───────────────────────────── */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #eef2f7;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(13,148,136,.06) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(20,184,166,.05) 0%, transparent 50%),
            url("data:image/svg+xml,%3Csvg width='52' height='52' viewBox='0 0 52 52' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%2394a3b8' fill-opacity='0.07'%3E%3Ccircle cx='26' cy='26' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* header */
    .chat-header-bar {
        padding: 12px 20px;
        background: var(--white);
        border-bottom: 1px solid var(--slate-200);
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 12px rgba(15,23,42,.06);
        z-index: 1;
    }
    .header-avatar {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: .82rem; font-weight: 700;
        position: relative;
        box-shadow: 0 4px 12px rgba(13,148,136,.3);
    }
    .header-name {
        font-size: .88rem;
        font-weight: 700;
        color: var(--slate-800);
        letter-spacing: -.01em;
    }
    .header-status {
        display: flex; align-items: center; gap: 5px;
        margin-top: 2px;
    }
    .status-dot {
        width: 7px; height: 7px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse-dot 2.5s infinite;
    }
    @keyframes pulse-dot {
        0%,100% { box-shadow: 0 0 0 0 rgba(34,197,94,.4); }
        50%      { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
    }
    .status-text {
        font-size: .7rem; color: var(--slate-500); font-weight: 500;
    }
    .header-child-tag {
        display: inline-flex; align-items: center; gap: 4px;
        background: var(--teal-50);
        border: 1px solid var(--teal-200, #99f6e4);
        border-radius: 20px;
        padding: 2px 9px;
        font-size: .68rem;
        color: var(--teal-700);
        font-weight: 600;
        margin-left: 6px;
    }

    .icon-btn {
        width: 34px; height: 34px;
        border-radius: 10px;
        background: var(--slate-100);
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--slate-500);
        transition: background .15s, color .15s, transform .15s;
    }
    .icon-btn:hover { background: var(--teal-50); color: var(--teal-600); transform: scale(1.05); }

    /* messages */
    .chat-messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px 28px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .chat-messages-area::-webkit-scrollbar { width: 4px; }
    .chat-messages-area::-webkit-scrollbar-thumb { background: var(--slate-300, #cbd5e1); border-radius: 4px; }

    .date-sep {
        display: flex; align-items: center; gap: 10px;
        margin: 10px 0 16px;
        color: var(--slate-400);
        font-size: .67rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .07em;
    }
    .date-sep::before, .date-sep::after {
        content: ''; flex: 1; height: 1px; background: var(--slate-200);
    }

    .msg-row { display: flex; flex-direction: column; margin-bottom: 2px; }
    .msg-row.sent  { align-items: flex-end; }
    .msg-row.recv  { align-items: flex-start; }

    /* sender label */
    .msg-label {
        font-size: .65rem; font-weight: 600;
        color: var(--slate-400);
        margin-bottom: 3px;
        padding: 0 14px;
    }

    .bubble {
        max-width: 68%;
        padding: 10px 14px;
        font-size: .82rem;
        line-height: 1.55;
        word-break: break-word;
        position: relative;
    }
    .bubble p { margin: 0; }

    .bubble.sent {
        background: linear-gradient(135deg, var(--teal-600) 0%, var(--teal-500) 100%);
        color: #fff;
        border-radius: 18px 18px 4px 18px;
        box-shadow: 0 4px 16px rgba(13,148,136,.28);
    }
    .bubble.recv {
        background: var(--white);
        color: var(--slate-800);
        border-radius: 18px 18px 18px 4px;
        box-shadow: 0 2px 10px rgba(15,23,42,.08);
    }

    .bubble-time {
        font-size: .62rem;
        margin-top: 5px;
        display: flex; align-items: center; gap: 3px;
    }
    .bubble.sent  .bubble-time { color: rgba(255,255,255,.7); justify-content: flex-end; }
    .bubble.recv  .bubble-time { color: var(--slate-400); }

    /* consecutive same-sender: tighter gap */
    .msg-row + .msg-row.sent  { margin-top: 1px; }
    .msg-row + .msg-row.recv  { margin-top: 1px; }
    .msg-row.sent + .msg-row.recv,
    .msg-row.recv + .msg-row.sent { margin-top: 10px; }

    /* ─── INPUT AREA ──────────────────────────── */
    .chat-input-area {
        padding: 12px 20px 14px;
        background: var(--white);
        border-top: 1px solid var(--slate-200);
        display: flex;
        align-items: flex-end;
        gap: 10px;
        box-shadow: 0 -2px 12px rgba(15,23,42,.05);
    }

    .input-action-btn {
        width: 38px; height: 38px;
        border-radius: 12px;
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--slate-500);
        background: var(--slate-100);
        transition: background .15s, color .15s, transform .15s;
        flex-shrink: 0;
    }
    .input-action-btn:hover { background: var(--teal-50); color: var(--teal-600); transform: scale(1.06); }
    .input-action-btn.active-emoji { background: var(--teal-50); color: var(--teal-600); }

    .msg-input-box {
        flex: 1;
        background: var(--slate-100);
        border: 1.5px solid transparent;
        border-radius: 18px;
        display: flex;
        align-items: flex-end;
        padding: 9px 14px;
        gap: 6px;
        transition: border-color .2s, background .2s, box-shadow .2s;
    }
    .msg-input-box:focus-within {
        background: var(--teal-50);
        border-color: var(--teal-400);
        box-shadow: 0 0 0 3px rgba(13,148,136,.08);
    }
    .msg-input-box textarea {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        resize: none;
        font-size: .85rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--slate-800);
        line-height: 1.55;
        max-height: 120px;
        min-height: 22px;
    }
    .msg-input-box textarea::placeholder { color: var(--slate-400); }

    .send-btn {
        width: 44px; height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--teal-600), var(--teal-500));
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        box-shadow: var(--shadow-teal);
        transition: background .2s, transform .2s, box-shadow .2s;
        color: #fff;
    }
    .send-btn:hover {
        background: linear-gradient(135deg, var(--teal-700), var(--teal-600));
        transform: translateY(-1px) scale(1.04);
        box-shadow: 0 8px 22px rgba(13,148,136,.35);
    }
    .send-btn:active { transform: scale(.96); }

    /* emoji picker */
    .emoji-picker-panel {
        position: absolute;
        bottom: 80px; left: 20px;
        background: var(--white);
        border: 1px solid var(--slate-200);
        border-radius: 16px;
        padding: 12px 14px;
        box-shadow: 0 12px 40px rgba(15,23,42,.14);
        z-index: 100;
        display: none;
        flex-wrap: wrap;
        gap: 6px;
        width: 292px;
        animation: pop-in .18s ease;
    }
    .emoji-picker-panel.open { display: flex; }
    @keyframes pop-in {
        from { opacity: 0; transform: scale(.92) translateY(8px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .emoji-btn-pick {
        width: 36px; height: 36px;
        border-radius: 8px;
        border: none;
        background: transparent;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .12s, transform .12s;
    }
    .emoji-btn-pick:hover { background: var(--teal-50); transform: scale(1.18); }

    /* file attachment preview */
    .attach-preview {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        background: var(--teal-50);
        border: 1px dashed var(--teal-400);
        border-radius: 10px;
        margin-bottom: 6px;
        font-size: .75rem;
        color: var(--teal-700);
        font-weight: 600;
    }
    .attach-preview.show { display: flex; }
    .attach-remove {
        margin-left: auto;
        cursor: pointer;
        color: var(--slate-400);
        background: none; border: none;
        font-size: .9rem;
        line-height: 1;
        transition: color .15s;
        flex-shrink: 0;
    }
    .attach-remove:hover { color: #ef4444; }

    /* ─── EMPTY STATE ─────────────────────────── */
    .chat-empty-state {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 18px;
        background: #eef2f7;
    }
    .empty-icon-ring {
        width: 88px; height: 88px;
        border-radius: 28px;
        background: linear-gradient(135deg, var(--teal-100), var(--teal-50));
        border: 2px solid rgba(13,148,136,.15);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 8px 24px rgba(13,148,136,.14);
    }

    /* ─── AVATAR COLORS ───────────────────────── */
    .av1 { background: linear-gradient(135deg,#6366f1,#8b5cf6); }
    .av2 { background: linear-gradient(135deg,#0ea5e9,#2563eb); }
    .av3 { background: linear-gradient(135deg,#f59e0b,#f97316); }
    .av4 { background: linear-gradient(135deg, var(--teal-600), var(--teal-400)); }
    .av5 { background: linear-gradient(135deg,#ec4899,#db2777); }
    .av6 { background: linear-gradient(135deg,#14b8a6,#0891b2); }

    /* ─── SCROLLBAR GLOBAL ────────────────────── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--slate-200); border-radius: 5px; }
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
        showEmoji: false,
        attachedFile: null,
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
        insertEmoji(e) {
            this.message += e;
            this.showEmoji = false;
            this.$nextTick(() => {
                const ta = document.getElementById('msgTextarea');
                if (ta) { this.autoResize(ta); ta.focus(); }
            });
        },
        triggerAttach() {
            document.getElementById('fileInput').click();
        },
        handleFile(event) {
            const file = event.target.files[0];
            if (file) { this.attachedFile = file.name; }
        },
        removeAttach() {
            this.attachedFile = null;
            document.getElementById('fileInput').value = '';
        },
        sendMessage() {
            if (!this.message.trim() && !this.attachedFile) return;
            document.getElementById('messageForm').action = `/chat/${this.activeChat}`;
            document.getElementById('messageForm').submit();
        },
        listenForMessages() {
            if (!this.activeChat) return;
            window.Echo.leave(`chat.${this.activeChat}`);
            window.Echo.private(`chat.${this.activeChat}`)
                .listen('MessageSent', (e) => {
                    this.messages.push({ id: e.id, pesan: e.pesan, is_from_admin: e.is_from_admin, time: e.time });
                    this.scrollToBottom();
                });
        }
    }"
    x-init="scrollToBottom(); $watch('activeChat', () => listenForMessages()); listenForMessages();"
    @click.outside="showEmoji = false"
>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SIDEBAR                                    --}}
    {{-- ══════════════════════════════════════════ --}}
    <aside class="chat-sidebar">

        {{-- Header --}}
        <div class="sidebar-top">
            <h2>Pesan</h2>
            <p class="sub">Percakapan dengan wali santri</p>
            <button class="new-chat-btn" title="Pesan baru">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
        </div>

        {{-- Search --}}
        <div class="sidebar-search">
            <div class="search-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari wali santri…">
            </div>
        </div>

        {{-- List --}}
        <div class="chat-list-scroll">
            <template x-for="conv in filtered" :key="conv.id">
                <div
                    class="conv-item"
                    :class="{ 'active': activeChat === conv.id }"
                    @click="activeChat = conv.id"
                >
                    <div class="conv-avatar-wrap">
                        <div class="conv-avatar" :class="conv.av" x-text="conv.initials"></div>
                        <span x-show="conv.online" class="online-indicator"></span>
                    </div>
                    <div class="conv-info">
                        <div class="conv-name" x-text="conv.name"></div>
                        <div class="conv-meta" x-text="conv.child + ' · ' + conv.kelas"></div>
                        <div class="conv-last" x-text="conv.lastMessage"></div>
                    </div>
                    <div class="conv-right">
                        <span class="conv-time" x-text="conv.time"></span>
                        <span x-show="conv.unread > 0" class="unread-badge" x-text="conv.unread"></span>
                    </div>
                </div>
            </template>

            <div x-show="filtered.length === 0" class="flex flex-col items-center justify-center py-12 gap-2" style="color:var(--slate-400);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <p style="font-size:.8rem;">Tidak ada percakapan</p>
            </div>
        </div>
    </aside>

    {{-- ══════════════════════════════════════════ --}}
    {{-- MAIN CHAT                                  --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="chat-main" x-show="activeChat !== null" style="position:relative;">

        {{-- Header --}}
        <div class="chat-header-bar" x-show="activeConv">
            <div class="flex items-center gap-3">
                <div style="position:relative;">
                    <div class="header-avatar" :class="activeConv?.av" x-text="activeConv?.initials"></div>
                    <span x-show="activeConv?.online" style="position:absolute;bottom:-1px;right:-1px;width:11px;height:11px;background:#22c55e;border:2px solid #fff;border-radius:50%;"></span>
                </div>
                <div>
                    <div class="header-name" x-text="activeConv?.name"></div>
                    <div class="header-status">
                        <span x-show="activeConv?.online" class="status-dot"></span>
                        <span class="status-text" x-text="activeConv?.online ? 'Online' : 'Terakhir aktif beberapa jam lalu'"></span>
                        <span class="header-child-tag">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span x-text="activeConv?.child + ' · ' + activeConv?.kelas"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="icon-btn" title="Hubungi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.63 3.42 2 2 0 0 1 3.6 1.25h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8a16 16 0 0 0 5.5 5.5l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21 16.92z"/></svg>
                </button>
                <button class="icon-btn" title="Info">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </button>
                <button class="icon-btn" title="Opsi lainnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages-area" id="msgArea">
            <div class="date-sep">Hari ini &nbsp;{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>

            <template x-if="messages.length === 0">
                <div class="flex items-center justify-center py-16">
                    <div style="text-align:center;color:var(--slate-400);">
                        <div style="width:56px;height:56px;background:var(--teal-50);border-radius:18px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;border:1px solid rgba(13,148,136,.15);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--teal-500)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <p style="font-size:.82rem;font-weight:600;color:var(--slate-600);">Belum ada pesan</p>
                        <p style="font-size:.72rem;margin-top:3px;">Mulai percakapan sekarang</p>
                    </div>
                </div>
            </template>

            <template x-for="msg in messages" :key="msg.id">
                <div class="msg-row" :class="msg.is_from_admin ? 'sent' : 'recv'">
                    <div class="bubble" :class="msg.is_from_admin ? 'sent' : 'recv'">
                        <p x-text="msg.pesan"></p>
                        <div class="bubble-time">
                            <span x-text="msg.time"></span>
                            <template x-if="msg.is_from_admin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.8"><polyline points="20 6 9 17 4 12"/></svg>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <div class="h-3"></div>
        </div>

        {{-- Emoji picker panel --}}
        <div class="emoji-picker-panel" :class="{ 'open': showEmoji }" @click.stop>
            <div style="width:100%;font-size:.7rem;font-weight:700;color:var(--slate-400);text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;">Ekspresi</div>
            @foreach(['😊','😂','🥰','😎','🤔','😅','🙏','👍','❤️','🔥','✅','📚','🌟','💪','🤝','👏','😢','😮','🎉','📝','⏰','🏠','📞','💬','🤗','😇','🌙','☀️','🙌','💯'] as $e)
                <button class="emoji-btn-pick" @click="insertEmoji('{{ $e }}')" type="button">{{ $e }}</button>
            @endforeach
        </div>

        {{-- Input area --}}
        <div class="chat-input-area" style="flex-direction:column;align-items:stretch;">

            {{-- Attach preview --}}
            <div class="attach-preview" :class="{ 'show': attachedFile !== null }">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                <span x-text="attachedFile"></span>
                <button class="attach-remove" @click="removeAttach()" type="button" title="Hapus lampiran">✕</button>
            </div>

            {{-- Row: buttons + input + send --}}
            <div class="flex items-end gap-2">

                {{-- Attach --}}
                <button type="button" class="input-action-btn" title="Lampiran" @click="triggerAttach()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                </button>
                <input type="file" id="fileInput" style="display:none;" @change="handleFile($event)">

                {{-- Message form --}}
                <form id="messageForm" method="POST" class="flex flex-1 items-end gap-2" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="parent_id" :value="activeChat">

                    {{-- Emoji toggle --}}
                    <button type="button" class="input-action-btn" :class="{ 'active-emoji': showEmoji }" title="Emoji" @click.stop="showEmoji = !showEmoji">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                    </button>

                    <div class="msg-input-box">
                        <textarea
                            id="msgTextarea"
                            name="pesan"
                            x-model="message"
                            @input="autoResize($el)"
                            @keydown.enter.prevent.exact="sendMessage()"
                            placeholder="Ketik pesan…"
                            rows="1"
                        ></textarea>
                    </div>
                </form>

                {{-- Send --}}
                <button type="button" class="send-btn" @click="sendMessage()" title="Kirim">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Empty state --}}
    <div class="chat-empty-state" x-show="activeChat === null">
        <div class="empty-icon-ring">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="var(--teal-600)" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div style="text-align:center;">
            <p style="font-size:.95rem;font-weight:700;color:var(--slate-700);letter-spacing:-.01em;">Pilih Percakapan</p>
            <p style="font-size:.78rem;color:var(--slate-400);margin-top:5px;">Pilih wali santri dari daftar untuk memulai</p>
        </div>
    </div>

</div>
@endsection