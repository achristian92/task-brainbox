<?php

namespace App\Http\Controllers\Front\Activities\Planned\Export;

use App\Exports\PlanCounterExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportListController extends Controller
{
    use ActivityFilterTrait, TActivityReport, DatesTrait;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(Request $request, int $user_id)
    {
        $date = $this->beginEndMonth($request);

        $activities = $this->queryActivities($date['begin'],$date['end'],$user_id,null,true)
                           ->map(function ($activity) {
                               return $this->transActivityReport($activity, true);
                           });

        $monthName = $date['format']->monthName."-".$date['format']->year;

        $user = $this->userRepo->findUserById($user_id);

        $nameFile = 'Plan-'.$user->name.'-'.$monthName.'.xlsx';

        _addHistory(UserHistory::EXPORT,"Exportó plan de trabajo del usuario $user->full_name - $monthName");

        return Excel::download(new PlanCounterExport($activities,$user->full_name,$monthName), $nameFile);
    }
}
