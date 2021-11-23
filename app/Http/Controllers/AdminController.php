<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function users(){
        $users =  User::all();
        return view('admin.user.users', compact('users'));
    }

    public function employees(){
        $employees =  Employee::all();
        return view('admin.employee.employee', compact('employees'));
    }

    public function contact(){
        $contacts = Contact::all();
        return view('admin.contact.contact', compact('contacts'));
    }
 

    public function contactCreate(){
        return view('admin.contact.create');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function contactStore(Request $request)
    {
        $contacts = new Contact();
        $contacts->num_tel = $request->num_tel;
        $contacts->correo1 = $request->correo1;
        $contacts->correo2 = $request->correo2;
        $contacts->correo3 = $request->correo3;
        $contacts->correo4 = $request->correo4;
        $contacts->save();

        $contacts = Contact::all();

        return view('admin.contact.contact', compact('contacts'));
       
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
    public function contactEdit(Contact $contact)
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
}
