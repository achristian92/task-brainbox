<?php


namespace App\Http\Controllers\Front\Reports\Customers;


use App\Exports\CustomerTotalHoursByMonth;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\ReportHoursMonthRequest;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\UsersHistories\UserHistory;

class TimeWorkedByMonthController extends Controller
{
    use DatesTrait, UploadableDocumentsTrait;

    private $customerRepo;
    private $activityRepo;

    public function __construct(ICustomer $ICustomer, IActivity $IActivity)
    {
        $this->customerRepo = $ICustomer;
        $this->activityRepo = $IActivity;
    }

    public function __invoke(ReportHoursMonthRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $data = $this->customerRepo->reportHorasMonth($date['begin'],$date['end'],$date['range']);

        $filename = 'Clientes-tiempo-mensual'.$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CustomerTotalHoursByMonth($data->toArray(), $date['range'], $date['format']->monthName),$filename);

        _addDocumentHistory(UserHistory::EXPORT,"Exportó reporte horas trabajas por clientes",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte horas trabajas por clientes");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }
}
