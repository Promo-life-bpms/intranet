<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemRequest extends Controller
{
        public function systemsrequest()
        {    
            $systems_request = DB::table('request_team')
            ->select('request_team.*')
            ->orderBy('id')
            ->get();
            return view('systems.systemRequest')->with('systems_request', $systems_request);
    }
}
