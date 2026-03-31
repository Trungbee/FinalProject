<nav x-data="{ open: false, tab: 'all' }" class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                        <div class="bg-indigo-600 p-2 rounded-xl group-hover:rotate-12 transition-all shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-black tracking-tighter text-slate-800 uppercase italic">Travel<span class="text-indigo-600">X</span></span>
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                <x-dropdown align="right" width="96">
                    <x-slot name="trigger">
                        <button id="notification-bell" class="relative p-2.5 text-slate-500 hover:text-indigo-600 transition-all bg-slate-100/50 rounded-full focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <div id="unread-dot" class="{{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'hidden' }}">
                                <span class="absolute top-1.5 right-1.5 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white animate-pulse"></span>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div @click.stop class="bg-white rounded-2xl overflow-hidden shadow-xl">
                            <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Thông báo</h2>
                            </div>

                            <div class="px-4 py-2 flex space-x-2 border-b border-slate-50 bg-white">
                                <button @click="tab = 'all'" :class="tab === 'all' ? 'bg-indigo-100 text-indigo-700' : 'hover:bg-slate-100 text-slate-600'" class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider transition">Tất cả</button>
                                <button @click="tab = 'unread'" :class="tab === 'unread' ? 'bg-indigo-100 text-indigo-700' : 'hover:bg-slate-100 text-slate-600'" class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider transition">Chưa đọc</button>
                            </div>

                            <div id="notification-container" class="max-h-[480px] overflow-y-auto custom-scrollbar bg-white">
                                @include('layouts.notification-list-items')
                            </div>

                            <div class="p-2 border-t border-slate-100 bg-white text-center">
                                <a href="{{ route('notifications.markAsRead') }}" class="text-xs font-bold text-indigo-600 hover:underline tracking-tight">Đánh dấu tất cả đã đọc</a>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 p-1 bg-slate-100/50 rounded-full border border-transparent hover:border-slate-200 transition-all focus:outline-none shadow-sm">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff" class="w-9 h-9 rounded-full object-cover shadow-sm">
                            @endif
                            <span class="text-sm font-bold text-slate-700 px-1 hidden md:block">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-50 text-[10px] font-black uppercase text-slate-400 tracking-widest">Tài khoản</div>

                        <x-dropdown-link :href="route('user.profile', auth()->user()->name)" class="font-bold text-slate-700">
                            👤 {{ __('Trang cá nhân') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('profile.edit')" class="font-medium text-slate-500">
                            ⚙️ {{ __('Cài đặt tài khoản') }}
                        </x-dropdown-link>

                        <div class="border-t border-slate-50"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500 font-bold">
                                🚪 {{ __('Đăng xuất') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

<script>
    // Logic kiểm tra thông báo mới và tự động cập nhật
    let lastNotificationId = '{{ auth()->user()->notifications->first()?->id }}';

    async function checkNotifications() {
        try {
            const response = await fetch('/api/notifications/latest');
            const data = await response.json();

            if (data.latest_id && data.latest_id !== lastNotificationId) {
                lastNotificationId = data.latest_id;
                const latest = data.notifications[0];

                // Hiện Toast (hàm này nằm ở app.blade.php)
                if (window.showToastNotification) {
                    window.showToastNotification(latest);
                }

                // Hiện chấm đỏ chuông
                document.getElementById('unread-dot')?.classList.remove('hidden');

                // Chèn HTML mới vào danh sách
                prependNotification(latest);
            }
        } catch (e) { console.error('Realtime sync error'); }
    }

    function prependNotification(notif) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const type = notif.data.type;
        const iconColor = type === 'like' ? 'bg-red-500' : 'bg-green-500';
        const iconSvg = type === 'like'
            ? '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>'
            : '<svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"></path></svg>';

        // Tạo chuỗi HTML cho thông báo mới
        const html = `
            <a href="/notifications/${notif.id}/go"
               class="px-4 py-4 hover:bg-slate-50 transition-colors flex items-center space-x-4 relative border-b border-slate-50/50 bg-blue-50/40">
                <div class="relative shrink-0">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(notif.data.user_name)}&background=random&color=fff" class="w-14 h-14 rounded-full object-cover border border-slate-100">
                    <div class="absolute -bottom-1 -right-1 p-1.5 rounded-full ring-2 ring-white ${iconColor}">${iconSvg}</div>
                </div>
                <div class="flex-1 pr-2 text-left">
                    <p class="text-[14px] text-slate-800 leading-tight">
                        <span class="font-bold text-slate-900">${notif.data.user_name}</span>
                        ${notif.data.message.replace(notif.data.user_name, '')}
                    </p>
                    <span class="text-[12px] font-semibold text-blue-600 mt-1 block italic">Vừa xong</span>
                </div>
                <div class="w-3 h-3 bg-blue-600 rounded-full shrink-0"></div>
            </a>
        `;
        container.insertAdjacentHTML('afterbegin', html);
    }

    setInterval(checkNotifications, 5000);
</script>
