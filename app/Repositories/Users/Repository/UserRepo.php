<?php


namespace App\Repositories\Users\Repository;

use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Customers\Customer;
use App\Repositories\Supervisors\Supervisor;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\Users\Transformations\UserTransformable;
use App\Repositories\UsersHistories\UserHistory;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepo extends BaseRepository implements IUser
{
    use UserTransformable, UploadableTrait,
        TActivityReport, ActivityFilterTrait,
        DatesTrait;

    public function model()
    {
        return User::class;
    }

    public function allUserList(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        $users = $this->model::with('roles','image')
            ->orderBy($orderBy,$sortBy)
            ->get()
            ->map(function ($user){
                return $this->transformToListUser($user);
            });

        return collect($users)->whereNotIn('id',1)->values()->ToArray();

    }

    public function userLessCollaboratorList(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']):Collection
    {
        return $this->model::with('roles')
            ->orderBy($orderBy,$sortBy)
            ->get()
            ->filter(function (User $user){
                return $user->hasAnyRole('admin','supervisor');
            })
            ->transform(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            });
    }

    public function createUser(array $data): User
    {
        $plainPassword          = _add4NumRand($data['name']);
        $data['password_plain'] = $plainPassword;
        $data["password"]       = bcrypt($plainPassword);
        $user                   = $this->model->create($data);
        $this->sendEmailNewCredentials($user);

        return $user;
    }
    public function updateUser(User $user, array $data): void
    {
        $user->update($data);
    }

    public function syncCustomers(User $user, array $params): void
    {
        $user->customers()->sync($params);
    }

    public function detachCustomers(User $user): void
    {
        $user->customers()->detach();
    }

    public function syncRoles(User $user, array $params): void
    {
        $user->roles()->sync($params);
    }

    public function detachRoles(User $user): void
    {
        $user->roles()->detach();
    }

    public function syncSupervise(User $user, array $params): void
    {
        $user->supervise()->sync($params);
    }

    public function detachSupervise(User $user): void
    {
        $user->supervise()->detach();
    }

    public function saveUserImage(User $user,$file,$folder = "users") :void
    {
        $url = $this->handleUploadedImage($file,$folder);
        $user->image()->updateOrCreate([],['url' => $url]);
    }


    public function deleteUser(User $user): bool
    {
        $isDelete = true;
        if ($user->activities()->exists()) {
            $user->update(['status' => false]);
            $isDelete = false;
            _addHistory(UserHistory::DISABLE,"Desactivo al usuario $user->full_name");
        } else {
            _addHistory(UserHistory::DELETE,"Eliminó al usuario $user->full_name");
            $user->customers()->detach();
            $user->supervise()->detach();
            $user->roles()->detach();
            $user->image()->delete();
            $user->histories()->delete();
            $user->documents()->delete();
            $user->delete();
        }
        return $isDelete;
    }

    public function listCustomers(int $user_id, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*'])
    {
        $user = $this->findUserById($user_id);

        if ($user->can_check_all_customers) {
            $customers = Customer::orderBy($orderBy,$sortBy)->where('status',true)->get($columns);
        } else {
            $customers = $user->customers()->orderBy($orderBy,$sortBy)->get($columns);
        }

        return $customers;
    }

    public function listPlannedActivities(int $user_id, $month = null, $year = null)
    {
        $yearAndMonth = Carbon::createFromDate($year, $month,1);

        return $this->findUserById($user_id)->activities()->with('partials','sub_activities','customer','created_by','tag')
            ->where('is_planned',true)
            ->whereMonth('start_date',$yearAndMonth->format('m'))
            ->whereYear('start_date',$yearAndMonth->format('Y'))
            ->orderBy('start_date','asc')
            ->get();

    }

    public function listActivities(int $user_id, $month = null, $year = null)
    {
        $yearAndMonth = Carbon::createFromDate($year, $month,1);

        $counter = $this->findUserById($user_id);

        return $counter->activities()->with('user','sub_activities','customer','created_by', 'tag')
            ->whereMonth('start_date',$yearAndMonth->format('m'))
            ->whereYear('start_date',$yearAndMonth->format('Y'))
            ->orderBy('start_date','asc')
            ->get();
    }


    public function findUserById(int $user_id): User
    {
        return $this->model()::findOrFail($user_id);
    }

    public function usersMonitoring(int $user_id, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']) //TODO OLD
    {
        $user = $this->findUserById($user_id);

        $usersAssig = DB::table('supervisor_user')
                     ->where('supervisor_id',$user->id)
                     ->pluck('user_id');

        $anySupervice = $this->model()::where('can_be_check_all',true)->get()->pluck('id');

        $IDS = collect($usersAssig->merge($anySupervice))->unique()->diff(1);
        if (\Auth::id() !== 1)
            return $this->model()::orderBy($orderBy,$sortBy)->whereIn('id',$IDS)->get($columns);
        else
        return $this->model()::orderBy($orderBy,$sortBy)->get($columns);

    }

    public function listUsersCanSee(array $columns = ['*'])
    {
        $supervisor = Supervisor::find(\Auth::id());

        $usersFreeIds = $this->model::where('can_be_check_all',true)->pluck('id');
        $usersIds = $usersFreeIds->merge($supervisor->users->pluck('id'))->unique()->diff(1);

        if (\Auth::id() !== 1)
            return $this->model::whereIn('id',$usersIds)->orderBy('name','asc')->get($columns);

        return $this->model::orderBy('name','asc')->get($columns);
    }

    //REPORT
    public function reportPlannedVsReal(int $user_id,string $from,string $to)
    {
        return $this->activitiesAllReport($from, $to, $user_id)
            ->groupBy('customer')
            ->map(function ($activities, $customer) {
                return [
                    'customer'           => $customer,
                    'totalEstimatedTime' => $this->totalEstimatedTime(new DatabaseCollection($activities)),
                    'totalRealTime'      => $this->totalRealTime(new DatabaseCollection($activities)),
                    'activities'         => $activities
                ];
            })->values();
    }
    public function reportTimeCustomer(int $counter_id,string $from,string $to)
    {
        $date = "$from / $to";

        return $this->queryActivities($from,$to,$counter_id)
            ->transform(function ($activity) {
                return [
                    'customer'      => $activity->customer->name,
                    'estimatedTime' => $activity->estimatedTime(),
                    'realTime'      => $activity->totalTimeEntered($activity['sub_activities'], $activity['partials']),
                ];
            })
            ->groupBy('customer')
            ->map(function ($activities, $customer) use ($date) {
                return [
                    'date'               => $date,
                    'customer'           => $customer,
                    'totalEstimatedTime' => $this->totalEstimatedTime($activities),
                    'totalRealTime'      => $this->totalRealTime($activities)
                ];
            })->sortBy('customer')->values();
    }

    public function reportTimeCustomerDays(int $user_id,string $from,string $to, array $period)
    {
        return $this->activitiesAllReport($from, $to, $user_id)
            ->groupBy('customer')
            ->map(function ($activities, $customer) use ($period) {
                return [
                    'customer' => $customer,
                    'dates' => $this->addTimeAtDate($period,$activities),
                    'total' => $this->totalRealTime(new DatabaseCollection($activities))
                ];
            })->sortBy('customer')->values();
    }

    public function listUserActive(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection
    {
        $users = $this->model::where('status',true)
                    ->orderBy($orderBy,$sortBy)
                    ->get($columns);
        return $users->whereNotIn('id',1);
    }

    public function listUsersByIds(array $ids, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection
    {
        return $this->model::whereIn('id',$ids)->orderBy($orderBy,$sortBy)->get($columns);
    }


}
