<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        $communiques = Communique::paginate(3);
        return view('home.index', compact('communiques'));
    }
}
