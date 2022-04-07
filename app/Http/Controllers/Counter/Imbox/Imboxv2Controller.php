<?php

namespace App\Http\Controllers\Counter\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use Illuminate\Support\Facades\Auth;

class Imboxv2Controller extends Controller
{
    use DatesTrait;
    use ActivityTransformable, ActivityFilterTrait;

    private $userRepo, $activityRepo, $tagRepo;

    public function __construct(IUser $IUser, ITag $ITag, IActivity $IActivity)
    {
        $this->userRepo = $IUser;
        $this->tagRepo = $ITag;
        $this->activityRepo = $IActivity;
    }

    public function __invoke()
    {
        $request = [
            'month' => request()->input('yearAndMonth',now()),
            'view' => request()->input('typeTab','today'),
            'myImboxId' => Auth::id()
        ];

        $dateFormat = $this->getDateFormats($request['month']);


        $transListToGeneral = $this->activityRepo->listOfActivities($dateFormat['from'],$dateFormat['to'])->transform(function ($activity) {
            return $this->transformActivityGeneral($activity);
        })->where('userID',$request['myImboxId']);


        $filterByTab      = $this->applyFilterByTypeTab($transListToGeneral,$request['view']);
        $filterActPartial = $transListToGeneral->where('status',Activity::TYPE_PARTIAL);

        return view('counter.imbox.indexv2', [
            'myImbox'   => $this->currentUser()->id,
            'customers' => $this->userRepo->listCustomers($this->currentUser()->id,'name','asc',['customers.id','customers.name']),
            'tags'      => $this->tagRepo->listTagsActived(),
            'activities'=> collect($filterByTab)->merge(collect($filterActPartial))->unique('id'),
            'title'     => $this->title_view_es($request['view']),
            'tab'       => $request['view']

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
