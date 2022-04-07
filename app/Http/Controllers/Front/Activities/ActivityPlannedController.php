<?php

namespace App\Http\Controllers\Front\Activities;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityPlannedController extends Controller
{
    use ActivityTransformable, ActivityFilterTrait;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(Request $request, int $user_id)
    {
        $month = null;
        $year = null;
        if ($request->filled('month')) {
            $month = $request->input('month');
        }
        if ($request->filled('year')) {
            $year = $request->input('year');
        }

        $activities = $this->userRepo->listPlannedActivities($user_id, $month, $year);
        $activitiesFilter = $this->applyFilterRequest($activities,$request);

        $counters =  [
            'total'        => $activitiesFilter->count(),
            'qtyPlanned'   => $this->qtyPlanned($activitiesFilter),
            'qtyApproved'  => $this->qtyApproved($activitiesFilter),
            'qtyPartial'   => $this->qtyPartial($activitiesFilter),
            'qtyCompleted' => $this->qtyCompleted($activitiesFilter),
            'timeEstimate' => _sumTime($activitiesFilter->pluck('time_estimate')->toArray()),
        ];

        $calendarActivities = $this->transformActivitiesByTypeView($activitiesFilter);
        $listActivities = $this->transformActivitiesByTypeView($activitiesFilter,'list');

        return response()->json([
            'counters' => $counters,
            'user_id'  => $user_id,
            'calendar' => $calendarActivities,
            'list'     => $listActivities
        ]);
    }


    private function applyFilterRequest($activities, Request $request): Collection
    {
        if ($request->filled('customer_id')) {
            $activities = $activities->where('customer_id',$request->input('customer_id'));
        }

        if ($request->filled('status_id')) {
            $activities = $activities->where('status',$request->input('status_id'));
        }

        return $activities;
    }


    private function transformActivitiesByTypeView($activities, string $view = "calendar") {
        if ($view === "calendar") {
            $transActivities = $activities->map(function ($activity) {
                return [
                    'id'        => $activity->id,
                    'title'     => $activity->name,
                    'start'     => $activity->start_date,
                    'allDay'    => true,
                    'className' => _colorStatusBg($activity->status)
                ];
            })->values();
        } else {
            $transActivities = $activities->transform(function ($activity) {
                return [
                    'id'                    => $activity->id,
                    'customer_id'           => $activity->customer->id,
                    'customer'              => Str::limit($activity->customer->name,20),
                    'nameActivity'          => Str::limit($activity->name,40),
                    'estimatedTime'         => $activity->estimatedTime(),
                    'duration'              => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
                    'startDateCalendar'     => Carbon::parse($activity->start_date)->format('Y-m-d'),
                    'startDate'             => Carbon::parse($activity->start_date)->format('d/m'),
                    'dueDate'               => $activity->due_date,
                    'is_priority'           => $activity->is_priority,
                    'colorPriority'         => $activity->is_priority ? 'text-danger' : '',
                    'status'                => $activity->currentStatus(),
                    'statusName'            => $activity->statusName(),
                    'nameUserStateActivity' => $activity->nameUserStateActivity(),
                    'colorState'            => _colorStatusBg($activity->currentStatus()),
                    'tagId'                => $activity->tag->id,
                    'tagName'               => $activity->tag->name,
                    'tagColor'               => $activity->tag->color,
                    'checked'               => false
                ];
            })->groupBy('customer')->map(function ($activities,$customer) {
                $progress = $this->calculateProgress($activities);
                return  [
                    'name'                => $customer,
                    'qtyActivities'       => $activities->count(),
                    'activities'          => $activities,
                    'progress'            => $progress,
                    'bgProgress'          => _bgProgress($progress),
                    'sumHoursEstCustomer' => $this->totalEstimatedTime($activities)
                ];
            })->values();
        }

        return $transActivities;
    }
}
