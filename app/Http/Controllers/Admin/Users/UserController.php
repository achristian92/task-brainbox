<?php


namespace App\Http\Controllers\Admin\Users;

use App\Repositories\Customers\Repository\ICustomer;
use App\Http\Controllers\Controller;
use App\Repositories\Documents\Document;
use App\Repositories\Tools\BaseRepoCustom;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Requests\StoreUserRequest;
use App\Repositories\Users\Requests\UpdateUserRequest;
use App\Repositories\Users\Transformations\UserTransformable;
use App\Repositories\UsersHistories\UserHistory;
use App\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use BaseRepoCustom, UserTransformable;

    private $userRepo;
    private $customerRepo;

    public function __construct(IUser $IUser, ICustomer $ICustomer)
    {
        $this->userRepo = $IUser;
        $this->customerRepo = $ICustomer;

    }

    public function index()
    {
        $users = $this->userRepo->allUserList();

        return view('admin.users.index',compact('users'));
    }

    public function create()
    {
        return view('admin.users.create',[
            'users'     => $this->userRepo->userLessCollaboratorList(),
            'customers' => $this->customerRepo->listCustomersActivated(),
            'roles'     => Role::all(),
            'model'     => new User()
        ]);
    }
    public function edit(User $user)
    {
        return view('admin.users.edit',[
            'users'            => $this->userRepo->userLessCollaboratorList(),
            'rolesIDSUser'     => $user->roles()->pluck('id')->toArray(),
            'customerIDSUser'  => $user->customers()->pluck('customers.id')->toArray(),
            'superviseIDSUser' => $user->supervise()->pluck('users.id')->toArray(),
            'customers'        => $this->customerRepo->listCustomersActivated(),
            'roles'            => Role::all(),
            'model'            => $user
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepo->createUser($request->all());

        if ($request->hasFile('image')) {
            $this->userRepo->saveUserImage($user,$request->file('image'));
        }

        if ($request->has('roles')) {
            $this->userRepo->syncRoles($user, $request->input('roles'));
        } else {
            $this->userRepo->detachRoles($user);
        }

        if (! $request->has('can_be_check_all')) {
            if ($request->has('superviseBy')) {
                $this->userRepo->syncSupervise($user, $request->input('superviseBy'));
            } else {
                $this->userRepo->detachSupervise($user);
            }
        }

        if (! $request->has('can_check_all_customers')) {
            if ($request->has('customers')) {
                $this->userRepo->syncCustomers($user, $request->input('customers'));
            } else {
                $this->userRepo->detachCustomers($user);
            }
        }

        _addHistory(UserHistory::STORE,"Creó al usuario $user->full_name",$user);

        return redirect()->route('admin.users.index')->with('success',"Usuario creado");
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->all();
        $data['can_be_check_all'] = $request->get('can_be_check_all',false);
        $data['can_check_all_customers'] = $request->get('can_check_all_customers',false);

        $this->userRepo->updateUser($user, $data);

        if ($request->hasFile('image')) {
            $this->userRepo->saveUserImage($user,$request->file('image'));
        }

        if ($request->has('roles')) {
            $this->userRepo->syncRoles($user, $request->input('roles'));
        } else {
            $this->userRepo->detachRoles($user);
        }

        if (!$data['can_be_check_all']) {
            if ($request->has('superviseBy')) {
                $this->userRepo->syncSupervise($user, $request->input('superviseBy'));
            } else {
                $this->userRepo->detachSupervise($user);
            }
        }

        if (! $data['can_check_all_customers']) {
            if ($request->has('customers')) {
                $this->userRepo->syncCustomers($user, $request->input('customers'));
            } else {
                $this->userRepo->detachCustomers($user);
            }
        }

        _addHistory(UserHistory::UPDATED,"Actualizó al usuario $user->full_name",$this->userRepo->findUserById($user->id));

        return redirect()->route('admin.users.index')->with('success',"Usuario actualizado");
    }

    public function enable(User $user)
    {
        $user->update(['status'=> true]);

        _addHistory(UserHistory::ENABLE,"Habilitó al usuario $user->full_name");

        return redirect()->route('admin.users.index')->with('success','Usuario activado.');
    }

    public function disable(User $user)
    {
        $user->update(['status'=> false]);

        _addHistory(UserHistory::DISABLE,"Desahabilitó al usuario $user->full_name");

        return redirect()->route('admin.users.index')->with('success','Usuario desactivado.');
    }
    public function delete(User $user)
    {
        $message = 'Usuario eliminado';
        $message = $this->userRepo->deleteUser($user) ? $message : 'Usuario desactivado';

        return redirect()->route('admin.users.index')->with('success',$message);
    }

    public function sendCredentials(User $user)
    {
        $this->sendEmailNewCredentials($user);

        return redirect()->route('admin.users.index')->with('success','Credenciales enviado.');
    }

    public function history(int $user_id)
    {
        $histories = UserHistory::search(request()->input('q'))
                                ->where('user_id',$user_id)
                                ->orderBy('created_at','desc')
                                ->get();
        return view('admin.users.history',[
            'user' => $this->userRepo->findUserById($user_id),
            'histories' => $histories
        ]);
    }
    public function documents(int $user_id)
    {
        $documents = Document::where('user_id',$user_id)
                            ->orderBy('created_at','desc')
                            ->get();
        return view('admin.users.documents',[
            'user' => $this->userRepo->findUserById($user_id),
            'documents' => $documents
        ]);
    }

}
