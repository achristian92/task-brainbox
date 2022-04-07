<?php

namespace App\Http\Controllers\Counter\Planned;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;

class PlannedController extends Controller
{
    private $tagRepo;
    private $userRepo;

    public function __construct (ITag $ITag,IUser $IUser) {
        $this->tagRepo = $ITag;
        $this->userRepo = $IUser;
    }

    public function __invoke()
    {
        return view('counter.planned.index', [
            'customers'  => $this->userRepo->listCustomers($this->currentUser()->id,'name','asc',['customers.id','customers.name']),
            'status'     => Activity::TYPE_STATE,
            'tags'       => $this->tagRepo->listTagsActived('name','asc',['id','name']),
        ]);
    }
}
