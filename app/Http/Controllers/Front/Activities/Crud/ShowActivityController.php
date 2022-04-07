<?php


namespace App\Http\Controllers\Front\Activities\Crud;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\ActivityHistory\Transformations\HistoryActivityTransformable;
use App\Repositories\PartialActivities\Transformations\PartialActivityTransformable;
use App\Repositories\SubActivities\Transformations\SubActivityTransform;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ShowActivityController extends Controller
{
    use ActivityTransformable, SubActivityTransform,
        PartialActivityTransformable, HistoryActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $activity = $this->activityRepo->findActivityById($activity_id);

        $data = [
            'id'                => $activity->id,
            'customer'          => $activity->customer->name,
            'tag'               => $activity->tag->name,
            'activity'          => $activity->name,
            'startDateShort'    => Carbon::parse($activity->start_date)->format('d/m'),
            'dueDateShort'      => Carbon::parse($activity->due_date)->format('d/m'),
            'isCompletedOutDate'=> $activity->isCompletedOutOfDate(),
            'dateCompleted'     => $activity->isCompletedState() ? Carbon::parse($activity->completed_date)->format('d/m') : '',
            'estimatedTime'     => $activity->estimatedTime(),
            'realTime'          => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
            'status'            => $activity->statusName(),
            'currentStatus'     => $activity->currentStatus(),
            'userStatusAct'     => $activity->nameUserStateActivity(),
            'description'       => $activity->isPlanned() ? $activity->description : $activity->description2,
            'subActivities'     => $this->subActivities($activity['sub_activities']),
            'histories'         => $this->histories($activity['histories']),
            'partials'          => $this->partialActivities($activity['partials']),
            'dependencies'      => $this->dependencies($activity)
        ];

        return response()->json($data);
    }

    private function subActivities(Collection $subActivities): Collection
    {
        return $subActivities->transform(function ($subActivity) {
            return $this->transformSubActivity($subActivity);
        });
    }

    private function partialActivities(Collection $partialActivities): Collection
    {
        return $partialActivities->transform(function ($subActivity) {
            return $this->transformPartialActivity($subActivity);
        });
    }

    private function histories(Collection $histories)
    {
        return $histories->transform(function ($history) {
            return $this->transformHistoryActivity($history);
        });
    }

    private function dependencies($activity)
    {
        return collect($activity->dependenceIDS())->map(function ($activity_id) {
            $activity = $this->activityRepo->findActivityById($activity_id);
            $completed_at = $activity->isCompletedState() ? $activity->completed_date : $activity->start_date;

            return [
                'name'         => $activity->name,
                'completed_at' => Carbon::parse($completed_at)->format('d/m'),
                'status'       => $activity->statusName(),
                'duration'     => $activity->isCompletedState() ?
                                  $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']) :
                                  $activity->estimatedTime()
            ];
        });
    }

}
