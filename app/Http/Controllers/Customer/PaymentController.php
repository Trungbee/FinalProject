<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PayOS\PayOS;
use App\Models\User;
use App\Models\Booking;

class PaymentController extends Controller
{
    private $payOS;

    public function __construct() {
        $this->payOS = new PayOS(env('PAYOS_CLIENT_ID'), env('PAYOS_API_KEY'), env('PAYOS_CHECKSUM_KEY'));
    }

    public function createPayment($type, $id = null) {
        $orderCode = intval(substr(strval(microtime(true) * 10000), -6)); // Tạo mã đơn hàng số
        $amount = ($type == 'premium') ? 99000 : 2500000; // Giá tiền mẫu
        $description = ($type == 'premium') ? "Nang cap Premium" : "Thanh toan Booking";

        $data = [
            "orderCode" => $orderCode,
            "amount" => $amount,
            "description" => $description,
            "returnUrl" => route('payment.success'),
            "cancelUrl" => route('payment.cancel'),
        ];

        try {
            $response = $this->payOS->createPaymentLink($data);
            return redirect($response['checkoutUrl']); // Đẩy khách sang trang thanh toán của ngân hàng
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    // WEBHOOK: Ngân hàng tự gọi vào đây khi khách chuyển tiền xong
    public function handleWebhook(Request $request) {
        $body = $request->all();

        if (str_contains($body['data']['description'], 'Premium')) {
            // TỰ ĐỘNG NÂNG CẤP PREMIUM
            $user = User::find(auth()->id()); // Bạn nên lưu user_id vào một bảng orders để tìm chính xác hơn
            $user->premium_tier = 'gold';
            $user->save();
        }

        return response()->json(['status' => 'success']);
    }
}
