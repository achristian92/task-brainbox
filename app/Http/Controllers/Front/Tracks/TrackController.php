<?php

namespace App\Http\Controllers\Front\Tracks;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Settings\Setup;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use Illuminate\Support\Str;

class TrackController extends Controller
{
    use ActivityTransformable, ActivityFilterTrait, DatesTrait;

    private $userRepo, $activityRepo;

    public function __construct(IUser $IUser, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
    }

    public function index()
    {
        $dateFormat = $this->getDateFormats(request()->input('yearAndMonth'));
        $setting = Setup::first();
        $usersCanSeeIds = $this->userRepo->listUsersCanSee()->pluck('id');
        $activities = $this->activityRepo->listOfActivities($dateFormat['from'],$dateFormat['to'])
                      ->transform(function ($activity) {
                        return $this->transformActivityGeneral($activity);
                      });


        $users =  $activities->whereIn('userId',$usersCanSeeIds)
            ->groupBy('userName','userId')
            ->map(function ($activities,$counter) use ($setting) {
                $progress = $this->calculateProgress($activities);
                list($hours, $minute) = explode(':', $this->totalRealTime($activities));
                $qtys = $this->calculateResume($activities);

                return [
                    'id'            => $activities->first()['userId'],
                    'name'          => Str::limit($counter,30),
                    'qtyOverdue'    => $qtys['qtyOverdue'],
                    'qtyCompleted'  => $qtys['qtyCompleted'],
                    'total'         => $qtys['total'],
                    'estimatedTime' => $this->totalEstimatedTime($activities),
                    'realTime'      => $this->totalRealTime($activities),
                    'progress'      => $progress,
                    'bgProgress'    => _bgProgress($progress),
                    'hoursWorked'   => intval($hours),
                    'performance'   => $hours == 0 ? 0 : number_format((($hours * 100)/$setting->hours),2),
                    'performanceRaw'=> intval($hours)
                ];
            })->sortBy('name')->values();

        return response()->json([
            'users'      => $users,
            'hoursMonth' => intval($setting->hours),
        ]);
    }

    public function show(int $user_id)
    {

    }

}
