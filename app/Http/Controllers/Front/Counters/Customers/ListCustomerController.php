<?php

namespace App\Http\Controllers\Front\Counters\Customers;

use App\Http\Controllers\Controller;
use App\Repositories\Users\Repository\IUser;

class ListCustomerController extends Controller
{
    /**
     * @var IUser
     */
    private $userRepo;

    public function __construct(IUser $IUser)
    {
        $this->userRepo = $IUser;
    }
    public function __invoke($user_id)
    {
        return response()->json([
            'customers' => $this->userRepo->listCustomers($user_id,'name','asc',['customers.id','customers.name']),
        ]);
    }
}
