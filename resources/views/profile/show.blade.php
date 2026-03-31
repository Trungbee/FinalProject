<x-app-layout>
    <div class="py-10 bg-[#F8FAFC] min-h-screen font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 p-8 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-10"></div>

                <div class="relative flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-8">
                    <div class="relative group">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="w-32 h-32 rounded-[2.5rem] object-cover border-4 border-white shadow-2xl">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=128" class="w-32 h-32 rounded-[2.5rem] object-cover border-4 border-white shadow-2xl">
                        @endif
                        <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
                    </div>

                    <div class="flex-1 text-center md:text-left pb-2">
                        <h1 class="text-3xl font-black text-slate-800 tracking-tight">{{ $user->name }}</h1>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-1">Travel Enthusiast • Joined {{ $user->created_at->format('M Y') }}</p>

                        <div class="flex items-center justify-center md:justify-start mt-4 space-x-6">
                            <div class="text-center">
                                <span class="block text-xl font-black text-slate-800">{{ $posts->count() }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bài viết</span>
                            </div>
                            <div class="text-center border-x border-slate-100 px-6">
                                <span class="block text-xl font-black text-slate-800">{{ $posts->sum('likes_count') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Lượt thích</span>
                            </div>
                            <div class="text-center">
                                <span class="block text-xl font-black text-slate-800">{{ $posts->sum('comments_count') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bình luận</span>
                            </div>
                        </div>
                    </div>

                    @if(auth()->id() === $user->id)
                        <div class="pb-2">
                            <a href="{{ route('profile.edit') }}" class="px-6 py-3 bg-slate-900 text-white rounded-2xl text-xs font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                                Chỉnh sửa hồ sơ
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($posts as $post)
                    <div class="group bg-white rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-500">
                        <div class="aspect-video overflow-hidden bg-slate-100">
                            @if($post->media->first())
                                <img src="{{ asset($post->media->first()->file_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            @endif
                        </div>
                        <div class="p-6 text-left">
                            <div class="flex justify-between mb-2">
                                <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $post->created_at->diffForHumans() }}</span>
                                <span class="text-xs font-bold text-slate-400">❤️ {{ $post->likes_count }} 💬 {{ $post->comments_count }}</span>
                            </div>
                            <p class="text-slate-700 font-medium text-sm line-clamp-2">{{ $post->content }}</p>
                            <a href="{{ route('dashboard') }}#post-{{ $post->id }}" class="mt-4 block w-full text-center py-2 text-xs font-bold text-slate-500 border border-slate-100 rounded-xl hover:bg-slate-50">Xem chi tiết</a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-slate-400 italic py-10">Chưa có bài đăng nào.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
