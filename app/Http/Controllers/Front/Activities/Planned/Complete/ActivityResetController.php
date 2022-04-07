<?php


namespace App\Http\Controllers\Front\Activities\Planned\Complete;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;

class ActivityResetController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $this->activityRepo->resetActivityApproved($activity_id);

        return response()->json([
            'msg'      => 'Actividad restablecida',
            'activity' => $this->transformActivityGeneral($this->activityRepo->findActivityById($activity_id))
        ]);
    }

}
