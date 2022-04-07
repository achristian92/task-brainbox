<?php


namespace App\Repositories\Activities\Model;


use App\Repositories\Activities\Activity;

class StatusActivity
{

    /**
     * @var Activity
     */
    private $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    public function saludar()
    {
        return $this->activity->name;
    }

}
