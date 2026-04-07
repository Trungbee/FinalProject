<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Đường dẫn chuẩn Laravel 11

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_premium',
        'premium_tier',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Quan hệ để lấy bài viết, like, comment
    public function posts() { return $this->hasMany(Post::class); }
    public function interactions() { return $this->hasMany(Interaction::class); }
    public function comments() { return $this->hasMany(Comment::class); }

    // Những người mà User này đang theo dõi (Đang theo dõi)
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'followed_id')->withTimestamps();
    }

    // Những người đang theo dõi User này (Người theo dõi)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'followed_id', 'follower_id')->withTimestamps();
    }

    // Hàm kiểm tra xem có đang theo dõi 1 user cụ thể không (Dùng để hiện nút xanh/đỏ)
    public function isFollowing(User $user)
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    // Các tin nhắn User này gửi đi
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Các tin nhắn User này nhận được
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
