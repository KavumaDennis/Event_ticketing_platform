<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrendCommentLike extends Model
{
    use HasFactory;

    protected $table = 'comment_likes'; // your table for comment likes
    protected $fillable = ['user_id', 'trend_comment_id'];

    // Relationship to the comment
    public function comment()
    {
        return $this->belongsTo(TrendComment::class, 'trend_comment_id');
    }

    // Relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
