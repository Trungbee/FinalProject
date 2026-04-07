@forelse(auth()->user()->notifications->take(15) as $notification)
    <a href="{{ route('notifications.go', $notification->id) }}"
       x-show="tab === 'all' || (tab === 'unread' && {{ $notification->read_at ? 'false' : 'true' }})"
       class="px-4 py-4 hover:bg-slate-50 transition-colors flex items-center space-x-4 relative border-b border-slate-50/50 {{ $notification->read_at ? '' : 'bg-blue-50/40' }}">

        <div class="relative shrink-0">
            @php
                // LOGIC CHUẨN: Tìm theo ID trước, nếu không có thì tìm theo Tên (Tương thích ngược)
                $triggerUserId = $notification->data['user_id'] ?? null;

                if ($triggerUserId) {
                    $triggerUser = \App\Models\User::find($triggerUserId);
                } else {
                    $triggerUserName = $notification->data['user_name'] ?? '';
                    $triggerUser = $triggerUserName ? \App\Models\User::where('name', $triggerUserName)->first() : null;
                }
            @endphp

            @if($triggerUser && $triggerUser->avatar)
                <img src="{{ asset('storage/' . $triggerUser->avatar) }}" class="w-14 h-14 rounded-full object-cover border border-slate-100 shadow-sm">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($notification->data['user_name'] ?? 'U') }}&background=random&color=fff" class="w-14 h-14 rounded-full object-cover border border-slate-100 shadow-sm">
            @endif

            <div class="absolute -bottom-1 -right-1 p-1.5 rounded-full ring-2 ring-white shadow-sm {{ ($notification->data['type'] ?? '') == 'like' ? 'bg-red-500' : (($notification->data['type'] ?? '') == 'follow' ? 'bg-blue-500' : 'bg-green-500') }}">
                @if(($notification->data['type'] ?? '') == 'like')
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"></path></svg>
                @elseif(($notification->data['type'] ?? '') == 'follow')
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"></path></svg>
                @else
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z"></path></svg>
                @endif
            </div>
        </div>

        <div class="flex-1 pr-2 text-left">
            <p class="text-[14px] text-slate-800 leading-tight">
                <span class="font-bold text-slate-900">{{ $notification->data['user_name'] ?? 'Người dùng' }}</span>
                {{ str_replace($notification->data['user_name'] ?? '', '', $notification->data['message'] ?? '') }}
            </p>
            <span class="text-[12px] font-semibold {{ $notification->read_at ? 'text-slate-400' : 'text-blue-600' }} mt-1 block tracking-tight">
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </div>

        @if(!$notification->read_at)
            <div class="w-3 h-3 bg-blue-600 rounded-full shrink-0"></div>
        @endif
    </a>
@empty
    <div class="px-4 py-16 text-center bg-white">
        <p class="text-slate-400 text-sm italic">Không có thông báo nào</p>
    </div>
@endforelse
