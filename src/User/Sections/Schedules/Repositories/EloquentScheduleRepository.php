<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Repositories;

use AwemaPL\Sceduler\User\Sections\Schedules\Services\SchedulableType;
use AwemaPL\Scheduler\Contracts\Schedulable;
use AwemaPL\Scheduler\User\Sections\Schedules\Models\Schedule;
use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\Contracts\ScheduleRepository;
use AwemaPL\Scheduler\User\Sections\Schedules\Scopes\EloquentScheduleScopes;
use AwemaPL\Repository\Eloquent\BaseRepository;
use AwemaPL\Storage\User\Sections\Sources\Models\Contracts\Sourceable;
use AwemaPL\Storage\User\Sections\Sources\Services\SourceTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EloquentScheduleRepository extends BaseRepository implements ScheduleRepository
{

    /** @var SchedulableType $schedulableTypes */
    private $schedulableTypes;

    protected $searchable = [

    ];

    public function __construct(SchedulableType $schedulableTypes)
    {
        parent::__construct();
        $this->schedulableTypes = $schedulableTypes;
    }

    public function entity()
    {
        return Schedule::class;
    }

    public function scope($request)
    {
        // apply build-in scopes
        parent::scope($request);

        // apply custom scopes
        $this->entity = (new EloquentScheduleScopes($request))->scope($this->entity);
        return $this;
    }

    /**
     * Create new role
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $data['user_id'] = $data['user_id'] ?? Auth::id();
        return Schedule::create($data);
    }

    /**
     * Update schedule
     *
     * @param array $data
     * @param int $id
     * @param string $attribute
     *
     * @return int
     */
    public function update(array $data, $id, $attribute = 'id')
    {
       return parent::update($data, $id, $attribute);
    }

    /**
     * Delete schedule
     *
     * @param int $id
     */
    public function delete($id){
        $this->destroy($id);
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static[]|static|null
     */
    public function find($id, $columns = ['*']){
        return parent::find($id, $columns);
    }

    /**
     * Select schedulable type
     *
     * @return array
     */
    public function selectSchedulableType()
    {
        $data = [];
        foreach ($this->schedulableTypes->getTypes() as $type => $resource){
            array_push($data, [
                'id' =>$type,
                'name' =>$this->schedulableTypes->getName($type),
            ]);
        }
        return $data;
    }

    /**
     * Select schedulable ID
     *
     * @param string $schedulableType
     * @return array
     */
    public function selectSchedulableId($schedulableType){
        $class = $this->schedulableTypes->getType($schedulableType);
        $schedulables = $class::where('user_id', Auth::user()->id)->get();
        $data = [];
        /** @var Schedulable $schedulable */
        foreach ($schedulables as $schedulable){
            array_push($data, [
                'id' =>$schedulable->getKey(),
                'name' =>$schedulable->getName(),
            ]);
        }
        return $data;
    }
}
