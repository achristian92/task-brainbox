<?php

namespace App\Http\Controllers\Front\Tracks;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class TrackShowController extends Controller
{
   use ActivityFilterTrait, ActivityTransformable ;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(int $user_id)
    {
       $dateFormat = Carbon::createFromDate(request()->input('yearAndMonth'));

        $activities = $this->userRepo->listActivities($user_id,$dateFormat->month,$dateFormat->year)->transform(function ($activity) {
            return $this->transformActivityGeneral($activity);
        });
        $arrayEstimatedTime = Arr::pluck($activities,'estimatedTime');
        $arrayDuration = Arr::pluck($activities,'duration');

        return response()->json([
            'user'       => $this->userRepo->findUserById($user_id)->full_name,
            'timeWorked' => _formatTimeWorked(_sumTime($arrayEstimatedTime)),
            'timeReal'   => _formatTimeWorked(_sumTime($arrayDuration)),
            'progress'   => $this->calculateProgress($activities),
            'resume'     => $this->calculateResume($activities),
            'activities' => $activities,
        ]);
    }


}
