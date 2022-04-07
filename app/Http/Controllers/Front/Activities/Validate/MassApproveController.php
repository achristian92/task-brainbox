<?php

namespace App\Http\Controllers\Front\Activities\Validate;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Users\Repository\IUser;

class MassApproveController extends Controller
{

    private $userRepo;
    private $activityRepo;

    public function __construct(IUser $IUser,IActivity $IActivity)
    {
        $this->userRepo      = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $user_id)
    {
        $month = null;
        $year = null;
        if (request()->filled('month')) {
            $month = request()->input('month');
        }
        if (request()->filled('year')) {
            $year = request()->input('year');
        }

        $this->userRepo->listPlannedActivities($user_id,$month,$year)
                       ->where('status',Activity::TYPE_PLANNED)
                       ->pluck('id')
                       ->each(function ($activity_id)  {
                           $this->activityRepo->approve($activity_id);
                       });

        return response()->json([
            'msg' => "Actividades aprobadas"
        ]);
    }
}
