<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        "user_id",
        "business_name",
        'business_email',
        'business_website',
        'organizer_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id'); // <-- use organizer_id
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'organizer_followers', 'organizer_id', 'user_id');
    }

     protected static function booted()
    {
        static::deleting(function ($organizer) {
            // Delete organizer image
            if ($organizer->organizer_image) {
                Storage::disk('public')->delete($organizer->organizer_image);
            }

            // Delete all events and their images
            foreach ($organizer->events as $event) {
                if ($event->event_image) {
                    Storage::disk('public')->delete($event->event_image);
                }
                $event->delete();
            }
        });
    }

}
