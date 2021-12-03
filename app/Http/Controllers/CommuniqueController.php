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
        // request()->validate([
        //     'title' => 'required',
        //     'images' => 'mimes:png,jpg|image',
        //     'files' => 'mimes:png,jpg|image',
        //     'description' => 'required'
        // ]);
        // if ($request->companies == '' || $request->departments == '') {
        //     return back()->with('message', 'No es posible registrar el comunicado por que no has seleccionado a los destinatarios');
        // }
        // $imagen = $request->file("image");
        // $nombreimagen = $request->title . "." . $imagen->getClientOriginalName();
        // $ruta = public_path("storage/post/");

        //$imagen->move($ruta,$nombreimagen);
        // $imagen->move($ruta, $nombreimagen);

        // $communique = new Communique();
        // $communique->title = $request->title;
        // $communique->images = "storage/post/" . $nombreimagen;
        // $communique->description = $request->description;
        // $communique->save();

        //Quien recibe el comunicado
        if ($request->departments && !$request->companies) {
            foreach ($request->departments as $value) {
                $department = Department::find($value);
                foreach ($department->positionAttachment as $position) {
                    $position->users;
                    print_r();
                }
            }
            echo 1;
        } else if (!$request->departments && $request->companies) {
            foreach ($request->companies as $value) {
            }
            echo 2;
        } else if ($request->departments && $request->companies) {
            foreach ($request->companies as $value) {
                foreach ($request->departments as $value) {
                }
            }
        }
        return;
        // $communique->employeesAttachment()->attach(1);

        return redirect()->action([CommuniqueController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function show(Communique $communique)
    {
        $communiques =  Communique::all();
        $communiquesPrincipal =  Communique::paginate(5);
        return view('communique.show', compact('communiques', 'communiquesPrincipal'));
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
            'image' => 'required|mimes:png,jpg',
            'description' => 'required'
        ]);

        if ($request->companies == '' || $request->departments == '') {
            return back()->with('message', 'No es posible registrar el comunicado por que no has seleccionado a los destinatarios');
        }

        if (!$request->hasFile("image")) {
            return;
        }
        $imagen = $request->file("image");
        $nombreimagen = $request->title . "." . $imagen->getClientOriginalName();
        $ruta = public_path("img/post/");

        //$imagen->move($ruta,$nombreimagen);
        $imagen->move($ruta, $nombreimagen);
        $request->image = $ruta . $nombreimagen;


        $communique->update($request->all());


        //Quien recibe el comunicado
        $communique->employeesAttachment()->detach();
        $communique->employeesAttachment()->attach([1, 2, 3]);

        return redirect()->action([CommuniqueController::class, 'index']);
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

        $communiques = Communique::all();
        return view('communique.show', compact('communiques'));
    }
}
