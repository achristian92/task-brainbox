<?php


namespace App\Http\Controllers;




use App\Repositories\Activities\Activity;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Activities\Transformations\ActivityTransformable;
use App\Repositories\ActivityHistory\Transformations\HistoryActivityTransformable;

use App\Repositories\Customers\Customer;
use App\Repositories\Customers\Repository\ICustomer;
use App\Repositories\PartialActivities\Transformations\PartialActivityTransformable;
use App\Repositories\Settings\Repository\ISetup;
use App\Repositories\Settings\Setup;
use App\Repositories\SubActivities\Transformations\SubActivityTransform;
use App\Repositories\Supervisors\Supervisor;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Tags\Tag;
use App\Repositories\Tools\DatesTrait;
use App\Repositories\Tools\TActivityReport;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\Users\Transformations\UserTransformable;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestController extends Controller
{
    use UserTransformable,
        ActivityTransformable,SubActivityTransform,PartialActivityTransformable,HistoryActivityTransformable,
        ActivityFilterTrait, DatesTrait, TActivityReport;

    private $counterRepo;
    private $activityRepo;
    private $tagRepo;
    private $customerRepo;
    private $userRepo;
    private $setupRepo;

    public function __construct(ISetup $ISetup,IUser $IUser, ICustomer $ICustomer, IActivity $IActivity,ITag $ITag)
    {
        $this->setupRepo = $ISetup;
        $this->userRepo = $IUser;
        $this->activityRepo = $IActivity;
        $this->tagRepo = $ITag;
        $this->customerRepo = $ICustomer;
    }

    public function index(Request $request)
    {

        $user = User::find(1);
        $roles = $user->getRoleNames();
        dd($roles);


    }

    public function tags()
    {
        $tags = Tag::all()->transform(function ($tag) {
            return [
                'name' => $tag->name
            ];
        })->toArray();

        return response()->json([
            'data' => $tags
        ]);

    }

    public function brainbox()
    {
        $setting = Setup::first();
        $setting->update([
            'company' => 'Brainbox S.A.C',
            'project' => 'Task Manager',
            'url_logo' => 'https://brainbox.pe/img/brainbox.svg',
            'favicon' => 'https://brainbox20201126.s3.amazonaws.com/general/nuGHGkqpZEy1f3Wfavicon.ico'
        ]);

        User::all()->each(function ($user) {
            $string = $user->email;
            $replaced = Str::replaceFirst('jga.pe', 'brainbox.pe', $string);
            $user->email = $replaced;
            $user->save();
        });

        return 'actualizado';

    }


}

