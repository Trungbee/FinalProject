<x-app-layout>
    <div class="py-10 bg-[#F8FAFC] min-h-screen">
        <!-- Sử dụng max-w-7xl để bố cục 3 cột cân đối và đẹp mắt -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- 1. SIDEBAR TRÁI: THÔNG TIN CÁ NHÂN (3 Cột) -->
                <aside class="hidden lg:block lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sticky top-28 overflow-hidden font-sans text-center">

                        <!-- LẤY THÔNG TIN GÓI VÀ ĐỔI MÀU GIAO DIỆN -->
                        @php
                            $tier = auth()->user()->premium_tier ?? 'none';
                            $bgGradient = 'from-indigo-500 via-purple-500 to-pink-500'; // Mặc định
                            if($tier == 'silver') $bgGradient = 'from-slate-300 via-gray-400 to-slate-500';
                            if($tier == 'gold') $bgGradient = 'from-yellow-400 via-amber-500 to-yellow-600';
                            if($tier == 'diamond') $bgGradient = 'from-cyan-300 via-blue-500 to-indigo-600';
                        @endphp

                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $bgGradient }}"></div>

                        <div class="flex flex-col items-center pb-6 border-b border-slate-50">
                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="relative inline-block mb-4 group">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-24 h-24 rounded-3xl object-cover shadow-xl border-4 border-white group-hover:scale-105 transition duration-300">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&size=96" class="w-24 h-24 rounded-3xl object-cover shadow-xl border-4 border-white group-hover:scale-105 transition duration-300">
                                @endif

                                <!-- Dấu chấm Online -->
                                <div class="absolute bottom-2 right-0 w-5 h-5 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>

                                <!-- HUY HIỆU THEO TỪNG GÓI -->
                                @if($tier != 'none')
                                    <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gradient-to-r {{ $bgGradient }} text-white text-[9px] font-black px-3 py-1 rounded-full border-2 border-white shadow-md uppercase tracking-wider whitespace-nowrap z-10">
                                        @if($tier == 'silver') Bạc 🥈
                                        @elseif($tier == 'gold') Vàng 🥇
                                        @elseif($tier == 'diamond') Kim Cương 💎
                                        @endif
                                    </div>
                                @endif
                            </a>

                            <!-- Tên hiển thị kèm icon Premium -->
                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="hover:text-indigo-600 transition-colors {{ $tier != 'none' ? 'mt-2' : '' }}">
                                <h3 class="font-bold text-slate-800 text-lg leading-tight flex items-center justify-center gap-1">
                                    {{ auth()->user()->name }}
                                    @if($tier == 'silver') <span title="Thành viên Bạc">🥈</span>
                                    @elseif($tier == 'gold') <span title="Thành viên Vàng">🥇</span>
                                    @elseif($tier == 'diamond') <span title="Thành viên Kim Cương">💎</span>
                                    @endif
                                </h3>
                            </a>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mt-1 italic">
                                {{ $tier != 'none' ? 'VIP MEMBER' : 'Traveler' }}
                            </p>
                        </div>

                        <nav class="mt-8 space-y-2 text-left">
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-4 p-3 rounded-2xl bg-indigo-50 text-indigo-700 font-bold text-sm transition-all">
                                <span class="p-2 bg-white rounded-xl shadow-sm">🏠</span>
                                <span>Bảng tin</span>
                            </a>
                            <a href="{{ route('user.profile', auth()->user()->name) }}" class="flex items-center space-x-4 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 font-bold text-sm transition-all">
                                <span class="p-2 bg-white rounded-xl border border-slate-100">👤</span>
                                <span>Trang cá nhân</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-4 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 font-bold text-sm transition-all">
                                <span class="p-2 bg-white rounded-xl border border-slate-100">⚙️</span>
                                <span>Cài đặt</span>
                            </a>

                            <!-- MENU BOOKING (CÓ KIỂM TRA ĐIỀU KIỆN VÀ GỌI MODAL) -->
                            @php
                                $canBook = in_array($tier, ['gold', 'diamond']);
                            @endphp
                            <a href="{{ $canBook ? route('booking.index') : 'javascript:void(0)' }}"
                               onclick="{{ !$canBook ? 'showPremiumModal(); return false;' : '' }}"
                               class="flex items-center space-x-4 p-3 rounded-2xl text-slate-500 hover:bg-slate-50 font-bold text-sm transition-all">
                                <span class="p-2 bg-white rounded-xl border border-slate-100 shadow-sm">✈️</span>
                                <span class="flex-1">Booking</span>
                                @if(!$canBook)
                                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                @endif
                            </a>

                            <!-- MENU NÂNG CẤP PREMIUM (Hiệu ứng thay đổi khi đã mua gói) -->
                            <a href="{{ route('premium.index') }}" class="flex items-center space-x-4 p-3 rounded-2xl {{ $tier != 'none' ? 'text-indigo-600 bg-indigo-50 border border-indigo-50' : 'text-amber-600 hover:bg-amber-50 border border-amber-100' }} font-bold text-sm transition-all relative overflow-hidden group mt-4">
                                <span class="p-2 bg-white rounded-xl border {{ $tier != 'none' ? 'border-indigo-200' : 'border-amber-200' }} shadow-sm relative z-10">👑</span>
                                <span class="relative z-10">{{ $tier != 'none' ? 'Đổi gói Premium' : 'Nâng cấp Premium' }}</span>
                            </a>
                        </nav>
                    </div>
                </aside>

                <!-- 2. MAIN FEED: BÀI ĐĂNG (6 Cột) -->
                <main class="col-span-1 lg:col-span-6 space-y-6">
                    <x-create-post-form />
                    @foreach ($posts as $post)
                        <x-post-card :post="$post" />
                    @endforeach
                </main>

                <!-- 3. SIDEBAR PHẢI: CẢM HỨNG (3 Cột) -->
                <aside class="hidden lg:block lg:col-span-3">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 sticky top-28 font-sans">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-black text-slate-800 italic tracking-tight text-xs uppercase">🔥 Cảm hứng</h3>
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase rounded-full">Mood-based</span>
                        </div>

                        <!-- Mood Selector Grid -->
                        <div class="grid grid-cols-3 gap-2 mb-6">
                            <button onclick="filterMood('chill')" class="mood-btn active text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="chill">🏖️ Chill</button>
                            <button onclick="filterMood('adventure')" class="mood-btn text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="adventure">🏔️ Trek</button>
                            <button onclick="filterMood('romantic')" class="mood-btn text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="romantic">🌹 Love</button>
                            <button onclick="filterMood('foodie')" class="mood-btn text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="foodie">🍜 Food</button>
                            <button onclick="filterMood('culture')" class="mood-btn text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="culture">⛩️ Cult</button>
                            <button onclick="filterMood('nature')" class="mood-btn text-[10px] py-2 rounded-xl border border-slate-100 font-bold transition-all" data-mood="nature">🌲 Natu</button>
                        </div>

                        <div id="mood-suggestions" class="space-y-5"></div>
                    </div>
                </aside>

            </div>
        </div>
    </div>

    <!-- MODAL BÁO LỖI YÊU CẦU NÂNG CẤP PREMIUM -->
    <div id="premiumModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop mờ -->
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true" onclick="closePremiumModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Khối Modal -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-md w-full border border-slate-100">
                <div class="px-6 pt-8 pb-6 bg-white sm:p-8 sm:pb-6 text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-amber-50 mb-6 border-4 border-white shadow-sm">
                        <span class="text-4xl">🔒</span>
                    </div>
                    <h3 class="text-2xl font-black leading-6 text-slate-800 tracking-tight" id="modal-title">Tính năng khóa</h3>
                    <div class="mt-5">
                        <p class="text-sm text-slate-500 font-medium leading-relaxed">
                            Tính năng Booking chỉ dành cho hội viên <strong class="text-amber-500">Vàng 🥇</strong> hoặc <strong class="text-cyan-500">Kim Cương 💎</strong>. Vui lòng nâng cấp gói hội viên để mở khóa trải nghiệm!
                        </p>
                    </div>
                </div>
                <div class="px-6 py-5 bg-slate-50 sm:px-8 flex flex-col sm:flex-row-reverse gap-3 rounded-b-[2rem]">
                    <a href="{{ route('premium.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-transparent shadow-lg shadow-amber-500/30 px-6 py-3 bg-gradient-to-r from-amber-400 to-yellow-600 text-sm font-black text-white hover:from-amber-500 hover:to-yellow-700 focus:outline-none transition-all transform hover:scale-105">
                        Nâng cấp ngay 👑
                    </a>
                    <button type="button" onclick="closePremiumModal()" class="w-full sm:w-auto inline-flex justify-center items-center rounded-xl border border-slate-200 shadow-sm px-6 py-3 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition-all">
                        Để sau
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .mood-btn.active { background-color: #4f46e5; color: white; border-color: #4f46e5; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); }
        .mood-card { animation: slideUp 0.4s ease-out forwards; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        const moodData = {
            chill: [
                { name: "Phú Quốc - Đảo Ngọc", desc: "Hoàng hôn tím bên bờ biển.", img: "https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg?auto=compress&cs=tinysrgb&w=400", tag: "Biển" },
                { name: "Hồ Tuyền Lâm", desc: "Chèo thuyền SUP ngắm sương mù.", img: "https://images.pexels.com/photos/1525041/pexels-photo-1525041.jpeg?auto=compress&cs=tinysrgb&w=400", tag: "Tĩnh" }
            ],
            adventure: [
                { name: "Hà Giang - Mã Pí Lèng", desc: "Chinh phục cung đường hạnh phúc.", img: "https://owa.bestprice.vn/images/destinations/uploads/deo-ma-pi-leng-6001155be6540.jpg", tag: "Phượt" },
                { name: "Hang Sơn Đoòng", desc: "Khám phá kỳ quan hùng vĩ.", img: "https://lh3.googleusercontent.com/gps-cs-s/AHVAweoMIv9RIr-_eDCQqRtGT2ptb_Z9STshZAiwKBYEYvVy3yFYsCVjmFkubu8vkNmqhqajajbSORoThu-FCc3kgTrI8XpwwssaLnrr7NSjaWxoPMF6HX_B-xMqn8Q9GBcziXLdZ3iVyQ=w270-h312-n-k-no", tag: "Khám Phá" }
            ],
            romantic: [
                { name: "Hội An - Phố Cổ", desc: "Thả hoa đăng trên dòng sông Hoài.", img: "https://cdn.vntrip.vn/cam-nang/wp-content/uploads/2017/08/hoi-an-quang-nam-vntrip-1.jpg", tag: "Love" },
                { name: "Bà Nà Hills", desc: "Dạo bước Cầu Vàng giữa làn mây.", img: "https://asiaholiday.com.vn/pic/Tour/du-lich-da-nang-ba-na-hills_4865_HasThumb.jpg", tag: "Mây" }
            ],
            foodie: [
                { name: "Ốc Đêm Sài Gòn", desc: "Khám phá văn hóa ẩm thực.", img: "https://lh7-us.googleusercontent.com/docsz/AD_4nXdyBRZDtXB1vzH4Wgvn0lGpE1PQ46d_izCekHR3x7_rHMUURXPBFfT0xbPn0mgqck_wLmDZy8VfcAzScu7eGsM3XtxNzQblar7R98XLhtR3po4ITTCYg4Cfq2pDxKZhzgwhZdo3yqjloBrnnqj5eTpqrBM?key=rvsa2GbN568PJsi6zruZoQ", tag: "Ăn sập" },
                { name: "Phố Cổ Hà Nội", desc: "Bún chả, phở và Cafe trứng.", img: "https://images.pexels.com/photos/1146760/pexels-photo-1146760.jpeg?auto=compress&cs=tinysrgb&w=400", tag: "Đặc sản" }
            ],
            culture: [
                { name: "Cố đô Huế", desc: "Vẻ đẹp cung đình xưa cũ.", img: "https://kinhtevadubao.vn/stores/news_dataimages/kinhtevadubaovn/092018/18/14/1537170510-news-1243820210326195207.3736490.jpg?randTime=1774177998", tag: "Di sản" },
                { name: "Chùa Tam Chúc", desc: "Quần thể chùa lớn nhất thế giới.", img: "https://bizweb.dktcdn.net/100/474/438/products/chua-tam-chuc.jpg?v=1716283364077", tag: "Tâm linh" }
            ],
            nature: [
                { name: "Vịnh Hạ Long", desc: "Kỳ quan thiên nhiên thế giới.", img: "https://hnm.1cdn.vn/2024/01/12/images1283642_2.jpg", tag: "Kỳ quan" },
                { name: "Rừng Cúc Phương", desc: "Khám phá hệ sinh thái rừng già.", img: "https://ticotravel.com.vn/wp-content/uploads/2022/05/vuon-quoc-gia-cuc-phuong-9.jpg", tag: "Rừng" }
            ]
        };

        function filterMood(mood) {
            document.querySelectorAll('.mood-btn').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.querySelector(`[data-mood="${mood}"]`);
            if (activeBtn) activeBtn.classList.add('active');

            const container = document.getElementById('mood-suggestions');
            container.innerHTML = moodData[mood].map(item => `
                <div class="mood-card group cursor-pointer">
                    <div class="relative overflow-hidden rounded-2xl aspect-video mb-2 shadow-sm border border-slate-100 bg-slate-100">
                        <img src="${item.img}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" onerror="this.src='https://ui-avatars.com/api/?name=Trip';">
                        <div class="absolute top-1.5 left-1.5"><span class="px-1.5 py-0.5 bg-black/30 backdrop-blur-md text-white text-[7px] font-black uppercase rounded-md border border-white/20">${item.tag}</span></div>
                    </div>
                    <h4 class="text-[10px] font-black text-slate-800 group-hover:text-indigo-600 transition truncate">${item.name}</h4>
                </div>`).join('');
        }

        // Logic AJAX Like/Comment
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
                if(!input.value.trim()) return;
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

        // Hàm bật/tắt Modal Premium
        function showPremiumModal() {
            document.getElementById('premiumModal').classList.remove('hidden');
        }
        function closePremiumModal() {
            document.getElementById('premiumModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => filterMood('chill'));
    </script>
</x-app-layout>
