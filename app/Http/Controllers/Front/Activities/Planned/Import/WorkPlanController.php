<?php


namespace App\Http\Controllers\Front\Activities\Planned\Import;


use App\Http\Controllers\Controller;
use App\Imports\PlanCounterImport;
use App\Repositories\Tools\UploadableTrait;
use App\Repositories\UsersHistories\UserHistory;
use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class WorkPlanController extends Controller
{
    use UploadableTrait;

    public function __invoke(Request $request, int $user_id)
    {
        $request->validate([
            'file_upload' => 'required|file|mimes:xls,xlsx'
        ]);

        $url = $this->handleUploadedDocument($request->file('file_upload'),'import');

        _addDocumentHistory(UserHistory::IMPORT,"Importó plan de trabajo",$url);
        _addHistory(UserHistory::IMPORT,"Importó plan de trabajo - $url");

        $user = \App\User::find(Auth::id());

        Excel::import(new PlanCounterImport($user_id,$user), $request->file('file_upload'));

        return response()->json([
            'msg' => 'Información cargada',
            'view' => $request->input('view','calendar')
        ], 201);
    }

}
