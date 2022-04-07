<?php

namespace App\Http\Controllers\Front\Activities\Crud;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;

class DestroyActivityController extends Controller
{
    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $activity_id)
    {
        $this->activityRepo->deleteActivity($activity_id);

        return response()->json([
            'view' => request()->input('view','calendar'),
            'msg' => "Actividad eliminada"
        ]);
    }
}
