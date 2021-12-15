<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EloquentSchedule extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cron' => $this->cron,
           'activated' => $this->activated,
            'created_at' =>$this->created_at->format('Y-m-d H:i:s'),
            'schedulable_type' =>$this->schedulable_type,
            'schedulable_id' =>$this->schedulable_id,
        ];
    }
}
