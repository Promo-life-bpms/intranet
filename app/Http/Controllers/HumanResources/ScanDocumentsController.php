<?php

namespace App\Http\Controllers\HumanResources;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\UserDocumentation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ScanDocumentsController extends Controller
{
    public function scanDocuments($id)
    {
        $status = 1;

        $user = User::where('id',$id)->get()->last();
        $user_documents= UserDocumentation::all()->where('user_id', $id);
        $user_details = UserDetails::where('user_id',$id)->get();

        $status = $user->status;
        return view('admin.user.scan', compact('id','user_documents','user_details','status' ));
    }

    public function storeDocuments(Request $request)
    {
        $request->validate([
            'documents' => 'required',
            'description' => 'required'
        ]);
        $path="";
        $extension="";

        if ($request->hasFile('documents')) {
            $filenameWithExt = $request->file('documents')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('documents')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $path= $request->file('documents')->move('storage/user/documents', $fileNameToStore);
        }

        $create_document=new UserDocumentation();
        $create_document->type=$extension; //$entension sirve para colocar el tipo de archivo que es///
        $create_document->description=$request->description;
        $create_document->resource=$path;
        $create_document->user_id=$request->user_id;
        $create_document->save();
        return redirect()->back()->with('message', "Documento guardado correctamente");
    }

    public function deleteDocuments(Request $request){
        $request1 =UserDocumentation::all()->where('id', $request->document_id)->last();
        File::delete($request1->resource);
        DB::table('users_documentation')->where('id', $request->document_id)->delete();
        return redirect()->back()->with('message', 'Documento eliminado');
    }
    
    public function updateDocuments(Request $request)
    {

        if($request->description != '' || $request != null){

        }
     
        $document = UserDocumentation::all()->where('id', $request->id)->last();
        
        $path=$document->resource;
        $extension=$document->type;
        if ($request->hasFile('documents')) {
            File::delete($document->resource);

            $filenameWithExt = $request->file('documents')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('documents')->clientExtension();
            $fileNameToStore = time(). $filename . '.' . $extension;
            $path= $request->file('documents')->move('storage/user/documents', $fileNameToStore);
        }
        
        DB::table('users_documentation')->where('id', $request->id)->update(['resource' => $path, 
        'description'=>$request->description, 'type'=>$extension]);
        return redirect()->back()->with('message', "Documento actualizado correctamente");
    }
}