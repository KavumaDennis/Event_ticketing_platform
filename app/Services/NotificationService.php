<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    /**
     * Send a notification to a specific user.
     *
     * @param int|User $user The user ID or User model.
     * @param string $title The title of the notification.
     * @param string $message The body of the notification.
     * @param string $type The type of notification (info, success, warning).
     * @param Model|null $related The related model (optional).
     * @param string|null $actionUrl A direct URL (optional).
     * @return Notification
     */
    public function send($user, string $title, string $message, string $type = 'info', ?Model $related = null, ?string $actionUrl = null)
    {
        $userId = $user instanceof User ? $user->id : $user;

        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'related_id' => $related ? $related->id : null,
            'related_type' => $related ? get_class($related) : null,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Send a notification to multiple users.
     *
     * @param array|\Illuminate\Support\Collection $users Collection of User models or IDs.
     * @param string $title
     * @param string $message
     * @param string $type
     * @param Model|null $related
     * @param string|null $actionUrl
     * @return void
     */
    public function sendToMany($users, string $title, string $message, string $type = 'info', ?Model $related = null, ?string $actionUrl = null)
    {
        foreach ($users as $user) {
            $this->send($user, $title, $message, $type, $related, $actionUrl);
        }
    }
}
