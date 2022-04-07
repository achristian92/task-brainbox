<?php


namespace App\Repositories\Activities\Repository;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\UsersHistories\UserHistory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class ActivityRepo extends BaseRepository implements IActivity
{
    use ActivityTransformable, TActivityReport, DatesTrait, ActivityFilterTrait;

    public function model()
    {
        return Activity::class;
    }

    public function findActivityById(int $activity_id): Activity
    {
        return $this->model()::with('sub_activities','partials','histories')->findOrFail($activity_id);
    }

    public function createActivity(array $params): Activity
    {
        $createdBy = [
            'created_by_id' => Auth::id(),
            'created_date'  => Carbon::now(),
            'is_assign'     => $params['user_id'] !== Auth::id()
        ];
        $activity = $this->model->create($params + $createdBy);

        $this->saveHistory($activity,'Actividad creada');
        _addHistory(UserHistory::STORE,"Actividad creada $activity->name",$activity);

        return $activity;
    }

    public function updateActivity(array $params, int $activity_id): bool
    {
        $updatedBy = [
            'updated_by_id' => Auth::id(),
            'updated_date'  => now(),
        ];

        $activityUpdate = $this->findActivityById($activity_id);
        $activityUpdate->update($params + $updatedBy);

        $activity = $this->findActivityById($activity_id);

        $this->saveHistory($activity, "Actividad actualizada");
        _addHistory(UserHistory::UPDATED,"Actividad actualizada $activity->name",$activity);

        return true;
    }

    public function approve(int $activity_id)
    {
        $approvedBy = [
            'status'         => Activity::TYPE_APPROVED,
            'approved_by_id' => Auth::id(),
            'approved_date'  => now()
        ];

        $activity = $this->findActivityById($activity_id);
        _addHistory(UserHistory::APPROVED,"Aprobó la actividad $activity->name",$activity);
        $this->saveHistory($activity, "Actividad aprobada");
        return $activity->update($approvedBy );

    }

    public function deleteActivity(int $activity_id): bool
    {
        $activity = $this->findActivityById($activity_id);
        $activity->histories()->delete();
        $activity->sub_activities()->delete();
        $activity->partials()->delete();
        _addHistory(UserHistory::DELETE,"Eliminó la actividad $activity->name",$activity);
        return $activity->delete();
    }

    public function returnPlannedStatus(int $activity_id): void
    {
        $activity = $this->findActivityById($activity_id);
        $activity->sub_activities()->delete();
        $activity->partials()->delete();

        $activity->update([
            'status'         => Activity::TYPE_PLANNED,
            'time_real'      => '00:00',
            'approved_by_id' => null,
            'approved_date'  => null,
            'is_partial'     => false
        ]);

        _addHistory(UserHistory::REVERSE,"Retornó la actividad planeada $activity->name",$activity);
        $this->saveHistory($activity,'Actividad revertida');
    }

    public function resetActivityApproved(int $activity_id): void
    {
        $activity = $this->findActivityById($activity_id);
        $activity->sub_activities()->delete();
        $activity->partials()->delete();

        $activity->update([
            'status'            => Activity::TYPE_APPROVED,
            'time_real'         => "00:00",
            'is_partial'        => false,
            'with_subactivities'=> false,
            'completed_date'    => null
        ]);

        $this->saveHistory($activity,'Actividad restablecida');
        _addHistory(UserHistory::RESET,"Reseteó a aprobado la actividad $activity->name",$activity);
    }


    public function saveHistory(Activity $activity, string $description): void
    {
        $activity->histories()->create([
            'user'          => Auth::user()->full_name,
            'description'   => $description,
            'registered_at' => Carbon::now()
        ]);
    }

    public function saveDurationPartial(Activity $activity, string $duration = '00:00'):void
    {
        $activity->partials()->create([
            'duration'     => $duration,
            'completed_at' => Carbon::now()
        ]);
    }

    public function listActivities($month = null, $year = null) //TODO OLD
    {
        $yearAndMonth = Carbon::createFromDate($year, $month,1);  // Year and month defaults to current year

        $month = $yearAndMonth->format('m');
        $year = $yearAndMonth->format('Y');

        return $this->model->with('customer','created_by', 'tag','user','sub_activities','partials')
            ->whereMonth('start_date',$month)
            ->whereYear('start_date',$year)
            ->orderBy('start_date','desc')
            ->get();
    }

    public function listOfActivities(string $from, string $to)
    {
        return $this->model->with('customer','created_by', 'tag','user','sub_activities','partials')
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date','desc')
            ->get();
    }

