<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Organization;
use App\Models\Position;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        $organizations = Organization::all();
        $positions = Position::all();
        return view('admin.organization.index', compact('organizations', 'departments', 'positions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.organization.create');
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
            'name_company' => 'required',
            'description_company' => 'required'
        ]);

        $organization = new Organization();
        $organization->name_company = $request->name_company;
        $organization->description_company = $request->description_company;
        $organization->save();

        return redirect()->action([OrganizationController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        return view('admin.organization.edit', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {

        $request->validate([
            'name_company' => 'required',
            'description_company' => 'required'
        ]);

        $organization->update($request->all());

        return redirect()->action([OrganizationController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return redirect()->action([OrganizationController::class, 'index']);
    }
}
