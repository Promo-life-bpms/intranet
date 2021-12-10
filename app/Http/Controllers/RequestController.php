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
        $requests = auth()->user()->employee->yourRequests;
        return view('request.index', compact('requests'));
    }

    public function authorizeRequestManager()
    {
        $requests = auth()->user()->employee->yourAuthRequests;
        return view('request.authorize', compact('requests'));
    }

    public function showAll()
    {
        $requests = ModelsRequest::where('jefe_status', '=', 1);
        return view('request.show', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado');
        }
        $request->validate([
            'type_request' => 'required',
            'payment' => 'required',
            'absence' => 'required',
            'admission' => 'required',
            'reason' => 'required'
        ]);

        $req = new ModelsRequest();
        $req->employee_id = auth()->user()->employee->id;
        $req->type_request = $request->type_request;
        $req->payment = $request->payment;
        $req->absence = $request->absence;
        $req->admission = $request->admission;
        $req->reason = $request->reason;

        $req->direct_manager_id = auth()->user()->employee->jefe_directo_id;
        $req->direct_manager_status = "Pendiente";
        $req->human_resources_status = "Pendiente";

        $req->save();

        return redirect()->action([RequestController::class, 'index']);
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
    public function update(Request $req, ModelsRequest $request)
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
    public function destroy(ModelsRequest $request)
    {
        $request->delete();
        return redirect()->action([RequestController::class, 'index']);
    }
}
