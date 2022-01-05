<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $departments = Department::all()->pluck('name','id');
        $contacts = Contact::paginate(15);

        $rhID = 1;
        $rhPosition = Position::all()->where('department_id',$rhID)->pluck('id','id');
        $rhEmployeesPos = Employee::all()->whereIn('position_id',$rhPosition)->pluck('id','user_id');
        $rhData = User::all()->whereIn('id', $rhEmployeesPos)->pluck('id','id');
        $rh = Contact::all()->whereIn('user_id', $rhData);

        $adminID = 2;
        $adminPosition = Position::all()->where('department_id',$adminID)->pluck('id','id');
        $adminEmployeesPos = Employee::all()->whereIn('position_id',$adminPosition)->pluck('id','user_id');
        $adminData = User::all()->whereIn('id', $adminEmployeesPos)->pluck('id','id');
        $admin = Contact::all()->whereIn('user_id', $adminData);


        $ventasBHID = 3;
        $ventasBHPosition = Position::all()->where('department_id',$ventasBHID)->pluck('id','id');
        $ventasBHEmployeesPos = Employee::all()->whereIn('position_id',$ventasBHPosition)->pluck('id','user_id');
        $ventasBHData = User::all()->whereIn('id', $ventasBHEmployeesPos)->pluck('id','id');
        $ventasBH = Contact::all()->whereIn('user_id', $ventasBHData);


        $importacionesID = 4;
        $importacionesPosition = Position::all()->where('department_id',$importacionesID)->pluck('id','id');
        $importacionesEmployeesPos = Employee::all()->whereIn('position_id',$importacionesPosition)->pluck('id','user_id');
        $importacionesData = User::all()->whereIn('id', $importacionesEmployeesPos)->pluck('id','id');
        $importaciones = Contact::all()->whereIn('user_id', $importacionesData);

        $disenoID = 5;
        $disenoPosition = Position::all()->where('department_id',$disenoID)->pluck('id','id');
        $disenoEmployeesPos = Employee::all()->whereIn('position_id',$disenoPosition)->pluck('id','user_id');
        $disenoData = User::all()->whereIn('id', $disenoEmployeesPos)->pluck('id','id');
        $diseno = Contact::all()->whereIn('user_id', $disenoData);

        $sistemasID = 6;
        $sistemasPosition = Position::all()->where('department_id',$sistemasID)->pluck('id','id');
        $sistemasEmployeesPos = Employee::all()->whereIn('position_id',$sistemasPosition)->pluck('id','user_id');
        $sistemasData = User::all()->whereIn('id', $sistemasEmployeesPos)->pluck('id','id');
        $sistemas = Contact::all()->whereIn('user_id', $sistemasData);

        $operacionesID = 7;
        $operacionesPosition = Position::all()->where('department_id',$operacionesID)->pluck('id','id');
        $operacionesEmployeesPos = Employee::all()->whereIn('position_id',$operacionesPosition)->pluck('id','user_id');
        $operacionesData = User::all()->whereIn('id', $operacionesEmployeesPos)->pluck('id','id');
        $operaciones = Contact::all()->whereIn('user_id', $operacionesData);

        $ventasPLID = 8;
        $ventasPLPosition = Position::all()->where('department_id',$ventasPLID)->pluck('id','id');
        $ventasPLEmployeesPos = Employee::all()->whereIn('position_id',$ventasPLPosition)->pluck('id','user_id');
        $ventasPLData = User::all()->whereIn('id', $ventasPLEmployeesPos)->pluck('id','id');
        $ventasPL = Contact::all()->whereIn('user_id', $ventasPLData);
        
        $tecnologiaID = 9;
        $tecnologiaPosition = Position::all()->where('department_id',$tecnologiaID)->pluck('id','id');
        $tecnologiaEmployeesPos = Employee::all()->whereIn('position_id',$tecnologiaPosition)->pluck('id','user_id');
        $tecnologiaData = User::all()->whereIn('id', $tecnologiaEmployeesPos)->pluck('id','id');
        $tecnologia = Contact::all()->whereIn('user_id', $tecnologiaData);

        $cancunID = 11;
        $cancunPosition = Position::all()->where('department_id',$cancunID)->pluck('id','id');
        $cancunEmployeesPos = Employee::all()->whereIn('position_id',$cancunPosition)->pluck('id','user_id');
        $cancunData = User::all()->whereIn('id', $cancunEmployeesPos)->pluck('id','id');
        $cancun = Contact::all()->whereIn('user_id', $cancunData);

        return view('admin.contact.index', compact('contacts','departments','rh','admin', 'ventasBH','importaciones', 'diseno','sistemas', 'operaciones','ventasPL','tecnologia','cancun'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contact.create');
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
            'num_tel' => 'required'
        ]);

        $contacts = new Contact();
        $contacts->num_tel = $request->num_tel;
        $contacts->correo1 = $request->correo1;
        $contacts->correo2 = $request->correo2;
        $contacts->correo3 = $request->correo3;
        $contacts->correo4 = $request->correo4;
        $contacts->save();

      return redirect()->action([ContactController::class,'index']);
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
    public function edit(Contact $contact)
    {
        return view('admin.contact.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update($request->all());
        return redirect()->action([ContactController::class,'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->action([ContactController::class,'index']);
    }

   /*  public function getContacts($id)
    {
        $positions = Position::all()->where('department_id',$id)->pluck('id','id');
        //dd($positions);
        $employeesPos = Employee::all()->whereIn('position_id',$positions)->pluck('id','user_id');
        //dd($employeesPos);
        $data = User::all()->whereIn('id', $employeesPos)->pluck('id','id');
        //dd($contacts);
        $contacts = Contact::all()->whereIn('user_id', $data);
        return json_encode($contacts);
    } */
}
