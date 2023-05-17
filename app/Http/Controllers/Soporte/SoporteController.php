<?php

namespace App\Http\Controllers\Soporte;

use App\Models\Access;
use Illuminate\Http\Request;
use App\Models\Soporte\Categoria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SoporteController extends Controller
{
    //

   
    public function index()
    {
        return view('soporte.index');
    }

    public function solucion(){
        return view('soporte.solucion');
    }

    public function admin()
    {
        return view('soporte.admin');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename . '_' . time() . '.' . $extension;

            //Upload File
            $request->file('upload')->storeAs('public/uploads', $filenametostore);
            $request->file('upload')->storeAs('public/uploads/thumbnail', $filenametostore);

            //Resize image here
            // $thumbnailpath = public_path('storage/uploads/thumbnail/' . $filenametostore);
            // $img = Image::make($thumbnailpath)->resize(500, 150, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            // $img->save($thumbnailpath);

            echo json_encode([
                'default' => asset('storage/uploads/' . $filenametostore),
                '500' => asset('storage/uploads/thumbnail/' . $filenametostore)
            ]);
        }
    }
}
