<?php

namespace AwemaPL\Scheduler\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * @return BelongsTo
     */
    public function user(): BelongsTo;

    /**
     * Event schedule
     *
     * @return void
     */
    public function eventSchedule(): void;

    /**
     * Get key
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;
}
