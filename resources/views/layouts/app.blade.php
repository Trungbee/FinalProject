<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TravelGram') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes highlight-fade {
                0% { background-color: #f5f7ff; border-color: #6366f1; box-shadow: 0 0 20px rgba(99, 102, 241, 0.2); transform: scale(1.01); }
                100% { background-color: white; border-color: #f1f5f9; transform: scale(1); }
            }
            :target { animation: highlight-fade 3s ease-out; scroll-margin-top: 100px; border-radius: 1.5rem; }
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #f8fafc; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            .truncate-2-lines { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-900">
        <div class="min-h-screen bg-[#F8FAFC]">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white shadow-sm border-b border-slate-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- THÔNG BÁO NỔI GÓC DƯỚI BÊN PHẢI (TOAST) -->
        <div id="realtime-toast"
             style="position: fixed; bottom: 24px; right: 24px; width: 360px; z-index: 9999; transform: translateY(150px); opacity: 0; transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); pointer-events: none;">
            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-slate-100 p-4 flex items-center space-x-4 pointer-events-auto cursor-pointer hover:bg-slate-50 transition-all active:scale-95 border-l-4 border-l-indigo-600">
                <div id="toast-avatar" class="shrink-0 relative"></div>
                <div class="flex-1 min-w-0">
                    <p id="toast-message" class="text-sm text-slate-800 font-bold leading-tight truncate-2-lines"></p>
                    <span class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-1 block">Thông báo mới</span>
                </div>
                <button onclick="hideToast(event)" class="p-1.5 text-slate-300 hover:text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>

        <!-- KHUNG CHAT NỔI (STYLE FACEBOOK) -->
        <div id="chat-box" class="fixed bottom-0 right-24 w-80 bg-white rounded-t-2xl shadow-[0_-5px_25px_rgba(0,0,0,0.15)] flex flex-col border border-slate-200 z-[9998] hidden transition-all" style="height: 420px;">
            <!-- Header Chat Box -->
            <div class="px-4 py-3 bg-indigo-600 rounded-t-2xl flex justify-between items-center text-white shadow-sm">
                <a id="chat-box-link" href="#" class="flex items-center space-x-2 hover:opacity-80 transition group">
                    <img id="chat-box-avatar" src="" class="w-8 h-8 rounded-full border border-indigo-400 object-cover shadow-sm group-hover:scale-105 transition">
                    <span id="chat-box-name" class="font-bold text-sm tracking-tight border-b border-transparent group-hover:border-white transition"></span>
                </a>
                <button onclick="closeChatBox(event)" class="text-indigo-200 hover:text-white hover:bg-indigo-700 p-1 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50 custom-scrollbar text-sm"></div>

            <div class="p-3 border-t border-slate-100 bg-white">
                <form id="chat-form" onsubmit="sendChatMessage(event)" class="flex items-center space-x-2">
                    <input type="text" id="chat-input" class="flex-1 border-none bg-slate-100 rounded-full px-4 py-2 text-sm focus:ring-1 focus:ring-indigo-500 transition" placeholder="Nhập tin nhắn..." autocomplete="off">
                    <button type="submit" class="p-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- TOÀN BỘ SCRIPT LOGIC CHAT -->
        <script>
            function hideToast(e) {
                if (e) e.stopPropagation();
                const toast = document.getElementById('realtime-toast');
                if (toast) { toast.style.transform = "translateY(150px)"; toast.style.opacity = "0"; }
            }

            window.showToastNotification = function(notification) {
                const toast = document.getElementById('realtime-toast');
                const messageEl = document.getElementById('toast-message');
                const avatarEl = document.getElementById('toast-avatar');
                if (!toast || !messageEl || !avatarEl) return;
                messageEl.innerText = notification.data.message;
                const type = notification.data.type;
                let iconColor = 'bg-green-500';
                let iconSvg = '<path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"></path>';
                if (type === 'like') { iconColor = 'bg-red-500'; iconSvg = '<path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>'; }
                else if (type === 'follow') { iconColor = 'bg-blue-500'; iconSvg = '<path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path>'; }
                avatarEl.innerHTML = `<img src="${notification.avatar_url}" class="w-12 h-12 rounded-full shadow-sm object-cover border-2 border-white"><div class="absolute -bottom-1 -right-1 p-1 rounded-full ring-2 ring-white ${iconColor}"><svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">${iconSvg}</svg></div>`;
                toast.style.transform = "translateY(0)"; toast.style.opacity = "1";
                setTimeout(hideToast, 8000);
                toast.onclick = (e) => { if (!e.target.closest('button')) window.location.href = `/notifications/${notification.id}/go`; };
            }

            let currentChatUserId = null;
            let chatPollingInterval = null;
            let globalLastMessageId = 0;

            // BƯỚC 1: LOAD DANH SÁCH + HIỆN TIN NHẮN CUỐI + CHẤM ĐỎ CON
            async function loadContacts() {
                try {
                    const [resUsers, resCheck] = await Promise.all([fetch('/api/chat/contacts'), fetch('/api/chat/check')]);
                    const users = await resUsers.json();
                    const checkData = await resCheck.json();
                    const container = document.getElementById('contacts-container');
                    if(users.length === 0) { container.innerHTML = `<div class="p-4 text-center text-xs text-slate-400 italic">Chưa có liên hệ nào</div>`; return; }
                    container.innerHTML = users.map(user => {
                        const isUnread = checkData.unread_count > 0 && checkData.latest_message && checkData.latest_message.sender_id === user.id;
                        const lastMsg = isUnread ? checkData.latest_message.content : "Nhấn để trò chuyện";
                        return `
                            <div onclick="openChatBox(${user.id}, '${user.name}', '${user.avatar_url}')" class="flex items-center space-x-3 p-2 hover:bg-slate-50 rounded-xl cursor-pointer transition relative group">
                                <div class="relative">
                                    <img src="${user.avatar_url}" class="w-10 h-10 rounded-full object-cover border border-slate-100 shadow-sm">
                                    ${isUnread ? '<span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-red-500 border-2 border-white rounded-full"></span>' : ''}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm ${isUnread ? 'font-black text-slate-900' : 'font-bold text-slate-800'} truncate">${user.name}</h4>
                                    <p class="text-[11px] ${isUnread ? 'text-indigo-600 font-bold' : 'text-slate-400'} truncate">${lastMsg}</p>
                                </div>
                            </div>`;
                    }).join('');
                } catch(e) { console.error(e); }
            }

            async function openChatBox(userId, name, avatarUrl) {
                currentChatUserId = userId;
                document.getElementById('chat-box').classList.remove('hidden');
                document.getElementById('chat-box-name').innerText = name;
                document.getElementById('chat-box-avatar').src = avatarUrl;
                document.getElementById('chat-box-link').href = `/profile/${encodeURIComponent(name)}`;
                await fetchChatMessages();
                if (chatPollingInterval) clearInterval(chatPollingInterval);
                chatPollingInterval = setInterval(fetchChatMessages, 3000);
            }

            function closeChatBox(e) { e.stopPropagation(); document.getElementById('chat-box').classList.add('hidden'); currentChatUserId = null; if (chatPollingInterval) clearInterval(chatPollingInterval); }

            async function fetchChatMessages() {
                if (!currentChatUserId) return;
                try {
                    const res = await fetch(`/api/chat/${currentChatUserId}/messages`);
                    const messages = await res.json();
                    const container = document.getElementById('chat-messages');
                    const authId = {{ auth()->check() ? auth()->id() : 'null' }};
                    if (!authId) return;
                    const wasAtBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 10;
                    container.innerHTML = messages.map(msg => {
                        const isMe = msg.sender_id === authId;
                        return `<div class="flex ${isMe ? 'justify-end' : 'justify-start'}"><div class="${isMe ? 'bg-indigo-600 text-white rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl' : 'bg-white border border-slate-200 text-slate-800 rounded-tl-2xl rounded-tr-2xl rounded-br-2xl'} px-4 py-2 max-w-[75%] shadow-sm leading-relaxed">${msg.content}</div></div>`;
                    }).join('');
                    if(wasAtBottom || messages.length > 0) container.scrollTop = container.scrollHeight;
                } catch(e) {}
            }

            async function sendChatMessage(e) {
                e.preventDefault();
                const input = document.getElementById('chat-input');
                const content = input.value.trim();
                if (!content || !currentChatUserId) return;
                input.value = '';
                try {
                    await fetch(`/api/chat/${currentChatUserId}/send`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ content })
                    });
                    fetchChatMessages();
                } catch(e) { console.error('Lỗi gửi tin nhắn'); }
            }

            // BƯỚC 2: RADAR TỰ ĐỘNG BẬT KHUNG CHAT + CHẤM ĐỎ MENU
            async function checkGlobalChat() {
                const authId = {{ auth()->check() ? auth()->id() : 'null' }};
                if (!authId) return;
                try {
                    const res = await fetch('/api/chat/check');
                    const data = await res.json();
                    const chatDot = document.getElementById('chat-unread-dot');
                    if (chatDot) { if (data.unread_count > 0) chatDot.classList.remove('hidden'); else chatDot.classList.add('hidden'); }
                    if (data.latest_message) {
                        if (globalLastMessageId === 0) { globalLastMessageId = data.latest_message.id; return; }
                        if (data.latest_message.id > globalLastMessageId) {
                            globalLastMessageId = data.latest_message.id;
                            if (document.getElementById('chat-box').classList.contains('hidden') || currentChatUserId !== data.latest_message.sender_id) {
                                openChatBox(data.latest_message.sender_id, data.latest_message.sender_name, data.latest_message.sender_avatar);
                            }
                            loadContacts();
                        }
                    }
                } catch(e) { }
            }

            setInterval(checkGlobalChat, 3000);
            document.addEventListener('DOMContentLoaded', () => { loadContacts(); checkGlobalChat(); });
        </script>
    </body>
</html>
