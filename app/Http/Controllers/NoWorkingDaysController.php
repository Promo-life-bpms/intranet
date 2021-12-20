<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\NoWorkingDays;
use Illuminate\Http\Request;

class NoWorkingDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noworkingdays = NoWorkingDays::orderBy('day', 'ASC')->get();
        return view('admin.noworkingdays.index', compact('noworkingdays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all()->pluck('name_company','id');
        return view('admin.noworkingdays.create', compact('companies'));
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
            'day'=> 'required',
            'reason'=>'required',
            'companies_id'=>'required'
        ]);

        $noworkingday = new NoWorkingDays();
        $noworkingday->day = $request->day;
        $noworkingday->reason = $request->reason;
        $noworkingday->companies_id = $request->companies_id;
        $noworkingday->save();

        return redirect()->action([NoWorkingDaysController::class,'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NoWorkingDays  $noWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function show(NoWorkingDays $noWorkingDays)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NoWorkingDays  $noWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function edit(NoWorkingDays $noworkingday)
    {
        $companies = Company::all()->pluck('name_company','id');
        return view('admin.noworkingdays.edit', compact('noworkingday','companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NoWorkingDays  $noWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NoWorkingDays $noworkingday)
    {
        $request->validate([
            'day'=> 'required',
            'reason'=>'required',
            'companies_id'=>'required'
        ]);

        $noworkingday->day = $request->day;
        $noworkingday->reason = $request->reason;
        $noworkingday->companies_id = $request->companies_id;
        $noworkingday->save();


        return redirect()->action([NoWorkingDaysController::class,'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NoWorkingDays  $noWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function destroy(NoWorkingDays $noworkingday)
    {
        $noworkingday->delete();
        return redirect()->action([NoWorkingDaysController::class,'index']);

    }
}
