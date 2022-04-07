<?php

namespace App\Http\Controllers\Counter\Planned;

use App\Exports\TemplateImportPlan;
use App\Http\Controllers\Controller;
use App\Repositories\Tags\Repository\ITag;
use App\Repositories\Users\Repository\IUser;
use App\Repositories\UsersHistories\UserHistory;
use Maatwebsite\Excel\Facades\Excel;

class TemplateExcelController extends Controller
{

    private $tagRepo;
    private $userRepo;

    public function __construct(IUser $IUser,ITag $ITag)
    {
        $this->userRepo = $IUser;
        $this->tagRepo = $ITag;
    }

    public function __invoke()
    {
        _addHistory(UserHistory::EXPORT,"Exportó plantilla para importar plan");

        $customers = $this->userRepo->listCustomers($this->currentUser()->id)->pluck('name');
        $tags = $this->tagRepo->listTagsActived()->pluck('name');

        return Excel::download(new TemplateImportPlan($customers,$tags), 'Plan-Trabajo.xlsx');
    }
}
