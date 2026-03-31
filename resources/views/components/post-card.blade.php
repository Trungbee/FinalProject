@props(['post'])

<div id="post-{{ $post->id }}" class="bg-white rounded-3xl shadow-sm border border-slate-100 mb-6 overflow-hidden post-container transition-all duration-300 scroll-mt-24" data-post-id="{{ $post->id }}">

    <div class="flex items-center px-6 pt-6 mb-4 space-x-3">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" class="w-10 h-10 rounded-2xl shadow-sm border-2 border-white">
        <div>
            <h3 class="font-bold text-slate-800 text-[15px] leading-none">{{ $post->user->name }}</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                @if($post->location_name)
                    <span class="text-indigo-600 italic">📍 {{ $post->location_name }}</span> •
                @endif
                {{ $post->created_at->diffForHumans() }}
            </p>
        </div>
    </div>

    <p class="text-slate-700 text-[15px] px-6 pb-4 leading-relaxed font-medium whitespace-pre-wrap">{{ $post->content }}</p>

    @if($post->media->count() > 0)
        <div class="px-6 pb-4">
            @foreach($post->media as $media)
                <img src="{{ asset($media->file_url) }}" class="w-full rounded-2xl shadow-sm object-cover max-h-[40rem] border border-slate-50">
            @endforeach
        </div>
    @endif

    <div class="flex items-center px-6 py-4 border-t border-slate-50 space-x-8">
        @php $isLiked = $post->likes->where('user_id', auth()->id())->first(); @endphp

        <button type="button"
                class="btn-like-ajax flex items-center space-x-2 transition-all hover:scale-110 active:scale-90 focus:outline-none {{ $isLiked ? 'text-red-500' : 'text-slate-400' }}"
                data-post-id="{{ $post->id }}">
            <svg class="w-6 h-6 pointer-events-none" fill="{{ $isLiked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" stroke-width="2"></path>
            </svg>
            <span class="font-black text-xs likes-count pointer-events-none tracking-tighter">{{ $post->likes_count }}</span>
        </button>

        <div class="flex items-center space-x-2 text-slate-400 font-bold">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" stroke-width="2"></path>
            </svg>
            <span class="text-xs comments-count tracking-tighter">{{ $post->comments_count }}</span>
        </div>
    </div>

    <div class="bg-slate-50/50 px-6 py-4 border-t border-slate-50">
        <div class="space-y-4 comments-list-{{ $post->id }}">
            @foreach($post->comments as $comment)
                <div class="flex items-start space-x-3 text-sm">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name) }}&background=random" class="w-8 h-8 rounded-xl shadow-sm border border-white">
                    <div class="bg-white px-4 py-2 rounded-2xl border border-slate-100 shadow-sm flex-1">
                        <span class="font-bold text-slate-800 text-xs">{{ $comment->user->name }}</span>
                        <p class="text-slate-600 mt-0.5 leading-snug font-medium">{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex items-center space-x-3">
            <input type="text" id="comment-input-{{ $post->id }}" placeholder="Viết bình luận..."
                   class="flex-1 bg-white border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-100 transition-all border-none shadow-sm">
            <button type="button"
                    class="btn-comment-ajax bg-indigo-600 text-white p-3 rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95"
                    data-post-id="{{ $post->id }}">🚀Gửi</button>
        </div>
    </div>
</div>
