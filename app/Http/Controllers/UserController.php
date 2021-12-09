<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::paginate(15);
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $employees = Employee::all();
        $departments  = Department::pluck('name', 'id')->toArray();
        $positions  = Position::pluck('name', 'id')->toArray();
        $companies = Company::all();
        return view('admin.user.create', compact('roles', 'employees', 'departments', 'positions', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'birthday_date' => 'required',
            'date_admission' => 'required',
            'department' => 'required',
            'companies' => 'required',
            'position' => 'required',
            'roles' => 'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi';
        $user->save();

        $user->employee->birthday_date = $request->birthday_date;
        $user->employee->date_admission = $request->date_admission;
        $user->employee->status = $request->status;
        $user->employee->jefe_directo_id = $request->jefe;
        $user->employee->status = 1;
        $user->employee->save();

        $user->roles()->attach($request->roles);
        $user->employee->companies()->attach($request->companies);
        $user->employee->positions()->attach($request->position);

        return redirect()->action([UserController::class, 'index']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $employees = Employee::all();
        $departments  = Department::pluck('name', 'id')->toArray();
        $positions  = Position::pluck('name', 'id')->toArray();
        $companies = Company::all();
        return view('admin.user.edit', compact('roles', 'employees', 'departments', 'positions', 'companies', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        request()->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'birthday_date' => 'required',
            'date_admission' => 'required',
            'department' => 'required',
            'companies' => 'required',
            'position' => 'required',
            'roles' => 'required',
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = '$2y$10$syIdnDjSzM7PZ7PvA1Irl.oIA3g4Gv712wcoBHkTArOWxNs5/hAoi';
        $user->save();

<<<<<<< HEAD
        $user->employee->birthday_date = $request->birthday_date;
        $user->employee->date_admission = $request->date_admission;
        $user->employee->status = $request->status;
        $user->employee->jefe_directo_id = $request->jefe;
        $user->employee->status = 1;
        $user->employee->save();

        $user->roles()->detach();
        $user->employee->companies()->detach();
        $user->employee->positions()->detach();
        $user->roles()->attach($request->roles);
        $user->employee->companies()->attach($request->companies);
        $user->employee->positions()->attach($request->position);

=======
        $user->roles()->sync($request->roles);
        $users =  User::all();
>>>>>>> c8ceafbb3ca6a0050e8aa43ffccf95c616c96911
        return redirect()->action([UserController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->roles()->detach();
        $user->employee->companies()->detach();
        $user->employee->positions()->detach();
        $user->delete();
        return redirect()->action([UserController::class, 'index']);
    }
}
