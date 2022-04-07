<?php


namespace App\Http\Controllers\Front\Reports\Customers;


use App\Exports\CustomerHistoryHours;
use App\Exports\CustomerTotalHoursByMonth;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\ReportHoursMonthRequest;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\UsersHistories\UserHistory;

class HistoryLastSixMonthsController extends Controller
{
    use DatesTrait, UploadableDocumentsTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke(ReportHoursMonthRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $rangeMonth = $date['rangeMonth'];

        _addHistory(UserHistory::EXPORT,"Intentó exportar reporte historial horas clientes");

        $dataAll =  $this->activityRepo->listActivitiesForStatistics($date['subMonth'],$date['end']);

        $histories = $dataAll->groupBy('customerName')
            ->map(function ($activities, $customer) use($rangeMonth) {
                foreach ($rangeMonth as $month) {
                    $hours[] = _sumTime($activities->where('startDateMonth',$month)->pluck('realTime')->toArray());
                }
                return [
                    'name' => $customer,
                    'hoursMonths' => $hours,
                ];
            })->values();

        $filename = 'Clientes-historial'.$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CustomerHistoryHours($histories->toArray(),$date['rangeMonthName']),$filename);

        _addDocumentHistory(UserHistory::EXPORT,"Exportó historial horas clientes",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte historial horas clientes - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

}
