<?php

namespace App\Policies;

use App\Models\Trend;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrendPolicy
{
    public function update(User $user, Trend $trend)
    {
        return $trend->user_id === $user->id;
    }

    public function delete(User $user, Trend $trend)
    {
        return $trend->user_id === $user->id;
    }

}
