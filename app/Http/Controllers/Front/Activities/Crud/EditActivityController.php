<?php

namespace App\Http\Controllers\Front\Activities\Crud;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityTransformable;

class EditActivityController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $activity = $this->activityRepo->findActivityById($activity_id);

        return response()->json([
            'activity' => $this->transformActivity($activity),
        ]);
    }
}
