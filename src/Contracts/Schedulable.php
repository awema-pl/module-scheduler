<?php

namespace AwemaPL\Scheduler\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

interface Schedulable
{
    /**
     * Get the schedule's image.
     *
     * @return MorphOne
     */
    public function schedule(): MorphOne;

    /**
     * Get the user that owns the integration.
     *
     * @return HasOneThrough
     */
    public function user(): HasOneThrough;

    /**
     * Event schedule
     *
     * @return void
     */
    public function eventSchedule(): void;

    /**
     * Get key
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Get name
     *
     * @return string
     */
    public function getname(): string;
}
