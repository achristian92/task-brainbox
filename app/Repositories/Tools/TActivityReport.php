<?php


namespace App\Repositories\Tools;


use App\Repositories\Activities\Activity;
use App\Repositories\PartialActivities\PartialActivity;
use App\Repositories\SubActivities\SubActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

Trait TActivityReport
{
    public function activitiesAllReport($from, $to,$user_id = null, $customer_id = null)
    {
        $activities = $this->queryActivities($from, $to, $user_id , $customer_id );

        $activityIDS = $activities->pluck('id');
        $subActivities = $this->querySubActivities($activityIDS,$from,$to);
        $partials = $this->queryPartialActivities($activityIDS,$from,$to);

        return $this->mergeAllActivitiesRelated($activities,$subActivities,$partials);

    }
    public function transActivityReport(Activity $activity,$reportOnlyPlanneded = false)
    {
        $dateStartOrCompleted = $activity->start_date;
        if (! $reportOnlyPlanneded)
            $dateStartOrCompleted = $activity->isCompletedState() ? $activity->completed_date : $activity->start_date;

        return [
            'isPlanned'     => (bool) $activity->is_planned,
            'name'          => $activity->name,
            'counter'       => $activity->user->full_name,
            'customer'      => $activity->customer->name,
            'startDate'     => Carbon::parse($dateStartOrCompleted)->format('Y-m-d'),
            'startDateFormat'=> Carbon::parse($dateStartOrCompleted)->format('d/m/y'),
            'estimatedTime' => $activity->estimatedTime(),
            'realTime'      => $activity->currentRecordedTime(),
            'statusName'    => $activity->statusName(),
            'color'         => _colorStatusHex($activity->currentStatus()),
            'tag'          =>  $activity->tag->name,
            'description'   => $activity->description,
        ];
    }

    public function transSubActivityReport(SubActivity $subActivity)
    {
        return  [
            'isPlanned'     => false,
            'name'          => '(SubActividad) '. $subActivity->name .'('.$subActivity->activity->name .')',
            'counter'       => $subActivity->activity->user->full_name,
            'customer'      => $subActivity->activity->customer->name,
            'startDate'     => Carbon::parse($subActivity->completed_at)->format('Y-m-d'),
            'estimatedTime' => '00:00',
            'realTime'      => $subActivity->duration,
            'statusName'    => Activity::TYPE_STATE[Activity::TYPE_COMPLETED],
            'color'         => _colorStatusHex(Activity::TYPE_COMPLETED),
            'tag'           => $subActivity->activity->tag->name
        ];
    }

    public function transPartialActivityReport(PartialActivity $partial)
    {
        return  [
            'isPlanned'     => false,
            'name'          => '(Avance) '.strtolower($partial->activity->name),
            'counter'       => $partial->activity->user->full_name,
            'customer'      => $partial->activity->customer->name,
            'startDate'     => Carbon::parse($partial->completed_at)->format('Y-m-d'),
            'estimatedTime' => '00:00',
            'realTime'      => $partial->duration,
            'statusName'    => Activity::TYPE_STATE[Activity::TYPE_COMPLETED],
            'color'         => _colorStatusHex(Activity::TYPE_COMPLETED),
            'tag'          => $partial->activity->tag->name
        ];
    }

    private function mergeAllActivitiesRelated(Collection $activities, Collection $subActivities, Collection $partials)
    {
        $TActivities = $activities->transform(function (Activity $activity) {
            return $this->transActivityReport($activity);
        });

        $TSubActivities = $subActivities->transform(function (SubActivity $subActivity) {
            return $this->transSubActivityReport($subActivity);
        });

        $TPartials = $partials->transform(function (PartialActivity $partial) {
            return $this->transPartialActivityReport($partial);
        })->toArray();

        return collect($TActivities)->merge(collect($TSubActivities))->merge($TPartials);
    }

    /* queries */
    public function queryActivities(string $from,string $to,int $user_id = null,int $customer_id = null,bool $onlyPlanned = false)
    {
        $activities = Activity::with('customer','user','tag','sub_activities','partials')
                        ->whereDate('start_date','>=',$from)
                        ->whereDate('due_date','<=',$to)
                        ->orderBy('start_date')
                        ->get();

        if ($user_id !== null) {
            $activities = $activities->where('user_id', $user_id);
        }

        if ($customer_id !== null) {
            $activities = $activities->where('customer_id', $customer_id);
        }

        if ($onlyPlanned) {
            $activities = $activities->where('is_planned', true);
        }

        return $activities;

    }

    public function querySubActivities($activitiesIDS,$from, $to)
    {
        return SubActivity::with('activity','activity.tag','activity.customer','activity.user')
                ->whereIn('activity_id',$activitiesIDS)
                ->whereDate('completed_at','>=',$from)
                ->whereDate('completed_at','<=',$to)
                ->orderBy('completed_at')
                ->get();

    }

    public function queryPartialActivities($activitiesIDS,$from, $to)
    {
        return PartialActivity::with('activity','activity.tag','activity.customer','activity.user')
                ->whereIn('activity_id',$activitiesIDS)
                ->whereDate('completed_at','>=',$from)
                ->whereDate('completed_at','<=',$to)
                ->orderBy('completed_at')
                ->get();
    }

}
