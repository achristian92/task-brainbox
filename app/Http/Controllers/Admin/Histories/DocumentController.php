<?php


namespace App\Http\Controllers\Admin\Histories;


use App\Http\Controllers\Controller;
use App\Repositories\Documents\Document;
use App\Repositories\UsersHistories\UserHistory;

class DocumentController extends Controller
{
    public function __invoke()
    {
        $documents = Document::search(request()->input('q'))
                                ->orderBy('created_at','desc')
                                ->paginate(100);

        return view('admin.histories.document',[
            'documents' => $documents
        ]);
    }
}
