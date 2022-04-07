<?php

namespace App\Http\Controllers\Counter\Tracks;

use App\Http\Controllers\Controller;

class TrackController extends Controller
{
    public function __invoke()
    {
        return view('counter.tracks.index');
    }
}
