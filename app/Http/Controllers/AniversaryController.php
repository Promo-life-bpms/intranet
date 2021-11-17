<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AniversaryController extends Controller
{
    public function __invoke(){
        return view('aniversary.index');
    }

    public function birthday(){
        return view('aniversary.birthday');
    }

    public function aniversary(){
        return view('aniversary.aniversary');
    }
}
