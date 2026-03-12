<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'user_id',
        'role',
    ];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
