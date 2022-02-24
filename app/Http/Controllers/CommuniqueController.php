<?php

namespace App\Http\Controllers;

use App\Models\Communique;
use App\Models\CommuniqueCompany;
use App\Models\CommuniqueDepartment;
use App\Models\Company;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CommuniqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $employeeID = DB::table('employees')->where('user_id', $id)->value('id');
        $companyEmployee = DB::table('company_employee')->where('employee_id', $employeeID)->value('company_id');
        $companyCom = CommuniqueCompany::all()->where('company_id', $companyEmployee)->pluck('communique_id', 'communique_id');
        $companyCommuniques = Communique::all()->whereIn('id', $companyCom);        

        return view('communique.index', compact('companyCommuniques'));
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
            'description' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->clientExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $request->file('image')->move('storage/post/', $fileNameToStore);
        } else {
            $path = "/img/communique.svg";
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

        $communique = new Communique();
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
     * Display the specified resource.
     *
     * @param  \App\Models\Communique  $communique
     * @return \Illuminate\Http\Response
     */
    public function show(/* Communique $communique */)
    {
        $communiques =  Communique::all(); /* auth()->user()->employee->communiques; */

       foreach( $communiques as  $communique){
            $day = $communique->created_at->format('Y-m-d');
       
            $expiration = Carbon::parse($day)->addDays(5);
            $expirationFormat = $expiration->format('Y-m-d');

            $today = Carbon::now();
            $todayFormat = $today->format('Y-m-d');

            if( $todayFormat >=$expirationFormat   ){
                $communique->delete();
            }
       }
        
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


        if ($request->hasFile('image') == null) {

            if ($communique->image == null) {
                $path = null;
            } else {
                $path = $communique->image;
            }
        } else {

            File::delete($communique->image);

            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->clientExtension();
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

            File::delete($communique->file);

            $filenameWithExt2 = $request->file('file')->getClientOriginalName();
            $filename2 = pathinfo($filenameWithExt2, PATHINFO_FILENAME);
            $extension2 = $request->file('file')->clientExtension();
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

    public function department()
    {
        $id = Auth::user()->id;
        $employeeID = DB::table('employees')->where('user_id', $id)->value('id');
        $employeePosition = DB::table('employees')->where('id', $employeeID)->value('position_id');
        $employeeDepartment = DB::table('positions')->where('id', $employeePosition)->value('department_id');
        $departmentCom = CommuniqueDepartment::all()->where('department_id', $employeeDepartment)->pluck('communique_id', 'communique_id');
        $departmentCommuniques = Communique::all()->whereIn('id', $departmentCom);

        return view('communique.area', compact('departmentCommuniques'));
    }
}
