<?php

namespace App\Http\Controllers\Front\Reports\Customers;

use App\Exports\CounterPlannedVsRealExport;
use App\Exports\Customers\ActivityTagExport;
use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\UploadableDocumentsTrait;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityTagController extends Controller
{
    use UploadableDocumentsTrait, DatesTrait;

    private $customerRepo;

    public function __construct(ICustomer $ICustomer)
    {
        $this->customerRepo = $ICustomer;
    }

    public function __invoke(Request $request)
    {
        $customer = $this->customerRepo->findCustomerById($request->customer_id);
        $dateFormat = $this->getDateFormats($request->input('yearAndMonth'));

        $tags = $this->customerRepo->reportactivityTag($request->customer_id, $dateFormat['from'], $dateFormat['to']);

        $filename = ucwords(Str::slug($customer->name))."(etiquetas)".'.xlsx';
        $range = Carbon::parse($dateFormat['from'])->format('d/m/y') .' hasta '. Carbon::parse($dateFormat['to'])->format('d/m/y');
        $fullPath = $this->handleDocumentS3(new ActivityTagExport($tags,$customer->name,$range),$filename);

        _addDocumentHistory(UserHistory::EXPORT, "Exportó reporte por etiquetas de $customer->name",$fullPath);
        _addHistory(UserHistory::EXPORT,"Exportó reporte planificado vc real de $customer->name - $fullPath");

        return response()->json([
            'status' => 'ok',
            'msg'    => 'Descargando...',
            'url'    => $fullPath
        ],201);

    }

}
