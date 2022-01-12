<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;

class CommuniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communiques =  Communique::orderBy('created_at', 'ASC')->simplePaginate(5);
        $communiquesPrincipal =  Communique::orderBy('created_at', 'DESC')->limit(3)->get();
        return view('communique.index', compact('communiques', 'communiquesPrincipal'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $departments = Department::all();
        return view('communique.create', compact('companies', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        if (!$request->companies  && !$request->departments) {
            return back()->with('message', 'No es posible registrar el comunicado por que no has seleccionado a los destinatarios');
        }

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $request->file('image')->move('storage/post/', $fileNameToStore);
        } else {
            $path = null;
        }

        if ($request->hasFile('file')) {
            $filenameWithExt2 = $request->file('file')->getClientOriginalName();
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            $extension2 = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore2 = $filename2 . '.' . $extension2;
            $path2 = $request->file('file')->move('storage/post/', $fileNameToStore2);
        } else {
            $path2 = null;
        }

        $communique = new Communique();
        $communique->title = $request->title;
        $communique->image = $path;
        $communique->file = $path2;
        $communique->description = $request->description;
        $communique->creator_id = auth()->user()->employee->id;
        $communique->save();

        /*  //Quien recibe el comunicado
        $employees_id = [];
        if ($request->departments && !$request->companies) {
            foreach ($request->departments as $value) {
                $department = Department::find($value);
                foreach ($department->positions as $position) {
                    foreach ($position->employees as $employee) {
                        array_push($employees_id, $employee->id);
                    }
                }
            }
        } else if (!$request->departments && $request->companies) {
            foreach ($request->companies as $value) {
                $company = Company::find($value);
                foreach ($company->employees as $employee) {
                    array_push($employees_id, $employee->id);
                    $employees_id = array_unique($employees_id);
                }
            }
        } else if ($request->departments && $request->companies) {
            foreach ($request->companies as $value) {
                $company = Company::find($value);
                foreach ($request->departments as $value) {
                    $department = Department::find($value);
                    foreach ($department->positions as $position) {
                        foreach ($position->employees as $employee) {
                            foreach ($employee->companies as $com) {
                                if ($com->id == $company->id) {
                                    array_push($employees_id, $employee->id);
                                    $employees_id = array_unique($employees_id);
                                }
                            }
                        }
                    }
                }
            }
        } */

        $communique->companies()->attach($request->companies);
        $communique->departments()->attach($request->departments);


        return redirect()->action([CommuniqueController::class, 'show']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function show(/* Communique $communique */)
    {
        $communiques =  Communique::all(); /* auth()->user()->employee->communiques; */
        return view('communique.show', compact('communiques'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function edit(Communique $communique)
    {
        $companies = Company::all();
        $departments = Department::all();
        return view('communique.edit', compact('communique', 'companies', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Communique $communique)
    {
        request()->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($request->companies == '' || $request->departments == '') {
            return back()->with('message', 'No es posible registrar el comunicado por que no has seleccionado a los destinatarios');
        }


        if ($request->hasFile('image') == null) {

            if ($communique->image == null) {
                $path = null;
            } else {
                $path = $communique->image;
            }
        } else {

            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $request->file('image')->move('storage/post/', $fileNameToStore);
        }



        if ($request->hasFile('file') == null) {

            if ($communique->file == null) {
                $path2 = null;
            } else {
                $path2 = $communique->file;
            }
        } else {

            $filenameWithExt2 = $request->file('file')->getClientOriginalName();
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            $extension2 = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore2 = $filename2 . '.' . $extension2;
            $path2 = $request->file('file')->move('storage/post/', $fileNameToStore2);
        }

        $communique->title = $request->title;
        $communique->image = $path;
        $communique->file = $path2;
        $communique->description = $request->description;
        $communique->creator_id = auth()->user()->employee->id;
        $communique->save();

        $communique->companies()->attach($request->companies);
        $communique->departments()->attach($request->departments);

        return redirect()->action([CommuniqueController::class, 'show']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function destroy(Communique $communique)
    {
        $communique->delete();
        return redirect()->action([CommuniqueController::class, 'show']);
    }
}
