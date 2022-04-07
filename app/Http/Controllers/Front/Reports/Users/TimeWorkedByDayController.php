<?php


namespace App\Http\Controllers\Front\Reports\Users;


use App\Exports\CounterTotalHoursCustomersByDay;
use App\Http\Controllers\Controller;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Requests\ReportUserCustomerRequest;
use App\Repositories\Users\Requests\ReportUserDayRequest;
use App\Repositories\UsersHistories\UserHistory;

class TimeWorkedByDayController extends Controller
{
    use UploadableDocumentsTrait, DatesTrait;

    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }

    public function __invoke(ReportUserCustomerRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $user = $this->userRepo->findUserById($request->user_id);
        $data = $this->userRepo->reportTimeCustomerDays($user->id,$date['begin'],$date['end'],$date['range']);

        $filename = $user->name."-clientes-tiempo-dias-".$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CounterTotalHoursCustomersByDay($data->toArray(), $date['range'], $user->name, $date['format']->monthName),$filename);


        _addDocumentHistory(UserHistory::EXPORT, "Exportó reporte tiempo trabajo por dia de $user->full_name",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte tiempo trabajo por dia de $user->full_name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

}
