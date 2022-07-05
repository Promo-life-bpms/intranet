<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Publications;
use Exception;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __invoke()
    {
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request, Publications $publications)
    {

        //Almacena los likes de un usuario a una publicacion
        try {
            return auth()->user()->meGusta()->toggle($publications);
        } catch (Exception $e) {
            return $e;
        }
    }
}
