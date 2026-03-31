<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    protected $user_who_commented;
    protected $post;
    protected $comment_content;

    public function __construct($user_who_commented, $post, $comment_content)
    {
        $this->user_who_commented = $user_who_commented;
        $this->post = $post;
        $this->comment_content = $comment_content;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->user_who_commented->name . ' đã bình luận về bài viết của bạn: "' . \Str::limit($this->comment_content, 20) . '"',
            'post_id' => $this->post->id,
            'user_name' => $this->user_who_commented->name,
            'type' => 'comment'
        ];
    }
}
