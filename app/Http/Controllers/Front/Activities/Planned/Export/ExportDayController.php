<?php

namespace App\Http\Controllers\Front\Activities\Planned\Export;

use App\Exports\PlanCounterExportDays;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportDayController extends Controller
{
    use ActivityFilterTrait, TActivityReport, DatesTrait;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(Request $request, int $user_id)
    {
        $date   = $this->beginEndMonth($request);
        $period = $date['range'];
        $user   = $this->userRepo->findUserById($user_id);

        $data = $this->queryActivities($date['begin'],$date['end'],$user_id,null,true)
                     ->transform(function ($activity) {
                         return $this->transActivityReport($activity,true);
                     })
                     ->groupBy('customer')->map(function ($activities, $customer) use ($period) {
                         return [
                             'customer' => $customer,
                             'dates' => $this->addTimeAtDate($period,$activities,'estimatedTime'),
                             'total' => $this->totalEstimatedTime($activities)
                         ];
                     })
                     ->sortBy('customer')->values();

        $monthName = $date['format']->monthName."-".$date['format']->year;
        $nameFile = 'Plan-dias-'.$user->name.'-'.$monthName.'.xlsx';

        _addHistory(UserHistory::EXPORT,"Exportó plan de trabajo por dia del usuario $user->full_name - $monthName");

        return Excel::download(new PlanCounterExportDays($data->toArray(),$period,$user->name,$monthName), $nameFile);
    }
}
