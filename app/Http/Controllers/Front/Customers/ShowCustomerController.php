<?php


namespace App\Http\Controllers\Front\Customers;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Users\Repository\IUser;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShowCustomerController extends Controller
{
    use DatesTrait;
    use ActivityFilterTrait;

    private $customerRepo;
    private $userRepo;
    private $tagRepo;

    public function __construct(ICustomer $ICustomer,IUser $IUser,ITag $ITag)
    {
        $this->customerRepo = $ICustomer;
        $this->userRepo = $IUser;
        $this->tagRepo = $ITag;
    }

    public function __invoke(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $dateFormat = $this->getDateFormats($request->input('yearAndMonth'));

        $activities = $this->customerRepo->listActivities($id, $dateFormat['from'], $dateFormat['to']);
        $usersId    = $activities->unique('userId')->pluck('userId');
        $tagsId     = $activities->unique('tagId')->pluck('tagId');

        return response()->json([
            'cardStats'         => [
                'timeEstimated' => $this->totalEstimatedTime($activities),
                'timeReal'      => $this->totalRealTime($activities)
            ],
            'charts'            => [
                'progress'      => $this->calculateProgress($activities),
                'activities'    => $this->calculateResume($activities),
                'lineTags'      => $this->lineTags($id,$dateFormat),
            ],
            'filters'            => [
                'users'          => $this->userRepo->listUsersByIds($usersId->toArray(),'name','asc',['id','name','last_name']),
                'tags'           => $this->tagRepo->listTagsByIds($tagsId->toArray(),'name','asc',['id','name']),
                'status'         => Activity::getStatusesList()
            ],
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

}
