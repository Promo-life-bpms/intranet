<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $com = Communique::all();
        $communique = $com->last();
        return view('home.index', compact('communique'));
    }
}
