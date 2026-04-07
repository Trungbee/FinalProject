<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Media;
use App\Models\Interaction;
use App\Models\Comment;
use App\Notifications\PostCommented;

class PostController extends Controller
{
    // BƯỚC 3: Eager Loading - Lấy danh sách bài viết kèm toàn bộ Avatar
    public function index()
    {
        $posts = Post::with(['user', 'media', 'comments.user'])
            ->withCount(['likes', 'comments']) // Đếm số Like và Comment để hiển thị ở view
            ->latest()
            ->get();

        $notifications = auth()->user()->notifications; // Lấy thông báo theo logic cũ của bạn

        return view('dashboard', compact('posts', 'notifications'));
    }

    public function store(Request $request) {
        $request->validate([
            'content' => 'required|string|max:2000',
            'location_name' => 'nullable|string|max:255', // Nhận tên địa điểm
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'location_name' => $request->location_name, // Lưu địa điểm vào DB
            'privacy' => 'PUBLIC'
        ]);

        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $path = $file->store('posts/media', 'public');
                Media::create(['post_id' => $post->id, 'file_url' => '/storage/' . $path, 'type' => 'IMAGE']);
            }
        }
        return back();
    }

    public function like(Post $post) {
        $user = auth()->user();
        $like = Interaction::where('post_id', $post->id)->where('user_id', $user->id)->first();
        $isLiked = false;

        if ($like) {
            $like->delete();
        } else {
            Interaction::create(['post_id' => $post->id, 'user_id' => $user->id, 'type' => 'LIKE']);
            $isLiked = true;

            // CHỈ GỬI THÔNG BÁO NẾU NGƯỜI LIKE KHÔNG PHẢI CHỦ BÀI VIẾT
            if ($post->user_id !== $user->id) {
                // Dòng này sẽ ghi dữ liệu vào bảng notifications trong Postgres
                $post->user->notify(new \App\Notifications\PostLiked($user, $post));
            }
        }

        return response()->json(['likes_count' => $post->likes()->count(), 'is_liked' => $isLiked]);
    }

    public function comment(Request $request, Post $post) {
        $user = auth()->user();

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => $request->content
        ]);

        // GỬI THÔNG BÁO CHO CHỦ BÀI VIẾT (Nếu không phải chính mình tự comment)
        if ($post->user_id !== $user->id) {
            $post->user->notify(new PostCommented($user, $post, $request->content));
        }

        // BƯỚC 2: Kiểm tra và xuất Avatar thật cho AJAX
        $avatarUrl = $user->avatar
            ? asset('storage/' . $user->avatar)
            : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=random";

        // Render HTML khớp 100% với file post-card.blade.php
        $html = '
            <div class="flex items-start space-x-3 text-sm">
                <a href="'.route('user.profile', $user->name).'" class="shrink-0">
                    <img src="'.$avatarUrl.'" class="w-8 h-8 rounded-xl shadow-sm border border-white object-cover hover:opacity-80 transition">
                </a>
                <div class="bg-white px-4 py-2 rounded-2xl border border-slate-100 shadow-sm flex-1">
                    <a href="'.route('user.profile', $user->name).'" class="font-bold text-slate-800 text-xs hover:text-indigo-600 transition">'.$user->name.'</a>
                    <p class="text-slate-600 mt-0.5 leading-snug font-medium">'.e($comment->content).'</p>
                </div>
            </div>
        ';

        return response()->json([
            'html' => $html,
            'comments_count' => $post->comments()->count()
        ]);
    }
}
