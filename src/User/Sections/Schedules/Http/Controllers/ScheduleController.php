<?php

namespace AwemaPL\Scheduler\User\Sections\Schedules\Http\Controllers;

use AwemaPL\Scheduler\Client\Api\ConnectionValidator;
use AwemaPL\Scheduler\Client\Api\SchedulerApiException;
use AwemaPL\Scheduler\Client\SchedulerClient;
use AwemaPL\Scheduler\User\Sections\Schedules\Models\Schedule;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Scheduler\User\Sections\Schedules\Http\Requests\StoreSchedule;
use AwemaPL\Scheduler\User\Sections\Schedules\Http\Requests\UpdateSchedule;
use AwemaPL\Scheduler\User\Sections\Schedules\Repositories\Contracts\ScheduleRepository;
use AwemaPL\Scheduler\User\Sections\Schedules\Resources\EloquentSchedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ScheduleController extends Controller
{
    use RedirectsTo, AuthorizesRequests;

    /** @var ScheduleRepository $schedules */
    protected $schedules;

    public function __construct(ScheduleRepository $schedules)
    {
        $this->schedules = $schedules;
    }

    /**
     * Display schedules
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('scheduler::user.sections.schedules.index');
    }

    /**
     * Request scope
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function scope(Request $request)
    {
        return EloquentSchedule::collection(
            $this->schedules->scope($request)
                ->isOwner()
                ->latest()->smartPaginate()
        );
    }

    /**
     * Create schedule
     *
     * @param StoreSchedule $request
     * @return array
     * @throws \Exception
     */
    public function store(StoreSchedule $request)
    {
          $this->schedules->create($request->all());
        return notify(_p('scheduler::notifies.user.schedule.success_connected_schedule', 'Success connected schedule.'));
    }

    /**
     * Update schedule
     *
     * @param UpdateSchedule $request
     * @param $id
     * @return array
     */
    public function update(UpdateSchedule $request, $id)
    {
        $this->authorize('isOwner', Schedule::find($id));
        $this->schedules->update($request->all(), $id);
        return notify(_p('scheduler::notifies.user.schedule.success_updated_schedule', 'Success updated schedule.'));
    }

    /**
     * Delete schedule
     *
     * @param $id
     * @return array
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete($id)
    {
        $this->authorize('isOwner', Schedule::find($id));
        $this->schedules->delete($id);
        return notify(_p('scheduler::notifies.user.schedule.success_deleted_schedule', 'Success deleted schedule.'));
    }

    /**
     * Select source type
     *
     * @return JsonResponse
     */
    public function selectSourceableType()
    {
        return $this->ajax($this->schedules->selectSchedulableType());
    }

    /**
     * Select sourceable ID
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function selectSourceableId(Request $request)
    {
        return $this->ajax($this->schedules->selectSchedulableId($request->schedulable_type));
    }

}
