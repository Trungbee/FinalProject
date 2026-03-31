<x-app-layout>
    <div class="py-10 bg-[#F8FAFC] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <aside class="hidden lg:block lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-28 overflow-hidden font-sans">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>

                        <div class="flex flex-col items-center text-center pb-6 border-b border-slate-50">
                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="relative group block mb-4">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-24 h-24 rounded-3xl object-cover shadow-xl border-4 border-white group-hover:scale-105 transition duration-300">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=96" class="w-24 h-24 rounded-3xl object-cover shadow-xl border-4 border-white group-hover:scale-105 transition duration-300">
                                @endif
                                <div class="absolute bottom-2 right-0 w-5 h-5 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
                            </a>

                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="hover:text-indigo-600 transition-colors">
                                <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ auth()->user()->name }}</h3>
                            </a>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-1 italic">Travel Enthusiast</p>
                        </div>

                        <nav class="mt-8 space-y-2">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 p-3 rounded-2xl bg-indigo-50 text-indigo-700 group transition-all duration-300">
                                <span class="p-2 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform">🏠</span>
                                <span class="font-bold text-sm">Bảng tin</span>
                            </a>
                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="flex items-center space-x-4 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 group transition-all duration-300">
                                <span class="p-2 bg-white rounded-xl border border-slate-100 group-hover:scale-110 transition-transform">👤</span>
                                <span class="font-bold text-sm">Trang cá nhân</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-4 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 group transition-all duration-300">
                                <span class="p-2 bg-white rounded-xl border border-slate-100 group-hover:scale-110 transition-transform">⚙️</span>
                                <span class="font-bold text-sm">Cài đặt</span>
                            </a>
                        </nav>
                    </div>
                </aside>

                <main class="col-span-1 lg:col-span-6 space-y-6">
                    <x-create-post-form />
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </main>

                <aside class="hidden lg:block lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-28 font-sans">
                        <h3 class="font-bold text-slate-800 italic mb-6">🔥 Gợi ý hôm nay</h3>
                        <div class="space-y-4">
                             <div class="relative overflow-hidden rounded-2xl shadow-md aspect-video">
                                <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=300&q=80" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-3">
                                    <p class="text-white text-xs font-bold italic">Maldives - Thiên đường xanh</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', async function(e) {
            const likeBtn = e.target.closest('.btn-like-ajax');
            if (likeBtn) {
                const postId = likeBtn.dataset.postId;
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'}
                });
                const data = await response.json();
                likeBtn.querySelector('.likes-count').innerText = data.likes_count;
                const svg = likeBtn.querySelector('svg');
                if (data.is_liked) { likeBtn.classList.add('text-red-500'); svg.setAttribute('fill', 'currentColor'); }
                else { likeBtn.classList.remove('text-red-500'); svg.setAttribute('fill', 'none'); }
            }

            const commentBtn = e.target.closest('.btn-comment-ajax');
            if (commentBtn) {
                const postId = commentBtn.dataset.postId;
                const input = document.getElementById(`comment-input-${postId}`);
                const response = await fetch(`/posts/${postId}/comment`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
                    body: JSON.stringify({ content: input.value })
                });
                const data = await response.json();
                const list = document.querySelector(`.comments-list-${postId}`);
                list.insertAdjacentHTML('beforeend', data.html);
                input.value = '';
                list.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    </script>
</x-app-layout>
