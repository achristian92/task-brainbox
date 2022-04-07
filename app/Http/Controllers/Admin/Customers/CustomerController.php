<?php


namespace App\Http\Controllers\Admin\Customers;


use App\Exports\CustomerExport;
use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Customers\Customer;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Customers\Requests\StoreCustomerFormRequest;
use App\Repositories\Customers\Requests\UpdateCustomerFormRequest;
use App\Repositories\Tools\BaseRepoCustom;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\UsersHistories\UserHistory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    use DatesTrait;
    use ActivityFilterTrait;

    use BaseRepoCustom;

    private $customerRepo, $userRepo;


    public function __construct(ICustomer $ICustomer,IUser $IUser)
    {
        $this->customerRepo = $ICustomer;
        $this->userRepo = $IUser;
    }

    public function index()
    {
        $list = $this->customerRepo->listCustomers();

        return view('admin.customers.index', [
            'customers' => $list
        ]);
    }

    public function create()
    {
        return view('admin.customers.create',[ 'model' => new Customer ]);
    }

    public function store(StoreCustomerFormRequest $request)
    {
        $customer = $this->customerRepo->createCustomer($request->all());

        if ($request->hasFile('image')) {
            $this->customerRepo->saveCustomerImage($customer,$request->file('image'));
        }

        _addHistory(UserHistory::STORE,"Creó al cliente $customer->name",$customer);

        return redirect()->route('admin.customers.index')->with('success','Nuevo cliente creado.');
    }

    public function edit(int $customer_id)
    {
        return view('admin.customers.edit',[ 'model' => $this->customerRepo->findCustomerById($customer_id) ]);
    }

    public function show(Request $request,Customer $customer)
    {
        $id = $customer->id;
        $dateFormat = $this->getDateFormats($request->input('yearAndMonth'));

        $activities = $this->customerRepo->listActivities($id, $dateFormat['from'], $dateFormat['to']);
        $usersId    = $activities->unique('userId')->pluck('userId');
        $tagsId     = $activities->unique('tagId')->pluck('tagId');

        return view('admin.customers.show',[
            'timeEstimated' => $this->totalEstimatedTime($activities),
            'timeReal'      => $this->totalRealTime($activities),
            'progress'      => $this->calculateProgress($activities),
            'qtyUsers' => count($usersId),
            'resume'    => $this->calculateResume($activities),
            'lineTags'      => $this->lineTags($id,$dateFormat),
            'customer'           => $this->customerRepo->findCustomerById($id),
            'activities'         => $this->applyFilters($activities),
        ]);
    }

    private function applyFilters($activities)
    {
        if (request()->filled('user_id'))
            $activities = $activities->where('userId',request()->input('user_id'));

        if (request()->filled('tag_id'))
            $activities = $activities->where('tagId', request()->input('tag_id'));

        if (request()->filled('status'))
            $activities = $activities->where('status', request()->input('status'));

        return $activities->values();
    }

    private function lineTags(int $id,array $dateFormat)
    {
        $months = $this->getLastMonths($dateFormat);

        $activities = $this->customerRepo->listActivities($id, $months['from'], $dateFormat['to']);

        $calculateActivities = $activities->groupBy('tagName')->map(function ($activities, $tag) use ( $months ) {
            foreach ($months['formatYm'] as $month) {
                $hours[] = $this->totalRealTime($activities->where('startDateMonth',$month));
            }
            return [
                'seriesname' => Str::limit($tag, 15),
                'data' => $this->transformDataSource($hours,'value'),
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($months['names'],'label'),
            'dataset' => $calculateActivities
        ];
    }

    private function transformDataSource(array $data, string $labelorvalue)
    {
        return collect($data)->map(function ($name) use ($labelorvalue){
            return [ $labelorvalue => $name];
        });
    }

    public function update(UpdateCustomerFormRequest $request, int $customer_id)
    {
        $data = $request->all() + ['notify_excess_time' => $request->get('notify_excess_time',false)];
        $this->customerRepo->updateCustomer($data,$customer_id);

        $customer = $this->customerRepo->findCustomerById($customer_id);
        if ($request->hasFile('image')) {
            $this->customerRepo->saveCustomerImage($customer,$request->file('image'));
        }

        _addHistory(UserHistory::UPDATED,"Actualizó al cliente $customer->name",$customer);

        return redirect()->route('admin.customers.index')->with('success','Cliente actualizado.');
    }

    public function destroy(int $customer_id)
    {
        $this->customerRepo->deleteCustomer($customer_id);

        return back()->with('success', '¡Eliminación exitosa!');
    }


    public function import(Request $request)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:xls,xlsx'
        ]);

        Excel::import(new CustomerImport(), $request->file('file_upload'));

        return redirect()->route('admin.customers.index')->with('success', 'Información cargada');
    }
    public function export()
    {
        $customer = Customer::orderBy('name','asc')->get(['name','ruc','address'])->toArray();
        return Excel::download(new CustomerExport($customer), 'LISTA-CLIENTES.xlsx');
    }

}
