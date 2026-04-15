<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-slate-100">
                <div class="p-8 bg-slate-900 text-white text-center">
                    <h2 class="text-2xl font-black uppercase tracking-widest">Xác nhận thanh toán</h2>
                    <p class="text-slate-400 text-sm mt-2">Vui lòng chuyển khoản chính xác nội dung bên dưới</p>
                </div>

                <div class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">

                        <div class="text-center space-y-4">
                            <div class="inline-block p-4 bg-white border-2 border-dashed border-indigo-200 rounded-3xl shadow-inner">
                                @php
                                    $bankId = 'MB'; // Thay bằng ngân hàng của bạn (MB, VCB, ICB...)
                                    $accountNo = '123456789'; // Thay bằng số tài khoản của bạn
                                    $amount = ($type == 'premium') ? 99000 : 2500000; // Giả lập số tiền
                                    $content = ($type == 'premium') ? "TRAVELX PREMIUM ".auth()->id() : "BOOKING ".$id;
                                @endphp
                                <img src="https://img.vietqr.io/image/{{$bankId}}-{{$accountNo}}-compact2.png?amount={{$amount}}&addInfo={{$content}}"
                                     class="w-64 h-64 object-contain rounded-xl" alt="QR Thanh toán">
                            </div>
                            <p class="text-[10px] text-slate-400 italic">Mở ứng dụng Ngân hàng/Momo để quét mã</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ngân hàng</label>
                                <p class="text-lg font-bold text-slate-800">MB Bank (Quân Đội)</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Chủ tài khoản</label>
                                <p class="text-lg font-bold text-slate-800">LÊ TRỌNG TRUNG</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Số tiền</label>
                                <p class="text-3xl font-black text-indigo-600">{{ number_format($amount) }}đ</p>
                            </div>
                            <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                                <label class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Nội dung bắt buộc</label>
                                <p class="text-lg font-black text-amber-700 tracking-wider">{{ $content }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col md:flex-row gap-4">
                        <a href="{{ route('dashboard') }}" class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-2xl text-center transition">
                            Quay lại bảng tin
                        </a>
                        <button onclick="alert('Hệ thống đang kiểm tra giao dịch của bạn. Vui lòng đợi trong giây lát!')"
                                class="flex-[2] py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl text-center shadow-lg shadow-indigo-200 transition">
                            Tôi đã chuyển khoản thành công
                        </button>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 text-center">
                    <p class="text-[11px] text-slate-400">Hệ thống sẽ tự động cập nhật sau 1-5 phút khi nhận được tiền.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
