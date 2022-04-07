<?php


namespace App\Repositories\Activities\Transformations;

use App\Repositories\Activities\Activity;
use Carbon\Carbon;
use \Illuminate\Support\Collection;
use Illuminate\Http\Request;

trait ActivityFilterTrait
{
    public function calculateProgress(Collection $activities): float
    {
        if ($activities->isEmpty()) return 0;

        return round(((self::qtyCompleted($activities) / $activities->count()) * 100 ),1);
    }

    public function calculateResume(Collection $activities): array
    {
        return [
            'total'        => $activities->count(),
            'qtyOverdue'   => self::qtyOverdue($activities),
            'qtyCompleted' => self::qtyCompleted($activities),
            'qtyPending'   => self::qtyPending($activities)
        ];
    }


    public function filterByDate(Carbon $date, Collection $activities): array
    {
        return $activities->filter(function ($activity) use ($date) {
            return $activity['startDate'] === $date->format('Y-m-d') ||
                  ($date->format('Y-m-d') >= $activity['startDate'] && $date->format('Y-m-d') <= $activity['dueDate']);
        })->values()->all();
    }
    public function filterAfterDate(Carbon $date, Collection $activities): array
    {
        return  $activities->where('startDate','>', $date->format('Y-m-d'))->sortBy('startDate')->values()->all();
    }
    public function filterOverdue(Carbon $date, Collection $activities): array
    {
        return $activities->filter(function ($activity) use ($date) {
            return $activity['status'] != Activity::TYPE_COMPLETED && $activity['dueDate'] < $date->format('Y-m-d');
        })->sortByDesc('startDate')->values()->all();
    }
    public function filterEvaluation(Collection $activities): array
    {
        return $activities->filter(function ($activity) {
            return $activity['diff_completed_date'] && !$activity['changeDateBy'] && $activity['status'] !== "partial" ;
        })->sortByDesc('startDate')->values()->all();
    }


    public function qtyOverdue(Collection $activities):int
    {
        return collect(self::filterOverdue(Carbon::now(),$activities))->count();
    }

    public function qtyPending(Collection $activities):int
    {
        $allPending = $activities->where('status','!=',Activity::TYPE_COMPLETED)->count();
        $overdue = self::qtyOverdue($activities);
        return $allPending - $overdue;
    }

    public function qtyApproved(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_APPROVED)->count();
    }

    public function qtyPlanned(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_PLANNED)->count();
    }

    public function qtyCompleted(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_COMPLETED)->count();
    }

    public function qtyPartial(Collection $activities):int
    {
        return $activities->where('status',Activity::TYPE_PARTIAL)->count();
    }

    public function totalEstimatedTime(Collection $activities):string
    {
        return _sumTime($activities->pluck('estimatedTime')->toArray());
    }

    public function totalRealTime(Collection $activities):string
    {
        return _sumTime($activities->pluck('realTime')->toArray());
    }


    public function applyFilterByTypeTab(Collection $activities,$typeTab = 'today')
    {
        if ($typeTab === 'today') {
            return $this->filterByDate(Carbon::now(),$activities);
        } else if ($typeTab === 'proximate') {
            return $this->filterAfterDate(Carbon::now(),$activities);
        } else if ($typeTab === 'overdue') {
            return $this->filterOverdue(Carbon::now(),$activities);
        } else if ($typeTab === 'evaluation') {
            return $this->filterEvaluation($activities);
        }

        return $activities;
    }

}
