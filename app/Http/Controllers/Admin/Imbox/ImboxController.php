<?php

namespace App\Http\Controllers\Admin\Imbox;

use App\Http\Controllers\Controller;

class ImboxController extends Controller
{
    public function __invoke()
    {
        return view('admin.imbox.index');
    }
}
