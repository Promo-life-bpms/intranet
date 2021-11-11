<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromolifeController extends Controller
{
    public function __invoke()
    {
        return view('about.promolife');
    }
}
