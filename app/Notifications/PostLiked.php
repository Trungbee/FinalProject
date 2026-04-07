<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    use Queueable;

    protected $user_who_liked;
    protected $post;

    public function __construct($user_who_liked, $post)
    {
        $this->user_who_liked = $user_who_liked;
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->user_who_liked->name . ' đã thích bài viết của bạn.',
            'post_id' => $this->post->id,
            'user_name' => $this->user_who_liked->name,
            'user_id' => $this->user_who_liked->id,
            'type' => 'like'
        ];
    }
}
