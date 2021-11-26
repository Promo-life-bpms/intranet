<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function promolife()
    {
        return view('about.promolife');
    }
    public function bh()
    {
        return view('about.bhtrade');
    }
    public function promodreams()
    {
        return view('about.promodreams');
    }
    public function trademarket()
    {
        return view('about.trademarket');
    }
}
