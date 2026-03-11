<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceView extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_id',
        'user_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function viewer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
