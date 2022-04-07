<?php


namespace App\Http\Controllers\Front\Reports\Customers;


use App\Exports\CustomerListCounterWork;
use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\ReportListCounterWorkedRequest;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\UsersHistories\UserHistory;

class ListUsersWorkingController extends Controller
{
    use DatesTrait, UploadableDocumentsTrait;

    private $customerRepo;
    private $activityRepo;

    public function __construct(ICustomer $ICustomer, IActivity $IActivity)
    {
        $this->customerRepo = $ICustomer;
        $this->activityRepo = $IActivity;
    }
    public function __invoke(ReportListCounterWorkedRequest $request)
    {
        $date = $this->beginEndMonth($request);

        $customer = $this->customerRepo->findCustomerById($request->customer_id);
        $data = $this->activityRepo->reportListCounterWorkedCustomer($customer->id, $date['begin'],$date['end'], $date['range']);

        $filename = 'Clientes-lista-contadores'.$date['datetime'].'.xlsx';
        $fullPath = $this->handleDocumentS3(new CustomerListCounterWork($data->toArray(),$date['range'],$date['format']->monthName,$customer->name),$filename);

        _addDocumentHistory(UserHistory::EXPORT,"Exportó reporte lista de trabajores del cliente $customer->name",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte lista de trabajores del cliente $customer->name");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);
    }

}
