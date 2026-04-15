<?php

// KHAI BÁO CÁC CONTROLLER
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\PostController;
use App\Http\Controllers\Customer\FollowController;
use App\Http\Controllers\Customer\ChatController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\PaymentController; // Controller mới cho PayOS
use App\Models\User;
use Illuminate\Support\Facades\Route;

// 1. Trang chủ: Điều hướng dựa trên trạng thái đăng nhập
Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});

// 2. Bảng tin chính (Dashboard)
Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. API lấy thông báo mới nhất (Real-time Long Polling)
Route::get('/api/notifications/latest', function () {
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications->count();
    $latestNotifications = $user->notifications()->take(15)->get();

    $latestNotifications->transform(function ($notification) {
        $triggerUserId = $notification->data['user_id'] ?? null;
        $triggerUser = $triggerUserId ? User::find($triggerUserId) : User::where('name', $notification->data['user_name'] ?? '')->first();

        $notification->avatar_url = ($triggerUser && $triggerUser->avatar)
            ? asset('storage/' . $triggerUser->avatar)
            : "https://ui-avatars.com/api/?name=" . urlencode($notification->data['user_name'] ?? 'U') . "&background=random&color=fff";

        return $notification;
    });

    return response()->json([
        'unread_count' => $unreadCount,
        'notifications' => $latestNotifications,
        'latest_id' => $latestNotifications->first()?->id
    ]);
})->middleware('auth');

// --- CÁC ROUTE YÊU CẦU ĐĂNG NHẬP ---
Route::middleware('auth')->group(function () {

    // 4. HỆ THỐNG THANH TOÁN TỰ ĐỘNG (PayOS)
    // Khách chọn gói/đặt chỗ sẽ được đẩy sang trang thanh toán của ngân hàng
    Route::get('/checkout/{type}/{id?}', [PaymentController::class, 'createPayment'])->name('checkout.index');
    Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


    // 5. QUẢN LÝ BOOKING (Chỉ dành cho Gold/Diamond)
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::delete('/booking/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');


    // 6. TÍNH NĂNG PREMIUM
    Route::get('/premium', function () {
        return view('premium.index');
    })->name('premium.index');

    // (Lưu ý: Route upgrade cũ có thể giữ lại hoặc chuyển hướng sang checkout)
    Route::post('/premium/upgrade/{tier}', function ($tier) {
        return redirect()->route('checkout.index', ['type' => 'premium', 'id' => $tier]);
    })->name('premium.upgrade');


    // 7. QUẢN LÝ PROFILE & TÀI KHOẢN
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    Route::get('/profile/{user:name}', function (User $user) {
        $posts = $user->posts()->with(['user', 'media', 'comments.user'])->withCount(['likes', 'comments'])->latest()->get();
        return view('profile.show', compact('user', 'posts'));
    })->name('user.profile');


    // 8. BÀI VIẾT & TƯƠNG TÁC
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');


    // 9. THÔNG BÁO & FOLLOW
    Route::get('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAsRead');

    Route::get('/notifications/{id}/go', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        $postId = $notification->data['post_id'] ?? null;
        if ($postId) return redirect()->to(route('dashboard') . '#post-' . $postId);
        return redirect()->route('dashboard');
    })->name('notifications.go');

    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');


    // 10. API CHAT
    Route::get('/api/chat/check', [ChatController::class, 'checkUnread']);
    Route::get('/api/chat/contacts', [ChatController::class, 'contacts']);
    Route::get('/api/chat/{user}/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/api/chat/{user}/send', [ChatController::class, 'sendMessage']);
});

// 11. WEBHOOK (Xử lý xác nhận tiền từ ngân hàng - Phải để ngoài Middleware Auth)
Route::post('/payos/webhook', [PaymentController::class, 'handleWebhook']);

require __DIR__.'/auth.php';
