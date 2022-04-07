<?php

namespace App\Http\Controllers\Front\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\ApproveRejectRequest;

class ApproveRejectActivityController extends Controller
{

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(ApproveRejectRequest $request)
    {
        $activity = Activity::find($request->activity_id);

        if ($request->input('approved',false)) {
            $this->activityRepo->saveHistory($activity,'Aprobado el cambio de fecha');
            $activity->start_date     = $activity->completed_date_manual;
            $activity->due_date       = $activity->completed_date_manual;
            $activity->completed_date = $activity->completed_date_manual;
        }

        $activity->approved_change_date_by = \Auth::id();
        $activity->approved_change_date = now();
        $activity->save();

        return response()->json([
            'msg' => 'Registro actualizado'
        ]);
    }

}
