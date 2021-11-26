<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees =  Employee::all();   
        return view('admin.employee.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $contacts = Contact::pluck('num_tel','id')->toArray();
        $users = User::pluck('name','id')->toArray();
        return view('admin.employee.create', compact('contacts', 'users'));
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
            'nombre'=>'required',
            'paterno'=>'required',
            'materno'=>'required',
            'fecha_cumple'=>'required',
            'fecha_ingreso'=>'required',
            'status'=>'required',
            'id_contacto'=>'required',
            'id_user'=>'required'
        ]);

        $employee = new Employee();
        $employee->nombre = $request->nombre;
        $employee->paterno = $request->paterno;
        $employee->materno = $request->materno;
        $employee->fecha_cumple = $request->fecha_cumple;
        $employee->fecha_ingreso = $request->fecha_ingreso;
        $employee->status = $request->status;
        $employee->id_contacto = $request->id_contacto;
        $employee->id_user = $request->id_user;
        $employee->save();

        $employees =  Employee::all();   
        return view('admin.employee.index', compact('employees'));
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
    public function edit(Employee $employee)
    {
        $contacts = Contact::pluck('num_tel','id')->toArray();
        $users = User::pluck('name','id')->toArray();
        
        return view('admin.employee.edit', compact('employee', 'contacts', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Employee $employee )
    {

        $request->validate([
            'nombre'=>'required',
            'paterno'=>'required',
            'materno'=>'required',
            'fecha_cumple'=>'required',
            'fecha_ingreso'=>'required',
            'status'=>'required',
            'id_contacto'=>'required',
            'id_user'=>'required'
        ]);

        $employee->update($request->all());

        $employees =  Employee::all();   
        return view('admin.employee.index', compact('employees'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        $employees =  Employee::all();   
        return view('admin.employee.index', compact('employees'));
    }
}
