<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RhController extends Controller
{
    public function stadistics()
    {
        return view('rh.stadistics');
    }

    public function newUser()
    {
        return view('rh.new-user');
    }

    public function dropUser()
    {
        $users = User::all()->where('status',1);
        return view('rh.drop-user', compact('users'));
    }

    public function dropDocumentation($user)
    {
        $user = User::all()->where('id',$user)->first();
        $companies = Company::all()->pluck('name_company', 'id' );

        return view('rh.drop-documentation', compact('user', 'companies'));
    }
    
    public function dropDeleteUser(Request $request)
    {
        DB::table('users')->where('id', $request->user)->update(['status' => 2]); 

        return redirect()->action([RhController::class, 'dropDocumentation'])->with('message', 'El usuario se ha dado de baja correctamente');
    }

}
