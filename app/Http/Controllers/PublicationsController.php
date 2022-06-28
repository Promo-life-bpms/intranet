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
        if (($request->items != '' || $request->content_publication != '')) {
            if ($request->content_publication) {
                $content = $request->content_publication;
            } else {
                $content = '';
            }

            //Crear publicacion
            $publication = Publications::create([
                'user_id' => auth()->user()->id,
                'content_publication' => $content,
                'photo_public' => ''
            ]);

            if (trim($request->items) != "" || $request->items != null) {
                foreach (explode(',', $request->items) as $item) {
                    # code...
                    //Registar imagen
                    $data = [
                        'resource' => $item,
                        'type_file' => 'photo',
                    ];
                    $publication->files()->create($data);
                }
            }


            return redirect()->action([HomeController::class]);
        } else {
            return back()->with('errorData', 'No hay contenido para tu post!');
        }
    }
    public function uploadItems(Request $request)
    {
        $imagen = $request->file('file');
        $nombreImagen = time() . ' ' . str_replace(',', ' ', $imagen->getClientOriginalName());
        $imagen->move(public_path('storage/posts/'), $nombreImagen);
        return response()->json(['correcto' => $nombreImagen]);
    }
    public function deleteItem(Request $request)
    {
        if ($request->ajax()) {
            $imagen = $request->get('imagen');
            if (File::exists('storage/posts/' . $imagen)) {
                File::delete('storage/posts/' . $imagen);
            }
            return response(['mensaje' => 'Imagen Eliminada', 'imagen' => $imagen], 200);
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
