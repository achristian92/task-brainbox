<?php


namespace App\Http\Controllers\Front\Activities\SubActivity;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\SubActivities\SubActivity;
use App\Repositories\UsersHistories\UserHistory;

class DestroySubActivityController extends Controller
{
    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }
    public function __invoke(int $subactivity_id)
    {
        $subActivity = SubActivity::find($subactivity_id);
        _addHistory(UserHistory::DELETE,"Eliminó la subactividad $subActivity->name",$subActivity);
        $activity = $subActivity->activity;
        $subActivity->delete();

        if (! $activity->sub_activities()->exists()) {
            $activity->update(['with_subactivities' => false]);
        }

        return response()->json(['status'   => 'ok']);
    }

}
