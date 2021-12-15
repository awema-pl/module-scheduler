<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Jobs;

use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\Contracts\ScheduleRepository;
use AwemaPL\Scheduler\User\Sections\Schedules\Models\Schedule;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Scheduling\Schedule as ScheduleService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class CallScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    /** @var int The number of seconds the job can run before timing out. */
    public $timeout = 300;

    /** @var Carbon $date */
    private $date;

    /**
     * CallbackTaskJob constructor
     *
     * @param Carbon $date
     */
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @param ScheduleRepository $schedules
     */
    public function handle(ScheduleRepository $schedules)
    {

        foreach ($schedules->getScheduleActivatedCursor() as $schedule) {

            try{
                if ($this->isDue($schedule)){
                    $schedulable = $schedule->schedulable;
                    $schedulable->eventSchedule();
                }
            } catch (Throwable $exception){
                dump($exception->getMessage());
                Log::error(sprintf('%s: %s', trans('Błąd harmonogramu'), $exception->getMessage()), [
                    'exception' =>$exception,
                ]);
            }
        }
    }

    /**
     * Is due
     *
     * @param Schedule $schedule
     * @return mixed
     */
    public function isDue(Schedule $schedule): bool
    {
        return (new CronExpression($schedule->cron))->isDue($this->date, $schedule->timezone);
    }

}
