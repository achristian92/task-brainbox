<?php

namespace App\Http\Controllers\Counter\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;

class ImboxController extends Controller
{
    private $tagRepo;
    private $userRepo;

    public function __construct(IUser $IUser, ITag $ITag)
    {
        $this->userRepo = $IUser;
        $this->tagRepo = $ITag;
    }

    public function __invoke()
    {
        return view('counter.imbox.index', [
            'myImbox'   => $this->currentUser()->id,
            'customers' => $this->userRepo->listCustomers($this->currentUser()->id,'name','asc',['customers.id','customers.name']),
            'tags'      => $this->tagRepo->listTagsActived()
        ]);
    }
}
