<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\Manager;
use App\Models\Position;
use App\Models\User;
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

        return view('admin.manager.index', compact('managers', 'positions'));
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
        $users = User::pluck('name', 'id')->toArray();
        return view('admin.manager.create', compact('positions', 'departments', 'users'));
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
            'users_id' => 'required',
            'department_id' => 'required'
        ]);
        
        $manager = new Manager();
        $manager->users_id = $request->users_id;
        $manager->department_id = $request->department_id;
        $manager->save();

        return redirect()->action([ManagerController::class, 'index']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Manager $manager)
    {
        $positions  = Position::pluck('name', 'id')->toArray();
        $departments  = Department::pluck('name', 'id')->toArray();
        $users = User::pluck('name', 'id')->toArray();
        return view('admin.manager.edit', compact('positions', 'departments', 'users', 'manager'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'users_id' => 'required',
            'department_id' => 'required'
        ]);

        $manager->update($request->all());
        return redirect()->action([ManagerController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manager $manager)
    {
        $manager->delete();
        return redirect()->action([ManagerController::class, 'index']);
    }

    public function getPosition($id)
    {
        $positions = Position::all()->where('department_id', $id)->pluck('name', 'id');
        return json_encode($positions);
    }

    public function getEmployee($id)
    {
        $employeesPos = Employee::all()->where('position_id',$id)->pluck('id','user_id');
        $employees = User::all()->whereIn('id', $employeesPos)->pluck('name', 'id');
        return json_encode($employees);
    }
}
