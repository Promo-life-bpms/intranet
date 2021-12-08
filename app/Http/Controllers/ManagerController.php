<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\Manager;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {      
        $managers = Manager::all();
        $positions = Position::all();

        return view('admin.manager.index',compact('managers','positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        

        $positions  = Position::pluck('name', 'id')->toArray();
        $departments  = Department::pluck('name', 'id')->toArray();
        $employees = Employee::pluck('nombre', 'id')->toArray();
        return view('admin.manager.create', compact('positions','departments','employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manager = new Manager();
        $manager -> employee_id = $request-> employee_id;
        $manager -> department_id = $request ->department_id;
        $manager->save();

        return redirect()->action([ManagerController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        
        return view('admin.manager.edit');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPosition($id) 
    {               
      
        $positions = Position::all()->where('department_id', $id )->pluck('name','id');
        return json_encode($positions);
    }

    public function getEmployee($id){
        $employeesPos = EmployeePosition::all()->where('position_id', $id )->pluck('employee_id','id');
/*         $employeesPos = DB::table('employee_position')->whereIn('position_id', $id )->value('employee_id');         
 */        
        $employee = Employee::all()->whereIn('id', $employeesPos  )->pluck('nombre','id');
        return json_encode($employee );
    }
}
