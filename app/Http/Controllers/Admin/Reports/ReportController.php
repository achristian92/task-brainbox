<?php


namespace App\Http\Controllers\Admin\Reports;


use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Users\Repository\IUser;

class ReportController extends Controller
{

    private $customerRepo;
    private $userRepo;

    public function __construct (IUser $IUser, ICustomer $ICustomer) {
        $this->customerRepo = $ICustomer;
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        return view('admin.reports.index', [
            'users'     => $this->userRepo->usersMonitoring($this->currentUser()->id,'name','asc',['id','name','last_name']),
            'customers' => $this->customerRepo->listCustomersActivated('name','asc',['id','name'])
        ]);
    }
}
