<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = [
        'order_id',
        'user_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function response()
    {
        return $this->hasOne(FeedbackResponse::class);
    }

    public function hasResponse()
    {
        return $this->response()->exists();
    }

    public function getRatingStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingColorAttribute()
    {
        if ($this->rating >= 4) return 'success';
        if ($this->rating >= 3) return 'warning';
        return 'danger';
    }
}
