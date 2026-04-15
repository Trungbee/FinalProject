<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="max-w-3xl mx-auto mb-8 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl text-center font-bold shadow-sm animate-bounce">
                    <span class="block sm:inline">✨ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-3xl mx-auto mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl text-center font-bold shadow-sm">
                    <span class="block sm:inline">⚠️ {{ session('error') }}</span>
                </div>
            @endif

            <div class="mb-10 flex flex-col md:flex-row items-center justify-between bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">
                        Đặc quyền VIP <span class="text-2xl">✨</span>
                    </h2>
                    <p class="mt-2 text-slate-500 font-medium">Chào mừng <span class="text-indigo-600 font-bold">{{ auth()->user()->name }}</span>, hành trình mới đang chờ bạn!</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-2 bg-amber-50 text-amber-600 px-4 py-2 rounded-xl border border-amber-100 font-bold text-sm shadow-sm">
                    <span>👑</span>
                    <span class="uppercase tracking-widest">{{ auth()->user()->premium_tier }} Member</span>
                </div>
            </div>

            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="mb-10 bg-white p-4 rounded-3xl shadow-lg border border-slate-100 flex flex-col md:flex-row gap-4 transition-all duration-500">
                @csrf
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">📍</span>
                    <input type="text" name="destination" id="destinationInput" required placeholder="Bạn muốn đi đâu?"
                           class="w-full border-none pl-12 pr-4 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 transition-all">
                </div>
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">📅</span>
                    <input type="date" name="check_in_date" required
                           class="w-full border-none pl-12 pr-4 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700">
                </div>
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">👥</span>
                    <select name="guests" required
                            class="w-full border-none pl-12 pr-4 py-4 bg-slate-50 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 appearance-none">
                        <option value="1">1 Người</option>
                        <option value="2" selected>2 Người</option>
                        <option value="3">3 Người</option>
                        <option value="4">Gia đình (4+ người)</option>
                    </select>
                </div>
                <button type="submit" class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-2xl transition shadow-lg shadow-indigo-500/30 whitespace-nowrap active:scale-95">
                    Xác nhận Đặt
                </button>
            </form>

            @if(isset($myBookings) && $myBookings->count() > 0)
            <div class="mb-12">
                <div class="flex items-center space-x-3 mb-6">
                    <span class="text-2xl">🧳</span>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Chuyến đi của bạn</h3>
                </div>
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-widest border-b border-slate-100">
                                    <th class="p-5 font-bold">Điểm đến</th>
                                    <th class="p-5 font-bold">Ngày đi</th>
                                    <th class="p-5 font-bold">Số lượng</th>
                                    <th class="p-5 font-bold">Trạng thái</th>
                                    <th class="p-5 font-bold text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($myBookings as $booking)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                                    <td class="p-5 font-black text-slate-800">{{ $booking->destination }}</td>
                                    <td class="p-5 font-medium text-slate-600">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d/m/Y') }}</td>
                                    <td class="p-5 font-medium text-slate-600">{{ $booking->guests }} người</td>
                                    <td class="p-5">
                                        @if($booking->status == 'pending')
                                            <span class="bg-amber-100 text-amber-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Đang chờ xử lý</span>
                                        @else
                                            <span class="bg-green-100 text-green-700 text-[10px] font-black px-3 py-1 rounded-full uppercase">Đã xác nhận</span>
                                        @endif
                                    </td>
                                    <td class="p-5 text-center">
                                        @if($booking->status == 'pending')
                                            <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy yêu cầu đặt chỗ này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold transition hover:underline underline-offset-4">
                                                    Hủy bỏ
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-slate-300 text-xs italic">Không thể hủy</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">🔥 Gợi ý dành riêng cho bạn</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div onclick="selectDestination('Vinpearl Luxury Nha Trang')"
                     class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group cursor-pointer hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.pexels.com/photos/164041/pexels-photo-164041.jpeg?auto=compress&cs=tinysrgb&w=800"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-md">Giảm 30%</div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition">Vinpearl Luxury Nha Trang</h4>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Nghỉ dưỡng đẳng cấp 5 sao với bãi biển riêng tư.</p>
                        <div class="text-xl font-black text-slate-800">2.450.000đ<span class="text-xs text-slate-500 font-medium">/đêm</span></div>
                    </div>
                </div>

                <div onclick="selectDestination('Topas Ecolodge Sapa')"
                     class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group cursor-pointer hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.pexels.com/photos/1134166/pexels-photo-1134166.jpeg?auto=compress&cs=tinysrgb&w=800"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 left-4 bg-amber-500 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-md">Hot Deal</div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition">Topas Ecolodge Sapa</h4>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Hòa mình vào thiên nhiên hùng vĩ với hồ bơi vô cực.</p>
                        <div class="text-xl font-black text-slate-800">4.100.000đ<span class="text-xs text-slate-500 font-medium">/đêm</span></div>
                    </div>
                </div>

                <div onclick="selectDestination('Du thuyền 5 sao Stellar Hạ Long')"
                     class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group cursor-pointer hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <div class="relative h-48 overflow-hidden">
                        <img src="https://images.pexels.com/photos/3225531/pexels-photo-3225531.jpeg?auto=compress&cs=tinysrgb&w=800"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute top-4 right-4 bg-indigo-500 text-white p-2 rounded-full shadow-lg">✨</div>
                    </div>
                    <div class="p-6">
                        <h4 class="text-lg font-black text-slate-800 mb-2 group-hover:text-indigo-600 transition">Du thuyền 5 sao Stellar</h4>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">Khám phá kỳ quan thế giới với phong cách thượng lưu.</p>
                        <div class="text-xl font-black text-slate-800">6.800.000đ<span class="text-xs text-slate-500 font-medium">/tour</span></div>
                    </div>
                </div>
            </div>

            <p class="text-[10px] text-center text-slate-400 mt-12 italic">Cảm ơn bạn đã tin tưởng dịch vụ cao cấp của TravelX.</p>
        </div>
    </div>

    <script>
        function selectDestination(name) {
            const input = document.getElementById('destinationInput');
            const form = document.getElementById('bookingForm');

            // 1. Điền tên vào input
            input.value = name;

            // 2. Hiệu ứng focus và làm nổi bật form
            input.focus();
            form.classList.add('ring-4', 'ring-indigo-200');

            // 3. Cuộn mượt mà lên đầu trang
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // 4. Xóa hiệu ứng nổi bật sau 1.5 giây
            setTimeout(() => {
                form.classList.remove('ring-4', 'ring-indigo-200');
            }, 1500);
        }
    </script>
</x-app-layout>
