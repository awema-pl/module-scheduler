<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Illuminate\Database\Eloquent\Model;
use AwemaPL\Scheduler\User\Sections\Schedules\Models\Contracts\Schedule as ScheduleContract;

class Schedule extends Model implements ScheduleContract
{
    use EncryptableDbAttribute;

    /** @var array The attributes that should be encrypted/decrypted */
    protected $encryptable = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'user_id', 'name', 'cron', 'activated', 'schedulable_type', 'schedulable_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'activated' =>'boolean',
        'schedulable_id' =>'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return config('scheduler.database.tables.scheduler_schedules');
    }

    /**
     * Get the user that owns the schedule.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    /**
     * Get the parent schedulable model.
     */
    public function schedulable()
    {
        return $this->morphTo();
    }
}
