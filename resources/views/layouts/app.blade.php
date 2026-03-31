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
            /* Hiệu ứng Highlight bài viết khi được click từ thông báo */
            @keyframes highlight-fade {
                0% {
                    background-color: #f5f7ff;
                    border-color: #6366f1;
                    box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
                    transform: scale(1.01);
                }
                100% {
                    background-color: white;
                    border-color: #f1f5f9;
                    transform: scale(1);
                }
            }

            /* Áp dụng khi URL có hash ID bài viết (ví dụ: #post-5) */
            :target {
                animation: highlight-fade 3s ease-out;
                scroll-margin-top: 100px; /* Cách Menu Bar 1 đoạn khi cuộn tới */
                border-radius: 1.5rem;
            }

            /* Custom scrollbar cho toàn trang */
            ::-webkit-scrollbar {
                width: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #f8fafc;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* Hỗ trợ hiển thị nội dung thông báo dài */
            .truncate-2-lines {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
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

        <div id="realtime-toast"
             style="position: fixed; bottom: 24px; right: 24px; width: 360px; z-index: 9999; transform: translateY(150px); opacity: 0; transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); pointer-events: none;">

            <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.15)] border border-slate-100 p-4 flex items-center space-x-4 pointer-events-auto cursor-pointer hover:bg-slate-50 transition-all active:scale-95 border-l-4 border-l-indigo-600">

                <div id="toast-avatar" class="shrink-0 relative">
                    </div>

                <div class="flex-1 min-w-0">
                    <p id="toast-message" class="text-sm text-slate-800 font-bold leading-tight truncate-2-lines"></p>
                    <span class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-1 block">Thông báo mới</span>
                </div>

                <button onclick="hideToast(event)" class="p-1.5 text-slate-300 hover:text-slate-500 hover:bg-slate-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <script>
            function hideToast(e) {
                if (e) e.stopPropagation();
                const toast = document.getElementById('realtime-toast');
                if (toast) {
                    toast.style.transform = "translateY(150px)";
                    toast.style.opacity = "0";
                }
            }

            // Hàm này được gọi từ navigation.blade.php khi có thông báo mới từ API
            window.showToastNotification = function(notification) {
                const toast = document.getElementById('realtime-toast');
                const messageEl = document.getElementById('toast-message');
                const avatarEl = document.getElementById('toast-avatar');

                if (!toast || !messageEl || !avatarEl) return;

                messageEl.innerText = notification.data.message;

                const type = notification.data.type;
                const iconColor = type === 'like' ? 'bg-red-500' : 'bg-green-500';
                const iconSvg = type === 'like'
                    ? '<path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path>'
                    : '<path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"></path>';

                avatarEl.innerHTML = `
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(notification.data.user_name)}&background=random&color=fff" class="w-12 h-12 rounded-full shadow-sm object-cover border-2 border-white">
                    <div class="absolute -bottom-1 -right-1 p-1 rounded-full ring-2 ring-white ${iconColor}">
                        <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">${iconSvg}</svg>
                    </div>
                `;

                // Hiện Toast trượt lên
                toast.style.transform = "translateY(0)";
                toast.style.opacity = "1";

                // Tự động ẩn sau 8 giây
                setTimeout(hideToast, 8000);

                // Click vào để đi tới bài viết
                toast.onclick = (e) => {
                    if (!e.target.closest('button')) {
                        window.location.href = `/notifications/${notification.id}/go`;
                    }
                };
            }
        </script>
    </body>
</html>
