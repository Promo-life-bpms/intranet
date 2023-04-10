<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamRequest extends Controller
{
    //
public function index(){
    return view('admin.team.index');
}

public function index1(){
    return view('admin.team.record');
}


}
