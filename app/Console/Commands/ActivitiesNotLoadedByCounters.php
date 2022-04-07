<?php

namespace App\Console\Commands;

use App\Mail\SendEmailAlertActivitiesNotLoaded;
use App\Repositories\Activities\Repository\IActivity;
use App\Repositories\Activities\Transformations\ActivityFilterTrait;
use App\Repositories\Settings\Repository\ISetup;
use App\Repositories\Users\Repository\IUser;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;

class ActivitiesNotLoadedByCounters extends Command
{
    use ActivityFilterTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actnotloaded:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia correo a los usuarios que no registraron sus actividades';
    /**
     * @var IActivity
     */
    private $activityRepo;
    /**
     * @var IUser
     */
    private $userRepo;
    /**
     * @var ISetup
     */
    private $setupRepo;


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
        if ($this->setupRepo->notifyActivityOverdue()) {
            $data = $this->activityRepo->getListPlannedYesterday();

            if (collect($data)->isNotEmpty()) {
                $setup = $this->setupRepo->findSetup()->toArray();

                $this->userRepo->listUserActive()->each(function (User $user) use ($data,$setup) {
                    $activities = $this->groupByCounter($data);
                    if ($user->isAdminOrSupervisor() && $activities->isNotEmpty()) {
                        Mail::to($user->email)
                            ->send(new SendEmailAlertActivitiesNotLoaded($user, $activities->ToArray(),$setup));
                    }
                });
            }

        }

        return 0;
    }
    private function groupByCounter(Collection $data): Collection
    {
        return $data->groupBy('userName')->transform(function ($activities, $user) {
            $advance = $this->qtyCompleted($activities) + $this->qtyPartial($activities);
            $total   = $activities->count();
            return [
                'name'           => $user,
                'totalAct'       => $activities->count(),
                'qtyCompleted'   => $advance,
                'isCompletedAll' => (bool) ($total === $advance)
            ];
        })->filter(function ($activities) {
            return !$activities['isCompletedAll'];
        })->values();
    }
}
