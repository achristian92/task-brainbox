<?php

namespace App\Http\Controllers\Front\Activities\Validate;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;

class ReverseController extends Controller
{
    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $this->activityRepo->returnPlannedStatus($activity_id);

        return response()->json([
            'view' => request()->input('view','calendar'),
            'msg' => "Actividad revertida"
        ]);
    }
}
