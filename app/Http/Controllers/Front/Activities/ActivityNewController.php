<?php


namespace App\Http\Controllers\Front\Activities;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\StoreActivityNewRequest;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use Carbon\Carbon;

class ActivityNewController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function store(StoreActivityNewRequest $request)
    {
        $request->merge([
            'is_planned'     => false,
            'user_id'        => $this->currentUser()->id,
            'status'         => Activity::TYPE_COMPLETED,
            'completed_date' => Carbon::now()
        ]);

        $activity = $this->activityRepo->createActivity($request->all());

        $description = "Actividad terminada con {$request->input('time_real')}";
        $this->activityRepo->saveHistory($activity,$description);

        return response()->json([
            'msg' => "Actividad creada",
            'activity' => $this->transformActivityGeneral($activity)
        ],201);

    }


}
