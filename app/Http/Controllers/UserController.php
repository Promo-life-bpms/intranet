<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HumanResources\UserDetails;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\Manager;
use App\Models\UserDetails as ModelsUserDetails;
use App\Notifications\RegisteredUser;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
        $manager = DB::table('users')->select('users.id', 'users.name')->where('users.status', 1)
                                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                                    ->whereNotIn('role_user.role_id', [7])
                                    ->distinct()
                                    ->pluck('name', 'id');


        //$manager = User::all()->pluck('name', 'id');
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

        return redirect()->back()->with('message', 'Información actualizada correctamente');

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

    public function userDetails($id)
    {

        $user = User::find($id);
        $user_details = ModelsUserDetails::where('user_id',$id)->get()->last();
        $companies = Company::all()->pluck('name_company', 'id');
        $departments = Department::all()->pluck('name','id');

        $get_birthdate = $user->employee->birthday_date;
        $carbon =  $carbon = new \Carbon\Carbon();
        $date = $carbon->now();

        $today = $date->format('Y-m-d');
        $birthday = $get_birthdate ->format('Y-m-d');

        $age = date_diff(date_create($birthday), date_create($today))->y;

        return view('admin.user.details', compact('user_details', 'companies', 'departments', 'age', 'user'));
    }

    public function updateUserDetails(Request $request)
    {

        $user_details = ModelsUserDetails::where('user_id',$request->user_id)->get()->last();

        if($user_details == null){
            $create_user_details = new ModelsUserDetails();
            $create_user_details->user_id = $request->user_id;
            $create_user_details->civil_status = $request->civil_status;
            $create_user_details->age = $request->age;
            $create_user_details->gender = $request->gender;
            $create_user_details->nacionality = $request->nacionality;
            $create_user_details->id_credential = $request->id_credential;
            $create_user_details->rfc = $request->rfc;
            $create_user_details->nss = $request->nss;
            $create_user_details->curp = $request->curp;
            $create_user_details->phone = $request->phone;
            $create_user_details->message_phone = $request->message_phone;
            $create_user_details->email = $request->email;
            $create_user_details->fathers_name = $request->fathers_name;
            $create_user_details->mothers_name = $request->mothers_name;
            $create_user_details->full_address = $request->full_address;
            $create_user_details->place_of_birth = $request->place_of_birth;
            $create_user_details->street = $request->street;
            $create_user_details->colony = $request->colony;
            $create_user_details->delegation = $request->delegation;
            $create_user_details->postal_code = $request->postal_code;
            $create_user_details->fiscal_postal_code = $request->fiscal_postal_code;
            $create_user_details->home_phone = $request->home_phone;
            $create_user_details->home_references = $request->home_references;
            $create_user_details->house_characteristics = $request->house_characteristics;
            $create_user_details->contract_duration = $request->contract_duration;
            $create_user_details->month_salary_gross = $request->month_salary_gross;
            $create_user_details->month_salary_net = $request->month_salary_net;
            $create_user_details->daily_salary = $request->daily_salary;
            $create_user_details->daily_salary_letter = $request->daily_salary_letter;
            $create_user_details->bank_name = $request->bank_name;
            $create_user_details->card_number = $request->card_number;
            $create_user_details->infonavit_credit = $request->infonavit_credit;
            $create_user_details->factor_credit_number = $request->factor_credit_number;
            $create_user_details->fonacot_credit = $request->fonacot_credit;
            $create_user_details->discount_credit_number = $request->discount_credit_number;
            $create_user_details->save();
        }else{
            DB::table('users_details')->where('user_id',$request->user_id)->update([
                'civil_status' => $request->civil_status,
                'age' => $request->age,
                'gender' => $request->gender,
                'nacionality' => $request->nacionality,
                'id_credential' => $request->id_credential,
                'rfc' => $request->rfc,
                'nss' => $request->nss,
                'curp' => $request->curp,
                'phone' => $request->phone,
                'message_phone' => $request->message_phone,
                'email' => $request->email,
                'fathers_name' => $request->fathers_name,
                'mothers_name' => $request->mothers_name,
                'full_address' => $request->full_address,
                'place_of_birth' => $request->place_of_birth,
                'street' => $request->street,
                'colony' => $request->colony,
                'delegation' => $request->delegation,
                'postal_code' => $request->postal_code,
                'fiscal_postal_code' => $request->fiscal_postal_code,
                'home_phone' => $request->home_phone,
                'home_references' => $request->home_references,
                'house_characteristics' => $request->house_characteristics,
                'contract_duration' => $request->contract_duration,
                'month_salary_gross' => $request->month_salary_gross,
                'month_salary_net' => $request->month_salary_net,
                'daily_salary' => $request->daily_salary,
                'daily_salary_letter' => $request->daily_salary_letter,
                'bank_name' => $request->bank_name,
                'card_number' => $request->card_number,
                'infonavit_credit' => $request->infonavit_credit,
                'factor_credit_number' => $request->factor_credit_number,
                'fonacot_credit' => $request->fonacot_credit,
                'discount_credit_number' => $request->discount_credit_number,
            ]);
        }

        return redirect()->back()->with('message', 'Información actualizada correctamente');
    }
}
