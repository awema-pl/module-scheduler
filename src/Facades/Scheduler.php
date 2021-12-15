<?php

namespace AwemaPL\Scheduler\Facades;

use AwemaPL\Scheduler\Contracts\Scheduler as SchedulerContract;
use Illuminate\Support\Facades\Facade;

class Scheduler extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SchedulerContract::class;
    }
}
