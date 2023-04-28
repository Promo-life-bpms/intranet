<?php

namespace App\Http\Controllers;

use App\Models\SeeMore as ModelsSeeMore;
use Dotenv\Validator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeeMore extends Controller
{
    public function show($id)
    {
        $see_more = ModelsSeeMore::find($id);
        /*dd($user);*/
        return view('systems.show', compact('see_more'));
    }
    
    public function update(Request $request)
    {   
        $request->validate([
            'status' => 'required',
        ]);
        
        DB::table('request_team')->where('id', intval($request->id))->update([
            'status' => $request->status,
            'comments' => $request->comments,
        ]); 

        return redirect()->back()->with('success', '¡Solicitud Actualizada Exitosamente!');
    }

}
