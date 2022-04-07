<?php


namespace App\Repositories\Users\Repository;


use App\Repositories\Activities\Activity;
use App\User;
use Illuminate\Database\Eloquent\Collection;

interface IUser
{
    public function findUserById(int $user_id): User;
    public function createUser(array $data): User;
    public function updateUser(User $user, array $data): void;
    public function deleteUser(User $user): bool;

    public function listUsersByIds(array $ids, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection;

    public function listUserActive(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']): Collection;
    public function allUserList(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);
    public function userLessCollaboratorList(string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']):Collection;
    public function listCustomers(int $user_id, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);
    public function usersMonitoring(int $user_id, string $orderBy = 'name', string $sortBy = 'asc', array $columns = ['*']);

    public function listPlannedActivities(int $user_id, $month = null, $year = null);
    public function listActivities(int $user_id, $month = null, $year = null);

    public function reportPlannedVsReal(int $user_id,string $from,string $to);
    public function reportTimeCustomer(int $user_id,string $from,string $to);
    public function reportTimeCustomerDays(int $user_id,string $from,string $to, array $period);


    public function listUsersCanSee(array $columns = ['*']);


}
