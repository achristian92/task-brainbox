<?php


namespace App\Repositories\ActivityHistory\Transformations;


use App\Repositories\ActivityHistory\ActivityHistory;
use Carbon\Carbon;

trait HistoryActivityTransformable
{
    public function transformHistoryActivity(ActivityHistory $history)
    {
        return [
            'user'        => $history->user,
            'dateShort'   => Carbon::parse($history->registered_at)->format('d/m H:i'),
            'description' => $history->description
        ];
    }

}
