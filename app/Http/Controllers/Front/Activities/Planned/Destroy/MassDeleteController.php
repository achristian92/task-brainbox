<?php


namespace App\Http\Controllers\Front\Activities\Planned\Destroy;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;

class MassDeleteController extends Controller
{
    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $IDS = request()->input('destroyIDS',[]);
        collect($IDS)->each(function ($activity_id) {
            $this->activityRepo->deleteActivity($activity_id);
        });

        return response()->json([
            'msg' => "Actividades eliminadas"
        ]);
    }

}
