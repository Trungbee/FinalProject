<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- HEADER -->
            <div class="mb-10 flex flex-col md:flex-row items-center justify-between bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">
                        Đặc quyền VIP <span class="text-2xl">✨</span>
                    </h2>
                    <p class="mt-2 text-slate-500 font-medium">Chào mừng <span class="text-indigo-600 font-bold">{{ auth()->user()->name }}</span>, bạn muốn đặt chân đến đâu hôm nay?</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-2 bg-amber-50 text-amber-600 px-4 py-2 rounded-xl border border-amber-100 font-bold text-sm">
                    <span>👑</span>
                    <span class="uppercase tracking-widest">{{ auth()->user()->premium_tier }} Member</span>
                </div>
            </div>

            <!-- THANH TÌM KIẾM -->
            <div class="mb-10 bg-white p-4 rounded-3xl shadow-lg border border-slate-100 flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">📍</span>
                    <input type="text" placeholder="Bạn muốn đi đâu?" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700">
                </div>
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">📅</span>
                    <input type="date" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700">
                </div>
                <div class="flex-1 relative">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400">👥</span>
                    <select class="w-full pl-12 pr-4 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-slate-700 appearance-none">
                        <option>1 Người lớn, 0 Trẻ em</option>
                        <option>2 Người lớn, 1 Trẻ em</option>
                        <option>Gia đình (4+ người)</option>
                    </select>
                </div>
                <button class="px-8 py-4 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-black rounded-2xl transition shadow-lg shadow-indigo-500/30 whitespace-nowrap">
                    Tìm Kiếm
                </button>
            </div>

            <!-- GỢI Ý ĐẶT PHÒNG / TOUR DÀNH RIÊNG CHO VIP -->
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">🔥 Ưu đãi độc quyền cho bạn</h3>
                <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">Xem tất cả &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Card 1 -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition duration-300">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/164041/pexels-photo-164041.jpeg?auto=compress&cs=tinysrgb&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Resort">
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-md">Giảm 30%</div>
                        <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm p-2 rounded-xl shadow-md flex items-center gap-1">
                            <span class="text-amber-500 text-sm">★</span>
                            <span class="font-bold text-slate-800 text-xs">4.9</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Nha Trang, Việt Nam</div>
                        <h4 class="text-lg font-black text-slate-800 mb-2 line-clamp-1">Vinpearl Luxury Nha Trang</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">Trải nghiệm nghỉ dưỡng đẳng cấp 5 sao với bãi biển riêng và villa hồ bơi sang trọng.</p>
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-xs text-slate-400 line-through block">3.500.000đ/đêm</span>
                                <span class="text-xl font-black text-slate-800">2.450.000đ<span class="text-xs text-slate-500 font-medium">/đêm</span></span>
                            </div>
                            <button class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition">Đặt Ngay</button>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition duration-300">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/1134166/pexels-photo-1134166.jpeg?auto=compress&cs=tinysrgb&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Sapa">
                        <div class="absolute top-4 left-4 bg-amber-500 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-md">Hot Deal</div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Sapa, Lào Cai</div>
                        <h4 class="text-lg font-black text-slate-800 mb-2 line-clamp-1">Topas Ecolodge Sapa</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">Hòa mình vào thiên nhiên với hồ bơi vô cực ngắm nhìn toàn cảnh thung lũng Mường Hoa.</p>
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-xs text-slate-400 line-through block">5.200.000đ/đêm</span>
                                <span class="text-xl font-black text-slate-800">4.100.000đ<span class="text-xs text-slate-500 font-medium">/đêm</span></span>
                            </div>
                            <button class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition">Đặt Ngay</button>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group hover:shadow-xl transition duration-300">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.pexels.com/photos/3225531/pexels-photo-3225531.jpeg?auto=compress&cs=tinysrgb&w=800" class="w-full h-full object-cover group-hover:scale-110 transition duration-700" alt="Cruise">
                        <div class="absolute top-4 left-4 bg-purple-500 text-white text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-widest shadow-md">Gói Trải Nghiệm</div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Vịnh Hạ Long</div>
                        <h4 class="text-lg font-black text-slate-800 mb-2 line-clamp-1">Du thuyền 5 sao Stellar</h4>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4">Tour 2 ngày 1 đêm khám phá di sản thiên nhiên thế giới trên du thuyền đẳng cấp nhất.</p>
                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <span class="text-xs text-slate-400 block">Trọn gói cho 2 người</span>
                                <span class="text-xl font-black text-slate-800">6.800.000đ</span>
                            </div>
                            <button class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition">Đặt Ngay</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
