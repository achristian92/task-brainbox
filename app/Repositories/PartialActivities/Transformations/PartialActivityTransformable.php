<?php


namespace App\Repositories\PartialActivities\Transformations;


use App\Repositories\PartialActivities\PartialActivity;
use App\Repositories\SubActivities\SubActivity;
use Carbon\Carbon;

trait PartialActivityTransformable
{
    public function transformPartialActivity(PartialActivity $partialActivity)
    {
        return [
            'id'           => $partialActivity->id,
            'name'         => '(Avance) '.strtolower($partialActivity->activity->name),
            'duration'     => $partialActivity->duration,
            'completed_at' => Carbon::parse($partialActivity->completed_at)->format('d/m H:i')
        ];
    }


}
