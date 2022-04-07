<?php

namespace App\Http\Controllers\Front\Activities\Crud;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\StoreActivityRequest;
use App\Repositories\Activities\Transformations\ActivityTransformable;

class StoreActivityController extends Controller
{
    use ActivityTransformable;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(StoreActivityRequest $request)
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

        $activity = $this->activityRepo->createActivity($data);


        if ($request->has('previous'))
            $this->activityRepo->updateActivity(['previous_id' => implode(',',$request->input('previous'))], $activity->id);
        else
            $this->activityRepo->updateActivity(['previous_id' => NULL], $activity->id);


        if ($activity->isOwnerDifferent())
            $this->notifyAssignment($activity);

        return response()->json([
            'view' => $request->input('view','calendar'),
            'msg' => 'Actividad creada',
        ]);
    }
}
