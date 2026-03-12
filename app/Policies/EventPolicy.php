<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        if (!$event->organizer) {
            return false;
        }

        if ($event->organizer->user_id === $user->id) {
            return true;
        }

        return $event->organizer->hasRole($user, ['owner', 'editor']);
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        if (!$event->organizer) {
            return false;
        }

        if ($event->organizer->user_id === $user->id) {
            return true;
        }

        return $event->organizer->hasRole($user, ['owner', 'editor']);
    }
}
