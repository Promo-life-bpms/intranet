<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\Manager;
use App\Notifications\RegisteredUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::all();
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
        $manager = User::all()->pluck('name', 'id');

        return view('admin.user.create', compact('roles', 'employees', 'departments', 'positions', 'companies', 'manager'));
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

        $validate_user = User::where('email', '=', $request->email)->exists();

        if ($validate_user == true) {
            return back()->with('message', 'El correo de este usuario ya existe en la base de datos' );
        }


        if ($request->hasFile('image')) {
            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->clientExtension();
            $fileNameToStore = $filename . '.' . $extension;
            $path = $request->file('image')->move('storage/profile/', $fileNameToStore);

            $img = Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(300, 300);
            $img->save(public_path("storage/profile/300x300{$fileNameToStore}"));
        } else {
            $path = null;
        }
        // Crear una contraseña aleatoria
        $pass = Str::random(8);

        $user = new User();
        $user->name = $request->name;
        $user->image = $path;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($pass);
        $user->save();

        $user->employee->birthday_date = $request->birthday_date;
        $user->employee->date_admission = $request->date_admission;
        $user->employee->status = $request->status;
        $user->employee->jefe_directo_id = $request->jefe_directo_id;
        $user->employee->status = 1;
        $user->employee->position_id = $request->position;
        $user->employee->save();

        $user->roles()->attach($request->roles);
        $user->employee->companies()->attach($request->companies);

        $user->directory()->create([
            'type' => 'Email',
            'data' => $user->email,
            'company' => $request->companies[0],
        ]);
        // Enviar notificacion de registro
        $dataNotification = [
            'name' => $request->name . ' ' . $request->lastname,
            'email' => $request->email,
            'password' => $pass,
            'urlEmail' => url('/loginEmail?email=' . $request->email . '&password=' . $pass)
        ];

        $user->notify(new RegisteredUser($dataNotification));

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
        $manager = User::all()->pluck('name', 'id');
        return view('admin.user.edit', compact('roles', 'employees', 'departments', 'positions', 'companies', 'user', 'manager'));
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

        $validate_user = User::where('email', '=', $request->email)->where('id','<>', $user->id)->exists();

        if ($validate_user == true) {
            return back()->with('message', 'El correo de este usuario ya existe en la base de datos' );
        }


        if ($user->image == null) {
            if ($request->hasFile('image')) {

                File::delete($user->image);

                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path = $request->file('image')->move('storage/post/', $fileNameToStore);
            } else {
                $path = null;
            }
        } else {
            if ($request->hasFile('image') == null) {

                $path = $user->image;
            } else {
                File::delete($user->image);

                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->clientExtension();
                $fileNameToStore = $filename . '.' . $extension;
                $path = $request->file('image')->move('storage/post/', $fileNameToStore);
            }
        }


        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->image = $path;
        $user->email = $request->email;
        $user->save();

        $user->employee->birthday_date = $request->birthday_date;
        $user->employee->date_admission = $request->date_admission;
        $user->employee->status = $request->status;

        if ($request->jefe_directo_id == null) {
            if ($user->employee->position_id != null) {
                $user->employee->jefe_directo_id = $user->employee->jefe_directo_id;
            } else {
                $user->employee->jefe_directo_id = $request->jefe_directo_id;
            }
        } else {
            $user->employee->jefe_directo_id = $request->jefe_directo_id;
        }

        $user->employee->status = 1;
        $user->employee->position_id = $request->position;
        $user->employee->save();

        $user->roles()->detach();
        $user->employee->companies()->detach();
        // $user->employee->positions()->detach();
        $user->roles()->attach($request->roles);
        $user->employee->companies()->attach($request->companies);
        // $user->employee->positions()->attach($request->position);

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
        DB::table('users')->where('id',  $user->id)->delete();
        DB::table('employees')->where('user_id',  $user->id)->delete();
        DB::table('manager')->where('users_id',  $user->id)->delete();

        return redirect()->action([UserController::class, 'index']);
    }

    public function getPosition($id)
    {
        $positions = Position::all()->where('department_id', $id)->pluck('name', 'id');
        return json_encode($positions);
    }

    public function getManager($id)
    {
        $departmentID = DB::table('positions')->where('id', $id)->value('department_id');

        if ($departmentID == null) {
            $users = User::all()->pluck('name', 'id');
        } else {
            $managers = Manager::all()->where('department_id', $departmentID)->pluck('users_id', 'users_id');
            $users = User::all()->whereIn('id', $managers)->pluck('name', 'id');
        }

        return json_encode($users);
    }
    public function sendAccess()
    {
        $users = User::all();
        foreach ($users as $user) {
            $pass = Str::random(8);
            $user->password = Hash::make($pass);
            $user->save();
            $dataNotification = [
                'name' => $user->name . ' ' . $user->lastname,
                'email' => $user->email,
                'password' => $pass,
                'urlEmail' => url('/loginEmail?email=' . $user->email . '&password=' . $pass)
            ];
            $user->notify(new RegisteredUser($dataNotification));
        }
    }
    public function sendAccessPerUser(User $user)
    {
        $pass = Str::random(8);
        $user->password = Hash::make($pass);
        $user->save();
        $dataNotification = [
            'name' => $user->name . ' ' . $user->lastname,
            'email' => $user->email,
            'password' => $pass,
            'urlEmail' => url('/loginEmail?email=' . $user->email . '&password=' . $pass)
        ];
        $user->notify(new RegisteredUser($dataNotification));
        return redirect()->back();
    }
}
