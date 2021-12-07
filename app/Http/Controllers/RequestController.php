<?php

namespace App\Http\Controllers;

use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $userID = DB::table('employees')->where('id', $id)->value('id');
        $rol = DB::table('model_has_roles')->where('model_id', $id)->value('role_id');
        $rh = DB::table('roles')->where('name', 'RH')->value('id');


        if($rol == $rh){
            $requests = ModelsRequest::all();
            
        }else{
            $requests = ModelsRequest::all()->where('employee_id', $userID );
        }        
              
        return view('request.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        //$managers=DepartmentManager::pluck('department_id', 'id')->toArray();
        return view('request.create');
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
            'type_request' => 'required',
            'payment' => 'required',
            'absence' => 'required',
            'admission' => 'required',
            'reason' => 'required'
        ]);

        $id = Auth::user()->id;
        $userID = DB::table('employees')->where('id', $id)->value('id');
        $userPosition = DB::table('employee_position')->where('employee_id', $userID )->value('position_id');
        $position = DB::table('positions')->where('id',$userPosition)->value('department_id');
        $manager = DB::table('department_manager')->where('department_id', $position)->value('employee_id');

        $req = new ModelsRequest();
        $req->employee_id=$userID; 
        $req->type_request = $request->type_request; 
        $req->payment = $request->payment;
        $req->absence = $request->absence;
        $req->admission = $request->admission;
        $req->reason = $request->reason;

        $req->direct_manager_id = $manager;

        $req->direct_manager_status ="Pendiente";

        $req->human_resources_status ="Pendiente";

        $req->save();

        return redirect()->action([RequestController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Request $communique
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Request  $communique
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $req, ModelsRequest $request)
    {
        return view('request.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req , ModelsRequest $request)
    {
        $req->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'absence' => 'required',
            'admission' => 'required',
            'reason' => 'required'
        ]);

        $request->update($req->all());

        return redirect()->action([RequestController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }
}
