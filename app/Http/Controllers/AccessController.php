<?php

namespace App\Http\Controllers;

use App\Models\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $url1 = env("URL_COURSES", "https://elearning.promolife.lat");
        $url2 = env("URL_TASK_MANAGER", "https://taskmanagerplbh.promolife.lat");
        $url3 = env("URL_CATALOGO", "https://catalogodeproductos.promolife.online");
        $routeCourses = $url1 . "/loginEmail?email=" . auth()->user()->email . "&password=password";
        $routeTaskManager = $url2 . "/loginEmail?email=" . auth()->user()->email . "&password=password";
        $routeCatalogo = $url3 . "/loginEmail?email=" . auth()->user()->email . "&password=password";
        $id = Auth::user()->id;
        $access = Access::all()->where('users_id', $id);
        return view('access.index', compact('access', 'routeCourses', 'routeTaskManager', 'routeCatalogo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('access.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $id = Auth::user()->id;

        $request->validate([
            'title' => 'required',
            'link' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = $filename . '.' . $extension;
            // Upload Image
            $path = $request->file('image')->move('storage/post/', $fileNameToStore);
        } else {
            $path = 'https://image.freepik.com/free-vector/characters-people-holding-internet-search-icons_53876-35212.jpg';
        }

        $access = new Access();
        $access->title = $request->title;
        $access->link = $request->link;
        $access->user = $request->user;
        $access->password = $request->password;
        $access->image = $path;
        $access->users_id = $id;
        $access->save();

        return redirect()->action([AccessController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Access
     * @return \Illuminate\Http\Response
     */
    public function show(Access $acc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Access
     * @return \Illuminate\Http\Response
     */
    public function edit(Access $acc)
    {
        return view('access.edit', compact('acc'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Access
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Access $acc)
    {
        $id = Auth::user()->id;

        $request->validate([
            'title' => 'required',
            'link' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            // Get Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Extension
            $extension = $request->file('image')->getClientOriginalExtension();
            // Filename To store
            $fileNameToStore = $filename . '.' . $extension;
            // Upload Image
            $path = $request->file('image')->move('storage/post/', $fileNameToStore);
        } else {
            $path = 'https://image.freepik.com/free-vector/characters-people-holding-internet-search-icons_53876-35212.jpg';
        }

        $acc->title = $request->title;
        $acc->link = $request->link;
        $acc->user = $request->user;
        $acc->password = $request->password;
        $acc->image = $path;
        $acc->users_id = $id;
        $acc->save();

        return redirect()->action([AccessController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Access
     * @return \Illuminate\Http\Response
     */
    public function destroy(Access $acc)
    {
        $acc->delete();
        return redirect()->action([AccessController::class, 'index']);
    }
}
