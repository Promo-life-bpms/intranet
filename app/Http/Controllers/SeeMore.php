<?php

namespace App\Http\Controllers;

use App\Models\SeeMore as ModelsSeeMore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeeMore extends Controller
{
    public function seeMore()
    {
        $see_more = ModelsSeeMore::all();    
        /*$see_more = DB::table('request_team')
            ->select('request_team.*')
            ->orderBy('id')
            ->get();*/
        return view('systems.SeeMore')->with('see_more', $see_more);;
    }
}
