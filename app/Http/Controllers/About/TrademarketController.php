<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrademarketController extends Controller
{
    public function __invoke()
    {
        return view('about.trademarket');
    }
}
