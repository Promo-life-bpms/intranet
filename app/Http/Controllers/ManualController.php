<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ManualController extends Controller
{

  /*   public function __invoke()
    {
        return  view('manual.index');
    } */
     public function index(){
        
        $manual = Manual::all();
        return view('manual.index', compact('manual'));
    }

    public function create(){
        
        return view('manual.create');
    }

    public function edit(Manual $manual){
        
        return view('manual.edit', compact('manual'));
    }

    public function store(Request $request){
        
        $request->validate([
            'name' => 'required',
            'file' => 'required'
        ]);


        if ($request->hasFile('img')) {
            $filenameWithExt = $request->file('img')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('img')->clientExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $request->file('img')->move('storage/post/', $fileNameToStore);
        } else {
            $path = null;
        }

        if ($request->hasFile('file')) {
            $filenameWithExt2 = $request->file('file')->getClientOriginalName();
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            $extension2 = $request->file('file')->clientExtension();
            $fileNameToStore2 = $filename2 . '.' . $extension2;
            $path2 = $request->file('file')->move('storage/post/', $fileNameToStore2);
        } else {
            $path2 = null;
        }

        $manual= new Manual();
        $manual-> name = $request->name;
        $manual-> file = $path2;
        $manual->img = $path;
        $manual->save();
        
        return redirect()->action([ManualController::class,'index']);
    }

    public function update(Request $request, Manual $manual){

            
        $request->validate([
            'name' => 'required',
        ]);


        if ($manual->file == null) {
            if ($request->hasFile('file')) {

                File::delete($manual->file);

                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path = $request->file('file')->move('storage/post/', $fileNameToStore);
            } else {
                $path2 = null;
            }
        } else {
            if ($request->hasFile('file')) {
                File::delete($manual->file);

                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path2 = $request->file('file')->move('storage/post/', $fileNameToStore);
            } else {
                $path2 = $manual->file;
            }
        }


        if ($manual->img == null) {
            if ($request->hasFile('img')) {

                File::delete($manual->img);

                $filenameWithExt = $request->file('img')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('img')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path = $request->file('img')->move('storage/post/', $fileNameToStore);
            } else {
                $path = null;
            }
        } else {
            if ($request->hasFile('img') == null) {

                $path = $manual->img;
            } else {
                File::delete($manual->img);

                $filenameWithExt = $request->file('img')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('img')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path = $request->file('img')->move('storage/post/', $fileNameToStore);
            }
        }
       
        $manual-> name = $request->name;
        $manual-> file = $path2;
        $manual->img = $path;
        $manual->save();

        return redirect()->action([ManualController::class,'index']);
    }
 
    public function delete(Manual $manual){

        if ($manual->img!=null) {
            File::delete($manual->img);
        }
        File::delete($manual->file);

        $manual->delete();
        return redirect()->action([ManualController::class,'index']);
    }
}
