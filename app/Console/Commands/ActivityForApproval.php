<?php

namespace App\Console\Commands;

use App\Mail\SendEmailActivitiesDeadline;
use App\Mail\SendEmailActivitiesDeadlineAdmin;
use App\Mail\SendEmailActivitiesForApproval;
use App\Mail\SendEmailAlertActivitiesNotLoaded;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Settings\Repository\ISetup;
use App\Repositories\Users\Repository\IUser;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ActivityForApproval extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actbyapproval:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actividades por aprobar';
    /**
     * @var ISetup
     */
    private $setupRepo;
    /**
     * @var IActivity
     */
    private $activityRepo;
    /**
     * @var IUser
     */
    private $userRepo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ISetup $ISetup,IActivity $IActivity, IUser $IUser)
    {
        parent::__construct();
        $this->setupRepo    = $ISetup;
        $this->activityRepo = $IActivity;
        $this->userRepo     = $IUser;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = $this->activityRepo->getActivitiesByApproval();

        if (collect($data)->isNotEmpty()) {
            $setup = $this->setupRepo->findSetup()->toArray();

            $this->userRepo->listUserActive()->each(function (User $user) use ($data,$setup) {
                if ($user->isAdmin()) {
                    Mail::to($user->email)->send(new SendEmailActivitiesForApproval($user, $data,$setup));
                }
            });
        }
        return 0;
    }
}
