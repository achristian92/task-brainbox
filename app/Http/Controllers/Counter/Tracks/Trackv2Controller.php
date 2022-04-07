<?php

namespace App\Http\Controllers\Counter\Tracks;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Users\Repository\IUser;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class Trackv2Controller extends Controller
{
    use ActivityFilterTrait, ActivityTransformable ;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        $user_id = Auth::id();
        $dateFormat = Carbon::createFromDate(request()->input('yearAndMonth'));

        $activities = $this->userRepo->listActivities($user_id,$dateFormat->month,$dateFormat->year)->transform(function ($activity) {
            return $this->transformActivityGeneral($activity);
        });
        $arrayEstimatedTime = Arr::pluck($activities,'estimatedTime');
        $arrayDuration = Arr::pluck($activities,'duration');

        return view('counter.tracks.indexv2',[
            'user'       => $this->userRepo->findUserById($user_id)->full_name,
            'timeWorked' => _sumTime($arrayEstimatedTime),
            'timeReal'   => _sumTime($arrayDuration),
            'progress'   => $this->calculateProgress($activities),
            'resume'     => $this->calculateResume($activities),
            'activities' => $activities,
        ]);
    }
}
