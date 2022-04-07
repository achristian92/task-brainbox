<?php


namespace App\Http\Controllers\Front\Customers;


use App\Http\Controllers\Controller;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Users\Repository\IUser;
use App\User;
use Response;

class AssignedUsersController extends Controller
{
    private $userRepo;
    private $customerRepo;

    public function __construct(IUser $IUser, ICustomer $ICustomer)
    {
        $this->userRepo = $IUser;
        $this->customerRepo = $ICustomer;
    }

    public function __invoke(int $customer_id)
    {
        $assignedUsersAllCustomer = $this->userRepo->listUserActive()->where('can_check_all_customers',true)->pluck('id');
        $customer = $this->customerRepo->findCustomerById($customer_id)->users()->pluck('users.id');
        $usersIDS = $assignedUsersAllCustomer->merge($customer)->unique();


        $users = User::whereIn('id',$usersIDS)->orderBy('name')->get();
        $view = view('admin.customers.partials.assigned-users',compact('users'))->render();

        return Response::json(['status' => 200, 'view' => $view,'qty' => $usersIDS->count() ]);

    }

}
