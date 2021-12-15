<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Scopes;

use AwemaPL\Repository\Scopes\ScopesAbstract;

class EloquentScheduleScopes extends ScopesAbstract
{
    protected $scopes = [
        'q' =>SearchSchedule::class,
    ];
}
