<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $access = Access::all()->where('users_id',$id);
        return view('access.index', compact('access'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('access.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'link'=> 'required',
            'user'=>'required',
            'password'=>'required',
            'image'=>'required'
        ]);

        $access = new Access();
        $access->link = $request->link;
        $access->user = $request->user;
        $access->password = $request->password;
        $access->image = $request->image;
        $access->save();

        return redirect()->action([AccessController::class,'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Access  
     * @return \Illuminate\Http\Response
     */
    public function show(Access $acc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Access  
     * @return \Illuminate\Http\Response
     */
    public function edit(Access $acc)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Access  
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Access $acc)
    {
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Access  
     * @return \Illuminate\Http\Response
     */
    public function destroy(Access $acc)
    {
        

    }
}
