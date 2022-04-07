<?php


namespace App\Http\Controllers\Admin\Histories;


use App\Http\Controllers\Controller;
use App\Repositories\UsersHistories\UserHistory;

class HistoryController extends Controller
{

    public function __invoke()
    {
        $histories = UserHistory::search(request()->input('q'))
                                ->orderBy('created_at','desc')
                                ->paginate(100);

        return view('admin.histories.history',[
            'histories' => $histories
        ]);
    }

}
