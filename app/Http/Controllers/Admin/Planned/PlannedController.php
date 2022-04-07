<?php

namespace App\Http\Controllers\Admin\Planned;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;

class PlannedController extends Controller
{

    private $tagRepo;
    private $userRepo;
    private $customerRepo;

    public function __construct (IUser $IUser,ITag $ITag, ICustomer $ICustomer) {
        $this->userRepo     = $IUser;
        $this->tagRepo      = $ITag;
        $this->customerRepo = $ICustomer;
    }
    public function __invoke()
    {
        return view('admin.planned.index', [
            'usersMonitor' => $this->userRepo->usersMonitoring($this->currentUser()->id,'name','asc',['id','name','last_name']),
            'customers'    => $this->customerRepo->listCustomersActivated(),
            'usersAll'     => $this->userRepo->allUserList(),
            'status'       => Activity::TYPE_STATE,
            'tags'         => $this->tagRepo->listTagsActived('name','asc',['id','name']),
        ]);
    }
}
