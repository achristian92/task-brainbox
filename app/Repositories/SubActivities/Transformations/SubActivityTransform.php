<?php


namespace App\Repositories\SubActivities\Transformations;


use App\Repositories\SubActivities\SubActivity;
use Carbon\Carbon;

trait SubActivityTransform
{
    public function transformSubActivity(SubActivity $subActivity)
    {
        return [
            'id'           => $subActivity->id,
            'name'         => $subActivity->name,
            'duration'     => $subActivity->duration,
            'completed_at' => Carbon::parse($subActivity->completed_at)->format('d/m H:i')
        ];
    }


}
