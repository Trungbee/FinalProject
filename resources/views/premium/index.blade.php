<x-app-layout>
    <div class="py-16 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="max-w-3xl mx-auto mb-8 bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl text-center font-bold shadow-sm animate-bounce" role="alert">
                    <span class="block sm:inline">⚠️ {{ session('error') }}</span>
                </div>
            @endif

            <div class="text-center mb-16">
                <h2 class="text-4xl font-black text-slate-800 tracking-tight">Trở thành VIP trên <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">TravelX</span></h2>
                <p class="mt-4 text-lg text-slate-500 font-medium">Chọn gói hội viên phù hợp với hành trình của bạn.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

                <div class="bg-white rounded-[2rem] shadow-lg p-8 border border-slate-200 relative transform transition hover:-translate-y-2">
                    <div class="text-center">
                        <span class="text-5xl mb-4 block">🥈</span>
                        <h3 class="text-xl font-black text-slate-500 uppercase tracking-widest">Gói Bạc</h3>
                        <div class="mt-4 mb-6">
                            <span class="text-4xl font-black text-slate-800">49k</span>
                            <span class="text-slate-400 font-bold">/tháng</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm font-bold text-slate-600">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Huy hiệu Bạc cạnh tên</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✔</span> Bỏ qua quảng cáo popup</li>
                        <li class="flex items-center text-slate-400"><span class="text-slate-300 mr-2">✖</span> Mở khóa tính năng: Booking</li>
                        <li class="flex items-center text-slate-400"><span class="text-slate-300 mr-2">✖</span> Tải xuống hình ảnh/video chất lượng 4K</li>
                    </ul>

                    @if(auth()->user()->premium_tier == 'silver')
                        <button class="w-full py-3 bg-indigo-600 text-white cursor-not-allowed font-black rounded-xl uppercase tracking-widest transition" disabled>
                            Đang sử dụng
                        </button>
                    @else
                        <a href="{{ route('checkout.index', ['type' => 'premium', 'id' => 'silver']) }}" class="block w-full py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-center font-black rounded-xl uppercase tracking-widest transition">
                            Chọn Gói Bạc
                        </a>
                    @endif
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-2xl p-10 border-2 border-yellow-400 relative transform md:scale-105 z-10">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-gradient-to-r from-yellow-400 to-amber-600 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-md">Khuyên Dùng</span>
                    </div>
                    <div class="text-center">
                        <span class="text-6xl mb-4 block">🥇</span>
                        <h3 class="text-2xl font-black text-amber-500 uppercase tracking-widest">Gói Vàng</h3>
                        <div class="mt-4 mb-6">
                            <span class="text-5xl font-black text-slate-800">99k</span>
                            <span class="text-slate-400 font-bold">/tháng</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm font-bold text-slate-700">
                        <li class="flex items-center"><span class="text-amber-500 mr-2">✔</span> Huy hiệu Vàng nổi bật</li>
                        <li class="flex items-center"><span class="text-amber-500 mr-2">✔</span> Xóa sạch 100% quảng cáo</li>
                        <li class="flex items-center"><span class="text-amber-500 mr-2">✔</span> Mở khóa tính năng: Booking</li>
                        <li class="flex items-center text-slate-400"><span class="text-slate-300 mr-2">✖</span> Tải xuống hình ảnh/video chất lượng 4K</li>
                    </ul>

                    @if(auth()->user()->premium_tier == 'gold')
                        <button class="w-full py-4 bg-indigo-600 text-white cursor-not-allowed font-black rounded-xl uppercase tracking-widest transition shadow-lg" disabled>
                            Đang sử dụng
                        </button>
                    @else
                        <a href="{{ route('checkout.index', ['type' => 'premium', 'id' => 'gold']) }}" class="block w-full py-4 bg-gradient-to-r from-yellow-400 to-amber-600 hover:from-yellow-500 hover:to-amber-700 text-white text-center font-black rounded-xl uppercase tracking-widest transition shadow-lg shadow-amber-500/40">
                            Chọn Gói Vàng
                        </a>
                    @endif
                </div>

                <div class="bg-slate-900 rounded-[2rem] shadow-lg p-8 border border-slate-700 relative transform transition hover:-translate-y-2">
                    <div class="text-center">
                        <span class="text-5xl mb-4 block">💎</span>
                        <h3 class="text-xl font-black text-cyan-400 uppercase tracking-widest">Kim Cương</h3>
                        <div class="mt-4 mb-6">
                            <span class="text-4xl font-black text-white">199k</span>
                            <span class="text-slate-500 font-bold">/tháng</span>
                        </div>
                    </div>
                    <ul class="space-y-4 mb-8 text-sm font-bold text-slate-300">
                        <li class="flex items-center"><span class="text-cyan-400 mr-2">✔</span> Huy hiệu Kim Cương siêu VIP</li>
                        <li class="flex items-center"><span class="text-cyan-400 mr-2">✔</span> Không quảng cáo 100%</li>
                        <li class="flex items-center"><span class="text-cyan-400 mr-2">✔</span> Mở khóa tính năng: Booking </li>
                        <li class="flex items-center"><span class="text-cyan-400 mr-2">✔</span> Tải xuống hình ảnh/video chất lượng 4K</li>
                    </ul>

                    @if(auth()->user()->premium_tier == 'diamond')
                        <button class="w-full py-3 bg-indigo-600 text-white cursor-not-allowed font-black rounded-xl uppercase tracking-widest transition shadow-lg" disabled>
                            Đang sử dụng
                        </button>
                    @else
                        <a href="{{ route('checkout.index', ['type' => 'premium', 'id' => 'diamond']) }}" class="block w-full py-3 bg-gradient-to-r from-cyan-400 to-blue-600 hover:from-cyan-500 hover:to-blue-700 text-white text-center font-black rounded-xl uppercase tracking-widest transition shadow-lg shadow-cyan-500/30">
                            Chọn Kim Cương
                        </a>
                    @endif
                </div>

            </div>

            <p class="text-[10px] text-center text-slate-400 mt-12 italic">*Đây là môi trường thử nghiệm thanh toán chuyển khoản.</p>
        </div>
    </div>
</x-app-layout>
