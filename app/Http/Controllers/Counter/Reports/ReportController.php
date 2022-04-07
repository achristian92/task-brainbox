<?php

namespace App\Http\Controllers\Counter\Reports;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function __invoke()
    {
        return view('counter.reports.index', [
            'users' => [$this->currentUser()],
        ]);
    }
}
