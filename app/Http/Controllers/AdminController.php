<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function contact()
    {
        $contacts = Contact::all();
        return view('admin.contact.index', compact('contacts'));
    }


    public function contactCreate()
    {
        return view('admin.contact.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function contactStore(Request $request)
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

        $contacts = Contact::all();

        return view('admin.contact.index', compact('contacts'));
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
    public function contactUpdate(Request $request, Contact $contact)
    {


        $contact->update($request->all());

        $contacts = Contact::all();

        return view('admin.contact.index', compact('contacts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contactDestroy(Contact $contact)
    {
        $contact->delete();

        $contacts = Contact::all();

        return view('admin.contact.index', compact('contacts'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userStore(Request $request)
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

        $contacts = Contact::all();

        return view('admin.contact.index', compact('contacts'));
    }
}
