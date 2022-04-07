<?php


namespace App\Http\Controllers\Front\Activities\Planned\Duplicate;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\StoreDuplicatePlannedRequest;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;

class WorkPlanController extends Controller
{
    private $activityRepo;
    private $userRepo;

    public function __construct(IActivity $IActivity,IUser $IUser)
    {
        $this->userRepo    = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function __invoke(StoreDuplicatePlannedRequest $request, int $user_id)
    {
        $fromDate  = Carbon::createFromDate($request->get('from_month'));
        $fromMonth = $fromDate->format('m');
        $dateTo    = Carbon::createFromDate($request->get('to_month'));
        $toMonth   = $dateTo->format('m');
        $toYear    = $dateTo->format('Y');

        $this->userRepo->listPlannedActivities($user_id, $fromMonth)
                       ->each(function ($activity) use ($toYear, $toMonth,$user_id) {
                           $start_date_day = Carbon::createFromDate($activity->start_date)->format('d');
                           $due_date_day   = Carbon::createFromDate($activity->due_date)->format('d');
                           $params = [
                               'is_planned'      => true,
                               'customer_id'     => $activity->customer_id,
                               'user_id'         => $user_id,
                               'name'            => $activity->name,
                               'time_estimate'   => $activity->time_estimate,
                               'start_date'      => Carbon::createFromDate($toYear, $toMonth, $start_date_day)->format('Y-m-d'),
                               'due_date'        => Carbon::createFromDate($toYear, $toMonth, $due_date_day)->format('Y-m-d'),
                               'tag_id'          => $activity->tag_id
                           ];
                           $this->activityRepo->createActivity($params);
                        });

        _addHistory(UserHistory::DUPLICATE,"Duplicó el plan de trabajo de $fromDate hacia $dateTo");

        return response()->json([
            'msg' => "Plan duplicado"
        ],201);
    }
}
