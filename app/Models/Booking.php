<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'destination', 'check_in_date', 'check_out_date', 'guests', 'total_price', 'status'
    ];

    // Mối quan hệ: Mỗi booking thuộc về 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
