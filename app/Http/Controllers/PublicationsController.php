<?php

namespace App\Http\Controllers;

use App\Models\Publications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublicationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    //guardar info
    public function store(Request $request)
    {
        // Obtener los datos de la publicacion
        if (($request->file('photo_public') || $request->content_publication)) {
            $imageName = "";
            $content = "";
            if ($request->file('photo_public')) {
                $file = $request->file('photo_public');
                $imageName = 'photos/' . $file->getClientOriginalName();

                Storage::disk('local')->put('public/' . $imageName, File::get($file));
            }

            if ($request->content_publication) {
                $content = $request->content_publication;
            }

            //Crear publicacion
            $publication = Publications::create([
                'user_id' => auth()->user()->id,
                'content_publication' => $content,
                'photo_public' => $imageName
            ]);
            return redirect()->action([HomeController::class]);
        } else {
            return back()->with('errorData', 'No hay contenido para tu post!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Publications $publications)
    {
        //Pasa la cantidad de likes a la vista

        //$likes=$publications->like->count();

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Responsez
     */
    public function edit($id)
    {
        //
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
