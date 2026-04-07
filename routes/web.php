<?php

// KHAI BÁO ĐỊA CHỈ MỚI CỦA CÁC CONTROLLER Ở ĐÂY CHO GỌN
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\PostController;
use App\Http\Controllers\Customer\FollowController;
use App\Http\Controllers\Customer\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Trang chủ: Điều hướng dựa trên trạng thái đăng nhập
Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});

// Bảng tin chính (Dashboard)
Route::get('/dashboard', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// API lấy thông báo mới nhất (Real-time Long Polling)
Route::get('/api/notifications/latest', function () {
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications->count();
    $latestNotifications = $user->notifications()->take(15)->get();

    // Bơm thêm link Avatar thật vào dữ liệu trả về cho JS
    $latestNotifications->transform(function ($notification) {
        $triggerUserId = $notification->data['user_id'] ?? null;
        if ($triggerUserId) {
            $triggerUser = \App\Models\User::find($triggerUserId);
        } else {
            $triggerUser = \App\Models\User::where('name', $notification->data['user_name'] ?? '')->first();
        }

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

// Nhóm các Route yêu cầu đăng nhập
Route::middleware('auth')->group(function () {

    // --- Quản lý Profile ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');

    // --- Quản lý Bài viết & Tương tác ---
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // --- Trang cá nhân công khai ---
    Route::get('/profile/{user:name}', function (\App\Models\User $user) {
        $posts = $user->posts()->with(['user', 'media', 'comments.user'])
                    ->withCount(['likes', 'comments'])
                    ->latest()
                    ->get();

        return view('profile.show', compact('user', 'posts'));
    })->name('user.profile');

    // --- Hệ thống Thông báo ---
    Route::get('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAsRead');

    Route::get('/notifications/{id}/go', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $postId = $notification->data['post_id'] ?? null;
        $triggerUserName = $notification->data['user_name'] ?? null;

        if ($postId) {
            return redirect()->to(route('dashboard') . '#post-' . $postId);
        }

        if (($notification->data['type'] ?? '') == 'follow' && $triggerUserName) {
            return redirect()->route('user.profile', $triggerUserName);
        }

        return redirect()->route('dashboard');
    })->name('notifications.go');

    // --- Tính năng Follow ---
    Route::post('/users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');

    // --- Tính năng Chat (API endpoints) ---
    Route::get('/api/chat/check', [ChatController::class, 'checkUnread']);
    Route::get('/api/chat/contacts', [ChatController::class, 'contacts']);
    Route::get('/api/chat/{user}/messages', [ChatController::class, 'fetchMessages']);
    Route::post('/api/chat/{user}/send', [ChatController::class, 'sendMessage']);

    // --- TÍNH NĂNG PREMIUM (3 GÓI) ---
    Route::get('/premium', function () {
        return view('premium.index');
    })->name('premium.index');

    Route::post('/premium/upgrade/{tier}', function ($tier) {
        $validTiers = ['silver', 'gold', 'diamond'];
        if (!in_array($tier, $validTiers)) {
            abort(400, 'Gói không hợp lệ');
        }

        $user = auth()->user();
        $user->premium_tier = $tier;
        $user->save();

        return redirect()->route('dashboard')->with('success', '🎉 Nâng cấp gói ' . strtoupper($tier) . ' thành công!');
    })->name('premium.upgrade');

    // --- TÍNH NĂNG BOOKING (CHỈ DÀNH CHO GOLD & DIAMOND) ---
    Route::get('/booking', function () {
        $tier = auth()->user()->premium_tier ?? 'none';

        // Kiểm tra nếu không phải Vàng hoặc Kim Cương
        if (!in_array($tier, ['gold', 'diamond'])) {
            return redirect()->route('premium.index')->with('error', 'Tính năng Booking chỉ dành cho hội viên Vàng 🥇 hoặc Kim Cương 💎. Vui lòng nâng cấp để sử dụng!');
        }

        // Nếu hợp lệ thì cho phép vào trang booking (Bạn sẽ cần tạo file view booking/index.blade.php sau)
        return view('booking.index');
    })->name('booking.index');
});

require __DIR__.'/auth.php';
