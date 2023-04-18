<?php

namespace App\Http\Controllers;

use App\Models\SystemsRequest as ModelsSystemsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemRequest extends Controller
{
        public function systemsrequest(){
            $datos = DB::table('request_team')
                ->select('request_team.*')
                ->orderBy('id')
                ->get();
            return view('systems.systemRequest')->with('datos', $datos);
    }
}
