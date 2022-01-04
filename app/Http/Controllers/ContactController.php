<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Plus;

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
        return view('admin.contact.index', compact('contacts','departments'));
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

    public function getContacts($id)
    {

     
        $positions = Position::all()->where('department_id',$id)->pluck('id','id');
        //dd($positions);
        $employeesPos = Employee::all()->whereIn('position_id',$positions)->pluck('id','user_id');
        //dd($employeesPos);
        $contacts = User::all()->whereIn('id', $employeesPos)->pluck('name', 'id');
        //dd($contacts);

        return json_encode($contacts);
    }
}
