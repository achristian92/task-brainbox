<?php

namespace App\Http\Controllers\Front\Activities\Crud;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\UpdateActivityRequest;

class UpdateActivityController extends Controller
{
    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(UpdateActivityRequest $request, int $activity_id)
    {

        $data = [
            'user_id'       => $request->user_id,
            'customer_id'   => $request->customer_id,
            'tag_id'        => $request->tag_id,
            'name'          => $request->name,
            'start_date'    => $request->start_date,
            'due_date'      => $request->due_date,
            'time_estimate' => $request->time_estimate,
            'deadline'      => $request->deadline,
            'description'   => $request->description,
            'is_priority'   => $request->is_priority,
        ];
        $this->activityRepo->updateActivity($data,$activity_id);

        $activity = $this->activityRepo->findActivityById($activity_id);

        if ($request->has('previous'))
            $this->activityRepo->updateActivity(['previous_id' => implode(',',$request->input('previous'))], $activity->id);
        else
            $this->activityRepo->updateActivity(['previous_id' => NULL], $activity->id);

        if ($request->user_id !== $activity->created_by_id) {
            $activity->reassign($request);
            $this->activityRepo->saveHistory($activity,'Actividad reasignada a '.$activity->user->name);
        }


        return response()->json([
            'msg' => 'Actividad actualizada',
            'view' => $request->input('view','calendar')
        ]);
    }
}
