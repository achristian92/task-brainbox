<?php


namespace App\Http\Controllers\Front\Reports\Activities;


use App\Exports\ActivitiesExport;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Requests\ReportActivityRequest;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\UsersHistories\UserHistory;

class ListActivitiesController extends Controller
{
    use UploadableDocumentsTrait,DatesTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(ReportActivityRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $data =  $this->activityRepo->reportActivities($date['beginR'],$date['endR']);

        $unionR = $date['unionR'];

        $filename = "Actividades-reporte-".$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new ActivitiesExport($data->toArray(),$unionR),$filename);

        _addDocumentHistory(UserHistory::EXPORT,"Exportó reporte actividades $unionR",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte actividades $unionR - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

}
