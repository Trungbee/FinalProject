<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Media;
use App\Models\Interaction;
use App\Models\Comment;
use App\Notifications\PostCommented;

class PostController extends Controller
{
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

    return response()->json([
        'html' => '<div class="flex items-start space-x-2 text-sm">
                    <img src="https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random" class="w-7 h-7 rounded-full">
                    <div class="bg-white p-2 rounded-xl border border-gray-100 flex-1">
                        <span class="font-bold text-gray-900 mr-2">'.$user->name.'</span>
                        <span class="text-gray-700">'.e($comment->content).'</span>
                    </div>
                  </div>',
        'comments_count' => $post->comments()->count()
    ]);
}
}
