<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function __invoke(){
        return view('folder.index');
    }
}
