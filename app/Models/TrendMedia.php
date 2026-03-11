<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'trend_id',
        'path',
        'type',
        'order',
    ];

    public function trend()
    {
        return $this->belongsTo(Trend::class);
    }

    public function getIsVideoAttribute()
    {
        return $this->type === 'video';
    }
}
