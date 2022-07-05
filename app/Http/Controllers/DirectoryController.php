<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Directory;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class DirectoryController
 * @package App\Http\Controllers
 */
class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::where('status', 1)->get();
        return view('directory.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $list = User::where('status', 1)->get();
        $listCompany = Company::all();
        $directory = new Directory();
        return view('directory.create', compact('directory', 'list', 'listCompany'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Directory::$rules);

        request()->validate([
            'user_id' => 'required',
            'type' => 'required',
            'data' => 'required',
            'company' => 'required',
        ]);

        $directory = Directory::create($request->all());

        return redirect()->route('directories.index')
            ->with('success', 'Directory created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $directory = Directory::where('user_id', $id)->get();
        $user = User::where('id', $id)->get();
        $companies = Company::all()->pluck('name_company', 'id');
        return view('directory.show', compact('directory', 'user', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $list = User::where('status', 1)->get();
        $listCompany = Company::all();
        $directory = Directory::find($id);
        return view('directory.edit', compact('directory', 'list', 'listCompany'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Directory $directory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Directory $directory)
    {
        request()->validate(Directory::$rules);

        request()->validate([
            'user_id' => 'required',
            'type' => 'required',
            'data' => 'required',
            'company' => 'required',
        ]);

        $directory->update($request->all());

        return redirect()->route('directories.index')
            ->with('success', 'Directory updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $directory = Directory::find($id)->delete();

        return redirect()->route('directories.index')
            ->with('success', 'Directory deleted successfully');
    }
}
