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
use Cache;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =  User::where('status', true)->get();
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
            return back()->with('message', 'El correo de este usuario ya existe en la base de datos');
        }


        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            ]);

            $filenameWithExt = $request->file('image')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->clientExtension();
            $fileNameToStore = time() . $filename . '.' . $extension;
            $path = 'storage/profile/200x300' . $fileNameToStore;

            $request->file('image')->move('storage/profile/', $fileNameToStore);
            Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(200, 300)->save(public_path("storage/profile/200x300{$fileNameToStore}"));
            Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(300, 300)->save(public_path("storage/profile/300x300{$fileNameToStore}"));
            File::delete(public_path("storage/profile/{$fileNameToStore}"));
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

        $validate_user = User::where('email', '=', $request->email)->where('id', '<>', $user->id)->exists();

        if ($validate_user == true) {
            return back()->with('message', 'El correo de este usuario ya existe en la base de datos');
        }


        if ($user->image == null) {
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpg,jpeg,png,gif',
                ]);

                File::delete($user->image);

                $filenameWithExt = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('image')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = 'storage/profile/200x300' . $fileNameToStore;

                $request->file('image')->move('storage/profile/', $fileNameToStore);
                Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(200, 300)->save(public_path("storage/profile/200x300{$fileNameToStore}"));
                Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(300, 300)->save(public_path("storage/profile/300x300{$fileNameToStore}"));
                File::delete(public_path("storage/profile/{$fileNameToStore}"));
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
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = 'storage/profile/200x300' . $fileNameToStore;

                $request->file('image')->move('storage/profile/', $fileNameToStore);
                Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(200, 300)->save(public_path("storage/profile/200x300{$fileNameToStore}"));
                Image::make(public_path("storage/profile/{$fileNameToStore}"))->fit(300, 300)->save(public_path("storage/profile/300x300{$fileNameToStore}"));
                File::delete(public_path("storage/profile/{$fileNameToStore}"));
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
        $user->status = false;
        $user->email = time() . 'disabled' . $user->email;
        $user->save();
        return redirect()->action([UserController::class, 'index'])->with('success', 'El usuario ' . $user->name . ' ' . $user->lastname . ' ha sido eliminado');
    }

    public function exportUsuarios()
    {

        $documento = new Spreadsheet();
        $documento
            ->getProperties()
            ->setCreator("Aquí va el creador, como cadena")
            ->setLastModifiedBy('Parzibyte') // última vez modificado por
            ->setTitle('Mi primer documento creado con PhpSpreadSheet')
            ->setSubject('El asunto')
            ->setDescription('Este documento fue generado para parzibyte.me')
            ->setKeywords('etiquetas o palabras clave separadas por espacios')
            ->setCategory('La categoría');

        $nombreDelDocumento = "Reporte de Usuarios con corte al ".now()->format('d-m-Y').".xlsx";

        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("Usuarios");
        $users = User::where('status', 1)->get();
        $i = 2;
        $hoja->setCellValueByColumnAndRow(1, 1,  'Nombre');
        $hoja->setCellValueByColumnAndRow(2, 1,  'Apellido');
        $hoja->setCellValueByColumnAndRow(3, 1,  'Correo');
        $hoja->setCellValueByColumnAndRow(4, 1,  'Fecha de Cumpleaños');
        $hoja->setCellValueByColumnAndRow(5, 1,  'Fecha de Ingreso');
        $hoja->setCellValueByColumnAndRow(6, 1,  'Departamento');
        $hoja->setCellValueByColumnAndRow(7, 1,  'Puesto');
        $hoja->setCellValueByColumnAndRow(8, 1,  'Jefe Directo');
        $hoja->setCellValueByColumnAndRow(9, 1,  'Empresas');
        $hoja->setCellValueByColumnAndRow(10, 1,  'Roles');
        $hoja->setCellValueByColumnAndRow(11, 1,  'Ultimo Inicio de Sesion');

        foreach ($users as $user) {
            $hoja->setCellValueByColumnAndRow(1, $i,  $user->name);
            $hoja->setCellValueByColumnAndRow(2, $i,  $user->lastname);
            $hoja->setCellValueByColumnAndRow(3, $i,  $user->email);
            $hoja->setCellValueByColumnAndRow(4, $i,  $user->employee->birthday_date->format('d/m/Y'));
            $hoja->setCellValueByColumnAndRow(5, $i,  $user->employee->date_admission->format('d/m/Y'));
            $hoja->setCellValueByColumnAndRow(6, $i,  $user->employee->position->department->name);
            $hoja->setCellValueByColumnAndRow(7, $i, $user->employee->position->name);
            if ($user->employee->jefeDirecto) {
                $hoja->setCellValueByColumnAndRow(8, $i, $user->employee->jefeDirecto->user->name . ' ' . $user->employee->jefeDirecto->user->lastname);
            } else {
                $hoja->setCellValueByColumnAndRow(8, $i, 'Ninguno');
            }
            $companies = '';
            if ($user->employee->companies) {
                foreach ($user->employee->companies as $company) {
                    $companies = $companies . $company->name_company . ', ';
                }
            }
            $hoja->setCellValueByColumnAndRow(9, $i, $companies);
            $roles = '';
            if ($user->roles) {
                foreach ($user->roles as $role) {
                    $roles = $roles . $role->display_name . ', ';
                }
            }
            $hoja->setCellValueByColumnAndRow(10, $i, $roles);
            $hoja->setCellValueByColumnAndRow(11, $i,  $user->last_login);
            $i++;
        }

        // Para crear siguiente hoja
        $hoja = $documento->createSheet();

        // Generale el nombre de hoja para que confirmes que tienes la hoja creada
        $hoja->setTitle("Usuarios Eliminados");
        $users = User::where('status', 0)->get();
        $i = 2;
        $hoja->setCellValueByColumnAndRow(1, 1,  'Nombre');
        $hoja->setCellValueByColumnAndRow(2, 1,  'Apellido');
        $hoja->setCellValueByColumnAndRow(3, 1,  'Correo');
        $hoja->setCellValueByColumnAndRow(4, 1,  'Fecha de Cumpleaños');
        $hoja->setCellValueByColumnAndRow(5, 1,  'Fecha de Ingreso');
        $hoja->setCellValueByColumnAndRow(6, 1,  'Departamento');
        $hoja->setCellValueByColumnAndRow(7, 1,  'Puesto');
        $hoja->setCellValueByColumnAndRow(8, 1,  'Jefe Directo');
        $hoja->setCellValueByColumnAndRow(9, 1,  'Empresas');
        $hoja->setCellValueByColumnAndRow(10, 1,  'Roles');
        $hoja->setCellValueByColumnAndRow(11, 1,  'Ultimo Inicio de Sesion');
        $hoja->setCellValueByColumnAndRow(12, 1,  'Fecha de Eliminacion de la Intranet');

        foreach ($users as $user) {
            $hoja->setCellValueByColumnAndRow(1, $i,  $user->name);
            $hoja->setCellValueByColumnAndRow(2, $i,  $user->lastname);
            $hoja->setCellValueByColumnAndRow(3, $i,  $user->email);
            $hoja->setCellValueByColumnAndRow(4, $i,  $user->employee->birthday_date->format('d/m/Y'));
            $hoja->setCellValueByColumnAndRow(5, $i,  $user->employee->date_admission->format('d/m/Y'));
            $hoja->setCellValueByColumnAndRow(6, $i,  $user->employee->position->department->name);
            $hoja->setCellValueByColumnAndRow(7, $i, $user->employee->position->name);
            if ($user->employee->jefeDirecto) {
                $hoja->setCellValueByColumnAndRow(8, $i, $user->employee->jefeDirecto->user->name . ' ' . $user->employee->jefeDirecto->user->lastname);
            } else {
                $hoja->setCellValueByColumnAndRow(8, $i, 'Ninguno');
            }
            $companies = '';
            if ($user->employee->companies) {
                foreach ($user->employee->companies as $company) {
                    $companies = $companies . $company->name_company . ', ';
                }
            }
            $hoja->setCellValueByColumnAndRow(9, $i, $companies);
            $roles = '';
            if ($user->roles) {
                foreach ($user->roles as $role) {
                    $roles = $roles . $role->display_name . ', ';
                }
            }
            $hoja->setCellValueByColumnAndRow(10, $i, $roles);
            $hoja->setCellValueByColumnAndRow(11, $i,  $user->last_login);
            $hoja->setCellValueByColumnAndRow(12, $i,  $user->updated_at->format('d/m/Y'));
            $i++;
        }

        /**
         * Los siguientes encabezados son necesarios para que
         * el navegador entienda que no le estamos mandando
         * simple HTML
         * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
         */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;
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
    /* public function sendAccess()
    {
        $users = User::where('status', 1)->get();
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
    } */
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
