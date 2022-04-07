<?php


namespace App\Repositories\Customers\Repository;

use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Customers\Customer;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\UsersHistories\UserHistory;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class CustomerRepo extends BaseRepository implements ICustomer
{
    use UploadableTrait, DatesTrait, TActivityReport,ActivityTransformable;
    use ActivityFilterTrait;

    public function model(): string
    {
        return Customer::class;
    }

    public function listCustomers(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model::with('image','users')
                            ->orderBy($orderBy,$sortBy)
                            ->get($columns);
    }

    public function createCustomer(array $data): Customer
    {
        return  $this->model->create($data);
    }

    public function saveCustomerImage(Customer $customer,$file,$folder = "customers") :void
    {
        $url = $this->handleUploadedImage($file,$folder);
        $customer->image()->updateOrCreate([],['url' => $url]);
    }

    public function findCustomerById(int $customer_id): Customer
    {
        return $this->model->findOrFail($customer_id);
    }

    public function updateCustomer(array $data, int $customer_id): bool
    {
        $customer = $this->findCustomerById($customer_id);
        return $customer->update($data);
    }

    public function deleteCustomer(int $customer_id): bool
    {
        $customer = $this->findCustomerById($customer_id);

        if (! $customer->activities()->exists()) {
            $customer->users()->detach();
            $customer->forceDelete();
            _addHistory(UserHistory::DELETE,"Eliminó al cliente $customer->name",$customer);
            return true;
        }

        _addHistory(UserHistory::DISABLE,"Desahilitado al cliente $customer->name",$customer);
        return $customer->update(['status' => false]);
    }

    public function listCustomersActivated(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        return $this->model->where('status',true)->orderBy($orderBy,$sortBy)->get($columns);
    }

    public function reportHorasMonth(string $from, string $to, array $rangeDates)
    {
        return $this->activitiesAllReport($from, $to)
                ->groupBy('customer')
                ->map(function ($activities, $customer) use ($rangeDates) {
                    return [
                        'customer'  => $customer,
                        'total' => _sumTime($this->addTimeAtDate($rangeDates,$activities)),
                        'dates' => $this->addTimeAtDate($rangeDates,$activities)
                    ];
                })->sortBy('customer');
    }

    public function reportactivityTag(int $id,string $from,string $to)
    {
        return $this->activitiesAllReport($from, $to,null,$id)
            ->groupBy('tag')
            ->map(function ($activities, $tag) {
                return [
                    'tag'                => $tag,
                    'totalEstimatedTime' => $this->totalEstimatedTime(new DatabaseCollection($activities)),
                    'totalRealTime'      => $this->totalRealTime(new DatabaseCollection($activities)),
                    'activities'         => $activities
                ];
            })->values();
    }

    public function searchCustomer(string $text): Collection
    {
        return $this->model->searchCustomer($text);
    }

    public function listActivities(int $id, string $from, string $to): Collection
    {

        return $this->findCustomerById($id)
                    ->activities()
                    ->with('user','partials','sub_activities','customer','created_by', 'tag')
                    ->whereDate('start_date','>=',$from)
                    ->whereDate('due_date','<=',$to)
                    ->orderBy('start_date')
                    ->get()
            ->transform(function ($activity) {
               return $this->transformActivityGeneral($activity);
            });
    }
}
