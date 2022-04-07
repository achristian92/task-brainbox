<?php


namespace App\Http\Controllers\Front\Activities\SubActivity;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\SubActivities\Requests\StoreSubActivityRequest;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;

class StoreSubActivityController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(StoreSubActivityRequest $request, int $activity_id)
    {
        $activity = $this->activityRepo->findActivityById($activity_id);

        $name = $request->get('name');
        $duration = $request->get('duration');

        $activity->update(['with_subactivities' => true]);

        $subActivity = $activity->sub_activities()->create([
            'name'         => $name,
            'duration'     => $duration,
            'completed_at' => Carbon::now()
        ]);

        $this->activityRepo->saveHistory($activity, "Se creó la subactividad  $name con $duration");
        _addHistory(UserHistory::STORE,"Creó la subactividad $subActivity->name",$subActivity);

        return response()->json([
            'activity' => $this->transformActivityGeneral($this->activityRepo->findActivityById($activity->id)),
            'msg'      => 'Subactividad creada'
        ],201);
    }

}
