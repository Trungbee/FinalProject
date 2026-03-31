<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'file_url', 'type'];

    // Ảnh/Video này thuộc về MỘT bài viết cụ thể
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
