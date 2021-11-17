<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkController extends Controller
{
   public function __invoke()
   {
      return view('work.index');
   } 
}
