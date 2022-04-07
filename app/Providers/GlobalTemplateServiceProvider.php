<?php

namespace App\Providers;

use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\ActivityRepo;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Settings\Repository\SetupRepo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GlobalTemplateServiceProvider extends ServiceProvider
{
    use ActivityTransformable, ActivityFilterTrait;

    /**
     * @var ActivityRepo|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $activityRepo;
    /**
     * @var ActivityRepo|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $setupRepo;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->activityRepo = resolve(ActivityRepo::class);
        $this->setupRepo = resolve(SetupRepo::class);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer([
            'layouts.auth.app',
            'emails.users.app',
        ], function ($view)
        {
            $view->with([
                'setting' => $this->setupRepo->findSetup()
            ]);
        });

        view()->composer([
            'layouts.admin.app',
        ], function ($view)
        {
            $view->with([
                'userCurrent' => Auth::user(),
                'setting'     => $this->setupRepo->findSetup(),
                'notifications' => $this->_alerts()
            ]);
        });

        view()->composer([
            'layouts.admin.sidebard',
        ], function ($view) {
            $view->with([
                'overdue'=> $this->qytActOverdue(),
            ]);
        });
    }

    private function _alerts()
    {
        $yearAndMonth = Carbon::createFromDate(null, null,1);  // Year and month defaults to current year

        $month = $yearAndMonth->format('m');
        $year = $yearAndMonth->format('Y');

        return Activity::with('user')
            ->whereMonth('start_date',$month)
            ->whereYear('start_date',$year)
            ->where('different_completed_date',true)
            ->whereNull('approved_change_date_by')
            ->latest()
            ->take(6)
            ->get();
    }
    private function qytActOverdue()
    {

        $yearAndMonth = Carbon::createFromDate(null, null,1);  // Year and month defaults to current year

        $month = $yearAndMonth->format('m');
        $year = $yearAndMonth->format('Y');

        $data = Activity::where('is_planned',true)
                        ->whereMonth('start_date',$month)
                        ->whereYear('start_date',$year)
                        ->get()
                        ->transform(function (Activity $activity) {
                            return [
                                'user_id'    => $activity->user_id,
                                'status'     => $activity->currentStatus(),
                                'startDate'  => Carbon::parse($activity->start_date)->format('Y-m-d'),
                                'dueDate'    => Carbon::parse($activity->due_date)->format('Y-m-d'),
                            ];
                        });
        $own = 0;
        if (Auth::user()->hasRole('colaborador')) {
            $own = collect($this->filterOverdue(Carbon::now(),$data->where('user_id',Auth::id())))->count();
        }

        return [
            'general' => collect($this->filterOverdue(Carbon::now(),$data))->count(),
            'own'     => $own
        ];
    }


}
