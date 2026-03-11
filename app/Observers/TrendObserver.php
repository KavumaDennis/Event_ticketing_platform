<?php

namespace App\Observers;

use App\Models\Trend;

class TrendObserver
{
    /**
     * Handle the Trend "created" event.
     */
    public function created(Trend $trend): void
    {
        $user = $trend->user;
        
        // Get all followers
        // The 'followers' relationship on User returns UserFollow models.
        $followers = $user->followers()->with('follower')->get();
        
        $notificationService = new \App\Services\NotificationService();
        
        foreach ($followers as $follow) {
            $followerUser = $follow->follower;
            
            if ($followerUser) {
                $notificationService->send(
                    $followerUser,
                    "New Post from {$user->first_name}",
                    "{$user->first_name} just posted a new trend: '{$trend->title}'",
                    "info",
                    $trend,
                    route('trends.show', $trend->id)
                );
            }
        }
    }

    /**
     * Handle the Trend "updated" event.
     */
    public function updated(Trend $trend): void
    {
        //
    }

    /**
     * Handle the Trend "deleted" event.
     */
    public function deleted(Trend $trend): void
    {
        //
    }

    /**
     * Handle the Trend "restored" event.
     */
    public function restored(Trend $trend): void
    {
        //
    }

    /**
     * Handle the Trend "force deleted" event.
     */
    public function forceDeleted(Trend $trend): void
    {
        //
    }
}
