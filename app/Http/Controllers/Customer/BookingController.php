<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // Hiển thị trang Booking (Có kiểm tra VIP)
    public function index()
    {
        $tier = auth()->user()->premium_tier ?? 'none';

        // Chặn user thường ngay tại Controller
        if (!in_array($tier, ['gold', 'diamond'])) {
            return redirect()->route('premium.index')->with('error', 'Tính năng Booking chỉ dành cho hội viên Vàng 🥇 hoặc Kim Cương 💎. Vui lòng nâng cấp!');
        }

        // Lấy lịch sử đặt phòng của user này
        $myBookings = Booking::where('user_id', auth()->id())->latest()->get();

        return view('booking.index', compact('myBookings'));
    }

    // Xử lý lưu thông tin đặt phòng
    public function store(Request $request)
    {
        // Validate dữ liệu từ form
        $request->validate([
            'destination' => 'required|string|max:255',
            'check_in_date' => 'required|date|after_or_equal:today',
            'guests' => 'required|integer|min:1',
        ]);

        // Tạo booking mới
        Booking::create([
            'user_id' => auth()->id(),
            'destination' => $request->destination,
            'check_in_date' => $request->check_in_date,
            'guests' => $request->guests,
            'total_price' => rand(2000000, 10000000), // Tạm thời random giá tiền từ 2-10 triệu
            'status' => 'pending' // Chờ xác nhận
        ]);

        return redirect()->back()->with('success', '🎉 Đặt chỗ đến ' . $request->destination . ' thành công! Chúng tôi sẽ liên hệ sớm.');
    }
    public function destroy($id)
{
    $booking = \App\Models\Booking::findOrFail($id);

    // Chỉ cho phép hủy nếu là người đặt và đang ở trạng thái pending
    if ($booking->user_id == auth()->id() && $booking->status == 'pending') {
        $booking->delete();
        return redirect()->back()->with('success', '🗑️ Đã hủy chuyến đi thành công!');
    }

    return redirect()->back()->with('error', 'Không thể hủy chuyến đi này!');
}
}
