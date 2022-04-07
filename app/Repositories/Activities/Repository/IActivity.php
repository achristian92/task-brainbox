<?php


namespace App\Repositories\Activities\Repository;


use App\Repositories\Activities\Activity;

interface IActivity
{
    public function findActivityById(int $activity_id): Activity;
    public function createActivity(array $params): Activity;
    public function updateActivity(array $params, int $activity_id): bool ;
    public function deleteActivity(int $activity_id): bool ;




    public function approve(int $activity_id);
    public function returnPlannedStatus(int $activity_id): void ;


    public function resetActivityApproved(int $activity_id): void ;

    public function listActivities($month = null, $year = null);
    public function listOfActivities(string $from, string $to);

    public function getListPlannedYesterday();
    public function getActivitiesWithDeadline(): array;
    public function getActivitiesByApproval(): array;

    public function reportListCounterWorkedCustomer(int $customer_id, string $from,string $to, array $rangeDates);
    public function reportActivities(string $from, string $to);

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    public function listActivitiesForStatistics(string $from, string $to);


    public function saveHistory(Activity $activity, string $description):void;

    public function saveDurationPartial(Activity $activity, string $duration):void;

}
