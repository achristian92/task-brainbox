<?php


namespace App\Http\Controllers\Front\Reports\Users;


use App\Exports\CounterTotalHoursCustomers;
use App\Http\Controllers\Controller;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Requests\ReportUserDayRequest;
use App\Repositories\UsersHistories\UserHistory;

class TimeWorkedByCustomerController extends Controller
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
        $data = $this->userRepo->reportTimeCustomer($user->id,$date['beginR'],$date['endR']);

        $filename = $user->name."-tiempo-trabajo-cliente-".$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CounterTotalHoursCustomers($data,$user->name,$date['unionR']),$filename);

        _addDocumentHistory(UserHistory::EXPORT, "Exportó reporte tiempo trabajo por cliente de $user->full_name",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte tiempo trabajo por cliente de $user->full_name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }
}
