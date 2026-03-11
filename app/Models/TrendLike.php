<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendLike extends Model
{
    use HasFactory;

    protected $fillable = ['trend_id', 'user_id'];

    public function trend()
    {
        return $this->belongsTo(Trend::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

