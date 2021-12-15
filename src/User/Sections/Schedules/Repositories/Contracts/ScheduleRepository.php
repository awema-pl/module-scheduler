<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Repositories\Contracts;

use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\EloquentScheduleRepository;
use AwemaPL\Scheduler\Sections\Options\Http\Requests\UpdateOption;
use Illuminate\Http\Request;

interface ScheduleRepository
{
    /**
     * Create schedule
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Scope schedule
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scope($request);
    
    /**
     * Update schedule
     *
     * @param array $data
     * @param int $id
     *
     * @return int
     */
    public function update(array $data, $id);
    
    /**
     * Delete schedule
     *
     * @param int $id
     */
    public function delete($id);

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']);

    /**
     * Select schedulable type
     *
     * @return array
     */
    public function selectSchedulableType();

    /**
     * Select schedulable ID
     *
     * @param string $schedulableType
     * @return array
     */
    public function selectSchedulableId($schedulableType);
}
