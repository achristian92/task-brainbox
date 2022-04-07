<?php


namespace App\Http\Controllers\Front\Activities\Planned\Complete;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;

class ActivityFinishController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $durationRequest = request()->input('duration',"00:00");
        $description = $this->isPartialActivity() ? "Actividad avanzada con $durationRequest"
                                                  : "Actividad completada con $durationRequest";


        $daterequest = Carbon::parse(request()->input('date',Carbon::now()))->format('Y-m-d');
        $now = Carbon::now()->format('Y-m-d');

        if ($this->isPartialActivity())
            $data = [
                'status'     => Activity::TYPE_PARTIAL,
                'is_partial' => 1,
            ];
         else
            $data = [
                'status'                   => Activity::TYPE_COMPLETED,
                'time_real'                => $durationRequest,
                'completed_date'           => Carbon::now(),
                'different_completed_date' => $daterequest !== $now,
                'completed_date_manual'    => $daterequest,
            ];


        $this->activityRepo->updateActivity($data,$activity_id);
        $activity = $this->activityRepo->findActivityById($activity_id);

        if ($this->isPartialActivity()) {
            $this->activityRepo->saveDurationPartial($activity,$durationRequest);
            $this->activityRepo->saveHistory($activity,$description);
        } else {
            $this->activityRepo->saveHistory($activity,$description);
        }

        if ($daterequest !== $now)
            $this->activityRepo->saveHistory(Activity::find($activity_id),'Pendiente de aprobación de fecha');

        _addHistory(UserHistory::UPDATED,"$description - $activity->name",$activity);

        return response()->json([
            'msg'      => $description,
            'activity' => $this->transformActivityGeneral($this->activityRepo->findActivityById($activity->id))
        ]);
    }
    private function isPartialActivity(): bool
    {
        return request()->filled('is_partial') && request()->input('is_partial',false);
    }

}
