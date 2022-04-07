<?php


namespace App\Repositories\Settings\Repository;


use App\Repositories\Settings\Setup;

interface ISetup
{
    public function notifyActivityOverdue():bool;
    public function sendCredentials():bool;
    public function notifyActivityDeadline():bool;

    public function findSetup(): Setup;

}
