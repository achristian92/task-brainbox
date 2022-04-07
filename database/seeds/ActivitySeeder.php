<?php

use App\Repositories\Activities\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Activity::class, 3000)->create();

        Activity::with('user','approved_by')->get()
            ->each(function ($activity) {
                if ($activity->state === 'planned') $this->historyPlanned($activity);
                if ($activity->state === 'approved') {
                    $this->historyPlanned($activity);
                    $this->historyApproved($activity);
                }
                if ($activity->state === 'completed') {
                    $this->historyPlanned($activity);
                    $this->historyApproved($activity);
                    $this->historyCompleted($activity);
                }
            });
    }
    public function historyPlanned(Activity $activity)
    {
        $activity->histories()->create([
            'user' => $activity->user->full_name,
            'description' => 'Actividad creada',
            'date' => $activity->created_date
        ]);
    }
    public function historyApproved(Activity $activity)
    {
        $activity->histories()->create([
            'user' => $activity->user->full_name,
            'description' => "Actividad aprobada",
            'date' => $activity->approved_date
        ]);
    }
    public function historyCompleted(Activity $activity)
    {
        $activity->histories()->create([
            'user' => $activity->user->full_name,
            'description' => "Actividad completada",
            'date' => $activity->start_date
        ]);
    }
}
