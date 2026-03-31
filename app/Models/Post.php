<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'content', 'location_name', 'privacy'];

    public function user() { return $this->belongsTo(User::class); }
    public function media() { return $this->hasMany(Media::class); }

    // THÊM QUAN HỆ LIKE & COMMENT
    public function likes() { return $this->hasMany(Interaction::class); }
    public function comments() { return $this->hasMany(Comment::class)->latest(); }
}
