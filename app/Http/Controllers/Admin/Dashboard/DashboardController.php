<?php


namespace App\Http\Controllers\Admin\Dashboard;


use App\Http\Controllers\Controller;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\Tools\DatesTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{

    use ActivityTransformable, ActivityFilterTrait, DatesTrait;

    private $activityRepo, $customerRepo;

    public function __construct(IActivity $IActivity,ICustomer $ICustomer)
    {
        $this->activityRepo = $IActivity;
        $this->customerRepo = $ICustomer;
    }

    public function index(Request $request)
    {
        $qtyShow = $request->input('qtyShow',5);

        $dateFormat = $this->getDateFormats($request->input('yearAndMonth'));

        $activities =  $this->activityRepo->listActivitiesForStatistics($dateFormat['from'],$dateFormat['to']);

        $filtersActivities = $activities->whereIn('customerId',$request->input('customer_ids',[]));

        return view('admin.dashboard.index',[
            'customers'         => $this->customerRepo->listCustomersActivated('name','asc',['id','name']),
            'progress'          => $this->calculateProgress($activities),
            'resume'            => $this->calculateResume($activities),
            'customerMoreHours' => $this->customersByTotalHours($activities, $qtyShow)['moreHours'],
            'customerLessHours' => $this->customersByTotalHours($activities, $qtyShow)['lessHours'],
            'userMoreHours'     => $this->usersByTotalHours($activities, $qtyShow)['moreHours'],
            'userLessHours'     => $this->usersByTotalHours($activities, $qtyShow)['lessHours'],
            'tagPercentage'     => $this->tagPercentage($activities),
            'lineTags'          => $this->lineTags($dateFormat),
            'activities'        => $this->qtyActivitiesByCustomers($filtersActivities),
            'hours'             => $this->qtyHoursByCustomers($filtersActivities),
            'period'            => $this->historiesHoursMonthCustomers($dateFormat)
        ]);
    }

    private function lineTags(array $dateFormat)
    {
        $months = $this->getLastMonths($dateFormat);

        $activities = $this->activityRepo->listActivitiesForStatistics($months['from'], $dateFormat['to']);

        $calculateActivities = $activities->groupBy('tagName')->map(function ($activities, $tag) use ( $months ) {
            foreach ($months['formatYm'] as $month) {
                list($hour, $minute) = explode(':', $this->totalRealTime($activities->where('startDateMonth',$month)));
                $hours[] = $hour;
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

    private function tagPercentage(Collection $activities)
    {
        return $activities->groupBy('tagName')
            ->map(function ($activities, $tag) {
                list($hour, $minute) = explode(':', $this->totalRealTime($activities));
                return [
                    'label' => $tag,
                    'value' => $hour
                ];
            })->values();
    }

    private function usersByTotalHours(Collection $activities, int $qtyShow)
    {
        $dataAll =  $activities->groupBy('userName')
            ->map(function ($activities, $userName) {
                list($hour, $minute) = explode(':', $this->totalRealTime($activities));
                return [
                    'label'     => $userName,
                    "labelLink" => route('admin.tracks.show',$activities->first()['userId']),
                    'value'     => $hour
                ];
            })->sortBy('value')->values();

        return [
            'moreHours' => $dataAll->take(-$qtyShow)->sortByDesc('value')->values(),
            'lessHours' => $dataAll->take($qtyShow)
        ];
    }

    private function customersByTotalHours(Collection $activities, int $qtyShow) : array
    {
        $dataAll =  $activities->groupBy('customerName')
            ->map(function ($activities, $customerName) {
                list($hour, $minute) = explode(':', $this->totalRealTime($activities));

                return [
                    'label'     => $customerName,
                    "labelLink" => route('admin.customers.show',$activities->first()['customerId']),
                    'value'     => $hour
                ];
            })->sortBy('value')->values();

        return [
            'moreHours' => $dataAll->take(-$qtyShow)->sortByDesc('value')->values(),
            'lessHours' => $dataAll->take($qtyShow)
        ];
    }

    private function qtyActivitiesByCustomers(Collection $data): array
    {
        $calculateActivities = $data->groupBy('customerName')->map(function ($activities, $customer) {
            return [
                'customer' => Str::limit($customer,15),
                'totalAct' => $activities->count(),
                'completedAct' => $this->qtyCompleted($activities)
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($calculateActivities->pluck('customer')->toArray(),'label'),
            'dataset' => [
                [
                    'seriesname' => "Total",
                    'data' => $this->transformDataSource($calculateActivities->pluck('totalAct')->toArray(),'value'),
                ],
                [
                    'seriesname' => "Completados",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('completedAct')->toArray(),'value'),
                ]
            ]
        ];
    }
    private function qtyHoursByCustomers(Collection $data): array
    {
        $calculateActivities = $data->groupBy('customerName')->map(function ($activities, $customer) {
            return [
                'customer' => Str::limit($customer,15),
                'estimatedHours' => $this->totalEstimatedTime($activities),
                'realHours' => $this->totalRealTime($activities)
            ];
        })->values();

        return [
            'categories' => $this->transformDataSource($calculateActivities->pluck('customer')->toArray(),'label'),
            'dataset' => [
                [
                    'seriesname'=> "Estimado",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('estimatedHours')->toArray(),'value'),
                ],
                [
                    'seriesname'=> "Real",
                    'data'=> $this->transformDataSource($calculateActivities->pluck('realHours')->toArray(),'value'),
                ]
            ]
        ];
    }

    private function historiesHoursMonthCustomers(array $dateFormat)
    {
        $months = $this->getLastMonths($dateFormat);

        $activities =  $this->activityRepo->listActivitiesForStatistics($months['from'],$dateFormat['to']);
        $data = $activities->whereIn('customerId',request()->input('customer_ids',[]));

        $calculateActivities = $data->groupBy('customerName')->map(function ($activities, $customer) use ( $months ) {
            foreach ($months['formatYm'] as $month) {
                $hours[] = $this->totalRealTime($activities->where('startDateMonth',$month));
            }
            return [
                'seriesname' => Str::limit($customer, 15),
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
