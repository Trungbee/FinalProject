<?php

namespace App\Http\Controllers\Customer;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    // 1. Lấy danh sách liên hệ (Tất cả user trừ bản thân)
    public function contacts()
    {
        $users = User::where('id', '!=', auth()->id())->get();

        $contacts = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'avatar_url' => $user->avatar
                    ? asset('storage/' . $user->avatar)
                    : "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=random&color=fff"
            ];
        });

        return response()->json($contacts);
    }

    // 2. Lấy lịch sử tin nhắn với 1 user cụ thể (Cập nhật trạng thái đã đọc)
    public function fetchMessages(User $user)
    {
        // Đánh dấu các tin nhắn người kia gửi cho mình là "Đã đọc" khi mình mở khung chat
        Message::where('sender_id', $user->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // THÀNH PHẦN QUAN TRỌNG: Radar quét tin nhắn chưa đọc và lấy nội dung mới nhất
    public function checkUnread()
    {
        $unreadMessages = Message::where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $latest = $unreadMessages->first();
        $latestData = null;

        if ($latest) {
            $sender = $latest->sender;
            $latestData = [
                'id' => $latest->id,
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'content' => $latest->content, // THÊM DÒNG NÀY ĐỂ HIỂN THỊ TRÊN DROP_DOWN
                'sender_avatar' => $sender->avatar
                    ? asset('storage/' . $sender->avatar)
                    : "https://ui-avatars.com/api/?name=" . urlencode($sender->name) . "&background=random&color=fff",
            ];
        }

        return response()->json([
            'unread_count' => $unreadMessages->count(),
            'latest_message' => $latestData
        ]);
    }

    // 3. Gửi tin nhắn mới
    public function sendMessage(Request $request, User $user)
    {
        $request->validate(['content' => 'required|string']);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $user->id,
            'content' => $request->content
        ]);

        return response()->json($message);
    }
}
