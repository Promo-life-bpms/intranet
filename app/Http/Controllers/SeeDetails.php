<?php

namespace App\Http\Controllers;

use App\Models\SeeDetails as ModelsSeeDetails;
use Illuminate\Http\Request;

class SeeDetails extends Controller
{
    public function details($id)
    {
       
        $see_details = ModelsSeeDetails::find($id);
        /*dd($see_details);*/
        return view('admin.Team.details', compact('see_details'));
    }
}
