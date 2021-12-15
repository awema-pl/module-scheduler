<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Http\Requests\Rules;
use AwemaPL\Baselinker\Client\Api\TokenValidator;
use AwemaPL\Scheduler\Client\Api\ConnectionValidator;
use AwemaPL\Scheduler\Client\Api\SchedulerApiException;
use Illuminate\Contracts\Validation\Rule;
use AwemaPL\Scheduler\Client\Contracts\SchedulerClient;

class ValidCron implements Rule
{
    private $message;

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \Cron\CronExpression::isValidExpression($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return _p('scheduler::requests.user.schedule.messages.invalid_cron', 'Invalid CRON');
    }
}
