<?php

namespace App\Http\Controllers\Admin\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tools\DatesTrait;
use Carbon\Carbon;

class ImboxControllerv2 extends Controller
{
    use DatesTrait;
    use ActivityTransformable, ActivityFilterTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $request = [
            'month' => request()->input('yearAndMonth',now()),
            'view' => request()->input('typeTab','today')
        ];

        $dateFormat = $this->getDateFormats($request['month']);


        $transListToGeneral = $this->activityRepo->listOfActivities($dateFormat['from'],$dateFormat['to'])->transform(function ($activity) {
            return $this->transformActivityGeneral($activity);
        });


        $filterByTab      = $this->applyFilterByTypeTab($transListToGeneral,$request['view']);
        $filterActPartial = $request['view'] !== 'evaluation' ? $transListToGeneral->where('status',Activity::TYPE_PARTIAL) : [];


        return view('admin.imboxv2.index',[
            'activities' => collect($filterByTab)->merge(collect($filterActPartial))->unique('id'),
            'title' => $this->title_view_es($request['view']),
        ]);
    }

    private function title_view_es($view = 'today'): string
    {
        if ($view === 'proximate')
            $title_es = 'Actividades próximas';
        else if ($view === 'overdue')
            $title_es = 'Actividades vencidas';
        else if ($view === 'evaluation')
            $title_es = 'Actividades por evaluar';
        else
            $title_es = 'Actividades de hoy';

        return $title_es;
    }
}
