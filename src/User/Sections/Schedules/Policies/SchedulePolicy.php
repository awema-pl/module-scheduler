<?php
namespace AwemaPL\Scheduler\User\Sections\Schedules\Policies;

use AwemaPL\Scheduler\User\Sections\Schedules\Models\Schedule;
use Illuminate\Foundation\Auth\User;

class SchedulePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Schedule $schedule
     * @return bool
     */
    public function isOwner(User $user, Schedule $schedule)
    {
        return $user->id === $schedule->user_id;
    }


}
