<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    protected $follower;

    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->follower->name . ' đã bắt đầu theo dõi bạn.',
            'user_name' => $this->follower->name,
            'user_id' => $this->follower->id,
            'type' => 'follow' // Type quan trọng để phân biệt icon
        ];
    }
}
