<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

// Trang chủ: Điều hướng dựa trên trạng thái đăng nhập
Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});

// Bảng tin chính (Dashboard)
Route::get('/dashboard', function () {
    $posts = Post::with(['user', 'media', 'comments.user'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->get();

    $notifications = auth()->user()->notifications;

    return view('dashboard', compact('posts', 'notifications'));
})->middleware(['auth', 'verified'])->name('dashboard');

// API lấy thông báo mới nhất (Real-time Long Polling)
Route::get('/api/notifications/latest', function () {
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications->count();
    $latestNotifications = $user->notifications()->take(15)->get();

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

    // --- Quản lý Bài viết & Tương tác ---
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

    // Thêm vào trong nhóm Route::middleware('auth')->group(function () { ... })
    Route::get('/profile/{user:name}', function (\App\Models\User $user) {
    $posts = $user->posts()->with(['user', 'media', 'comments.user'])
                ->withCount(['likes', 'comments'])
                ->latest()
                ->get();

    return view('profile.show', compact('user', 'posts'));
    })->name('user.profile');

    // --- Hệ thống Thông báo ---

    // Đánh dấu tất cả là đã đọc
    Route::get('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAsRead');

    // Xử lý click vào một thông báo cụ thể (Đã sửa logic chuyển hướng)
    Route::get('/notifications/{id}/go', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // 1. Đánh dấu thông báo này là đã đọc
        $notification->markAsRead();

        // 2. Lấy ID bài viết từ dữ liệu thông báo
        $postId = $notification->data['post_id'] ?? null;

        // 3. Chuyển hướng
        if ($postId) {
            // Ép kiểu URL để nhảy đến đúng ID bài viết trên trang Dashboard
            // Ví dụ kết quả: http://127.0.0.1:8000/dashboard#post-5
            return redirect()->to(route('dashboard') . '#post-' . $postId);
        }

        return redirect()->route('dashboard');
    })->name('notifications.go');

        // Avatar
        Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
});

require __DIR__.'/auth.php';
