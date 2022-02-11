<?php

namespace App\Http\Controllers;

use App\Models\Manual;
use Illuminate\Http\Request;

/**
 * Class ManualController
 * @package App\Http\Controllers
 */
class ManualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manuals = Manual::paginate();

        return view('manual.index', compact('manuals'))
            ->with('i', (request()->input('page', 1) - 1) * $manuals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manual = new Manual();
        return view('manual.create', compact('manual'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Manual::$rules);

        $manual = Manual::create($request->all());

        return redirect()->route('manuals.index')
            ->with('success', 'Manual created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $manual = Manual::find($id);

        return view('manual.show', compact('manual'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manual = Manual::find($id);

        return view('manual.edit', compact('manual'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Manual $manual
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Manual $manual)
    {
        request()->validate(Manual::$rules);

        $manual->update($request->all());

        return redirect()->route('manuals.index')
            ->with('success', 'Manual updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $manual = Manual::find($id)->delete();

        return redirect()->route('manuals.index')
            ->with('success', 'Manual deleted successfully');
    }
}
