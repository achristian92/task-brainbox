<?php

namespace App\Console\Commands;

use App\Mail\SendEmailActivitiesDeadline;
use App\Mail\SendEmailActivitiesDeadlineAdmin;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Settings\Repository\ISetup;
use App\Repositories\Users\Repository\IUser;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ActivitiesDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actdeadline:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users activities deadline to tomorrow';
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
     * @param ISetup $ISetup
     * @param IActivity $IActivity
     * @param IUser $IUser
     */
    public function __construct(ISetup $ISetup,IActivity $IActivity, IUser $IUser)
    {
        parent::__construct();
        $this->setupRepo = $ISetup;
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
        if ($this->setupRepo->notifyActivityDeadline()) {

            $data = $this->activityRepo->getActivitiesWithDeadline();

            if (collect($data)->isNotEmpty()) {
                $setup = $this->setupRepo->findSetup()->toArray();

                collect($data)->each(function ($user) use ($setup) {
                    Mail::to($user['email'])->send(new SendEmailActivitiesDeadline($user,$setup));
                });

                $this->userRepo->listUserActive()->each(function (User $user) use ($data,$setup) {
                    if ($user->isAdminOrSupervisor()) {
                        Mail::to($user->email)->send(new SendEmailActivitiesDeadlineAdmin($user, $data,$setup));
                    }
                });
            }

        }

        return 0;
    }
}