    public function reportListCounterWorkedCustomer(int $customer_id, string $from,string $to, array $rangeDates)
    {
        return $this->activitiesAllReport($from,$to,null,$customer_id)
                    ->groupBy('counter')
                    ->map(function ($activities, $counter) use ($rangeDates) {
                        return [
                           'counter' => $counter,
                           'dates' => $this->addTimeAtDate($rangeDates,$activities),
                           'total' => $this->totalRealTime(new Collection($activities))
                        ];
                    })->sortBy('counter')->values();
    }

    public function reportActivities(string $from, string $to)
    {
        return  $this->activitiesAllReport($from,$to)->sortBy('startDate');
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULED TASKS
    |--------------------------------------------------------------------------
    */
    public function getListPlannedYesterday()
    {
        return $this->model->with('user','partials')
            ->where('is_planned',true)
            ->whereDate('start_date',Carbon::yesterday()->format('Y-m-d'))
            ->orderBy('start_date','desc')
            ->get()
            ->transform(function (Activity $activity) {
                return [
                        'id' => $activity->id,
                    'userID' => $activity->user_id,
                  'userName' => $activity->user->full_name,
                    'status' => $activity->currentStatus(),
                ];
        });
    }

    public function getListPlannedDeadLine()
    {
        return $this->model->with('user','partials')
            ->where('is_planned',true)
            ->whereDate('start_date',Carbon::yesterday()->format('Y-m-d'))
            ->orderBy('start_date','desc')
            ->get()
            ->transform(function (Activity $activity) {
                return [
                    'id' => $activity->id,
                    'userID' => $activity->user_id,
                    'userName' => $activity->user->full_name,
                    'status' => $activity->currentStatus(),
                ];
            });
    }

    public function getActivitiesWithDeadline(): array
    {
        return $this->model->with('user','customer')
            ->where('is_planned',true)
            ->whereDate('deadline',Carbon::tomorrow()->format('Y-m-d'))
            ->where('status','!=',Activity::TYPE_COMPLETED)
            ->orderBy('start_date','desc')
            ->get()
            ->transform(function (Activity $activity) {
                return [
                    'id'       => $activity->id,
                    'activity' => Str::limit(strtoupper($activity->name),35),
                    'time'     => $activity->time_estimate,
                    'customer' => Str::limit(strtoupper($activity->customer->name),25),
                    'deadline' => Carbon::parse($activity->deadline)->format('d/m'),
                    'userID'   => $activity->user_id,
                    'userName' => $activity->user->full_name,
                    'userEmail'=> $activity->user->email,
                ];
            })->groupBy('userName')->transform(function ($activities, $user) {
                return [
                    'user'       => $user,
                    'email'      => $activities[0]['userEmail'],
                    'deadline'   => $activities[0]['deadline'],
                    'totalAct'   => $activities->count(),
                    'activities' => $activities->toArray()
                ];
            })->values()->toArray();
    }
    public function getActivitiesByApproval(): array
    {
        return $this->model->with('user')
            ->whereDate('start_date',Carbon::yesterday()->format('Y-m-d'))
            ->where('different_completed_date',true)
            ->whereNull('approved_change_date_by')
            ->latest()
            ->get()
            ->transform(function (Activity $activity) {
            return [
                'userName' => $activity->user->full_name,
            ];
            })->groupBy('userName')->transform(function ($activities, $user) {
                return [
                    'user' => $user,
                    'qty'  => $activities->count(),
                ];
            })->values()->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function listActivitiesForStatistics(string $from, string $to)
    {
        return $this->model::with('customer','user','tag','sub_activities','partials')
            ->whereDate('start_date','>=',$from)
            ->whereDate('due_date','<=',$to)
            ->orderBy('start_date')
            ->get()
            ->transform(function ($activity) {
                return [
                    'customerId'     => $activity->customer->id, //for redirect
                    'customerName'   => $activity->customer->name, //for customer more and less hours
                    'userId'         => $activity->user->id, // for redirect
                    'userName'       => $activity->user->full_name,
                    'status'         => $activity->currentStatus(), //for progress, resume
                    'statusName'     => $activity->statusName(),
                    'tagName'        => $activity->tag->name, //for porcentage by tag
                    'estimatedTime'  => $activity->estimatedTime(),
                    'realTime'       => $activity->totalTimeEntered($activity['sub_activities'],$activity['partials']),
                    'startDateMonth' => Carbon::parse($activity->start_date)->format('Y-m'), //filter last months(agroup)
                    'startDate'      => Carbon::parse($activity->start_date)->format('Y-m-d'),
                    'dueDate'        => Carbon::parse($activity->due_date)->format('Y-m-d'), //for Resume
                ];
            });
    }



}
