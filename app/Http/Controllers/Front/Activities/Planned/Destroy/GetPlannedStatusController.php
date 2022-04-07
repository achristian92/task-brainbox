<?php


namespace App\Http\Controllers\Front\Activities\Planned\Destroy;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;

class GetPlannedStatusController extends Controller
{
    private $userRepo;
    private $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke(int $user_id)
    {
        $month = null;
        $year = null;
        if (request()->filled('filter_month')) {
            $month = request()->input('filter_month');
        }
        if (request()->filled('filter_year')) {
            $year = request()->input('filter_year');
        }

        $activities = $this->userRepo->listPlannedActivities($user_id,$month,$year)
            ->filter(function ($activity) {
                return $activity['status'] === Activity::TYPE_PLANNED;
            })
            ->transform(function (Activity $activity) {
                return [
                    'checked'   => false,
                    'id'        => $activity->id,
                    'name'      => $activity->name,
                    'customer'  => $activity->customer->name,
                    'startDate' => Carbon::parse($activity->start_date)->format('d/m')
                ];
            })->values();

        return response()->json([
            'activities' => $activities
        ]);
    }

}
