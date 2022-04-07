<?php


namespace App\Http\Controllers\Front\Reports\Users;


use App\Exports\CounterPlannedVsRealExport;
use App\Http\Controllers\Controller;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Requests\ReportUserDayRequest;
use App\Repositories\UsersHistories\UserHistory;

class PlannedvsRealController extends Controller
{
    use UploadableDocumentsTrait, DatesTrait;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(ReportUserDayRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $user = $this->userRepo->findUserById($request->user_id);
        $data = $this->userRepo->reportPlannedVsReal($user->id,$date['beginR'],$date['endR']);

        $filename = $user->name."-plan-vs-real-".$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CounterPlannedVsRealExport($data,$user->name,$date['unionR']),$filename);

        _addDocumentHistory(UserHistory::EXPORT, "Exportó reporte planificado vc real de $user->full_name",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte planificado vc real de $user->full_name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

}
