<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments  = Department::pluck('name', 'id')->toArray();
        $employees = Employee::all();
        $dataEmployees = [];
        foreach ($employees as $employee) {
            array_push($dataEmployees, [
                "id" => $employee->id,
                "pid" => $employee->jefe_directo_id,
                "name" => $employee->nombre
            ]);
        }
        $dataEmployees = (object) $dataEmployees;
        // dd($dataEmployees);
        return view('company.index', compact('departments', 'dataEmployees'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }

    public function getPositions($id)
    {
        $positions = Position::all()->where("department_id", $id)->pluck("name", "id");
        return json_encode($positions);
    }
    public function getEmployees()
    {
        $employees = Employee::all();
        $dataEmployees = [];
        foreach ($employees as $employee) {
            array_push($dataEmployees, [
                "id" => $employee->id,
                "pid" => $employee->jefe_directo_id,
                "Nombre" => $employee->nombre,
                "Puesto" => $employee->nombre,
                "Photo" => 'https://www.pngall.com/wp-content/uploads/5/Profile-Male-PNG.png',
            ]);
        }
        // $dataEmployees = (object) $dataEmployees;
        return json_encode($dataEmployees);
    }
}
