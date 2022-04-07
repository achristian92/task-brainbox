<?php

namespace App\Http\Controllers\Front\Imbox;

use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Http\Request;

class ImboxController extends Controller
{
    use DatesTrait;
    use ActivityTransformable, ActivityFilterTrait;

    private $activityRepo;

    public function __construct(IActivity $IActivity)
    {
        $this->activityRepo = $IActivity;
    }
    public function __invoke(Request $request)
    {

        $dateFormat = $this->getDateFormats(request()->input('yearAndMonth'));

        $transListToGeneral = $this->activityRepo->listOfActivities($dateFormat['from'],$dateFormat['to'])->transform(function ($activity) {
            return $this->transformActivityGeneral($activity);
        });

        if ($request->filled('myImboxId')) {
            $transListToGeneral     = $transListToGeneral->where('userID',$request->get('myImboxId'));
        }

        $filterByTab      = $this->applyFilterByTypeTab($transListToGeneral,$request->input('typeTab','today'));
        $filterActPartial = $transListToGeneral->where('status',Activity::TYPE_PARTIAL);

        return response()->json([
            'activities' => collect($filterByTab)->merge(collect($filterActPartial))->unique('id')
        ]);
    }

}
