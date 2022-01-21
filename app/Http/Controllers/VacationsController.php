<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use App\Models\Vacations;
use Illuminate\Http\Request;

class VacationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $vacations = User::paginate(15);

        $rhID = 1;
        $rhPosition = Position::all()->where('department_id', $rhID)->pluck('id', 'id');
        $rhEmployeesPos = Employee::all()->whereIn('position_id', $rhPosition)->pluck('id', 'user_id');
        $rhData = User::all()->whereIn('id', $rhEmployeesPos)->pluck('id', 'id');
        $rh = Vacations::all()->whereIn('users_id', $rhData);

        $adminID = 2;
        $adminPosition = Position::all()->where('department_id', $adminID)->pluck('id', 'id');
        $adminEmployeesPos = Employee::all()->whereIn('position_id', $adminPosition)->pluck('id', 'user_id');
        $adminData = User::all()->whereIn('id', $adminEmployeesPos)->pluck('id', 'id');
        $admin = Vacations::all()->whereIn('users_id', $adminData);

        $ventasBHID = 3;
        $ventasBHPosition = Position::all()->where('department_id', $ventasBHID)->pluck('id', 'id');
        $ventasBHEmployeesPos = Employee::all()->whereIn('position_id', $ventasBHPosition)->pluck('id', 'user_id');
        $ventasBHData = User::all()->whereIn('id', $ventasBHEmployeesPos)->pluck('id', 'id');
        $ventasBH = Vacations::all()->whereIn('users_id', $ventasBHData);


        $importacionesID = 4;
        $importacionesPosition = Position::all()->where('department_id', $importacionesID)->pluck('id', 'id');
        $importacionesEmployeesPos = Employee::all()->whereIn('position_id', $importacionesPosition)->pluck('id', 'user_id');
        $importacionesData = User::all()->whereIn('id', $importacionesEmployeesPos)->pluck('id', 'id');
        $importaciones = Vacations::all()->whereIn('users_id', $importacionesData);

        $disenoID = 5;
        $disenoPosition = Position::all()->where('department_id', $disenoID)->pluck('id', 'id');
        $disenoEmployeesPos = Employee::all()->whereIn('position_id', $disenoPosition)->pluck('id', 'user_id');
        $disenoData = User::all()->whereIn('id', $disenoEmployeesPos)->pluck('id', 'id');
        $diseno = Vacations::all()->whereIn('users_id', $disenoData);

        $sistemasID = 6;
        $sistemasPosition = Position::all()->where('department_id', $sistemasID)->pluck('id', 'id');
        $sistemasEmployeesPos = Employee::all()->whereIn('position_id', $sistemasPosition)->pluck('id', 'user_id');
        $sistemasData = User::all()->whereIn('id', $sistemasEmployeesPos)->pluck('id', 'id');
        $sistemas = Vacations::all()->whereIn('users_id', $sistemasData);

        $operacionesID = 7;
        $operacionesPosition = Position::all()->where('department_id', $operacionesID)->pluck('id', 'id');
        $operacionesEmployeesPos = Employee::all()->whereIn('position_id', $operacionesPosition)->pluck('id', 'user_id');
        $operacionesData = User::all()->whereIn('id', $operacionesEmployeesPos)->pluck('id', 'id');
        $operaciones = Vacations::all()->whereIn('users_id', $operacionesData);

        $ventasPLID = 8;
        $ventasPLPosition = Position::all()->where('department_id', $ventasPLID)->pluck('id', 'id');
        $ventasPLEmployeesPos = Employee::all()->whereIn('position_id', $ventasPLPosition)->pluck('id', 'user_id');
        $ventasPLData = User::all()->whereIn('id', $ventasPLEmployeesPos)->pluck('id', 'id');
        $ventasPL = Vacations::all()->whereIn('users_id', $ventasPLData);

        $tecnologiaID = 9;
        $tecnologiaPosition = Position::all()->where('department_id', $tecnologiaID)->pluck('id', 'id');
        $tecnologiaEmployeesPos = Employee::all()->whereIn('position_id', $tecnologiaPosition)->pluck('id', 'user_id');
        $tecnologiaData = User::all()->whereIn('id', $tecnologiaEmployeesPos)->pluck('id', 'id');
        $tecnologia = Vacations::all()->whereIn('users_id', $tecnologiaData);

        $ecommerceID = 10;
        $ecommercePosition = Position::all()->where('department_id', $ecommerceID)->pluck('id', 'id');
        $ecommerceEmployeesPos = Employee::all()->whereIn('position_id', $ecommercePosition)->pluck('id', 'user_id');
        $ecommerceData = User::all()->whereIn('id', $ecommerceEmployeesPos)->pluck('id', 'id');
        $ecommerce = Vacations::all()->whereIn('users_id', $ecommerceData);

        $cancunID = 11;
        $cancunPosition = Position::all()->where('department_id', $cancunID)->pluck('id', 'id');
        $cancunEmployeesPos = Employee::all()->whereIn('position_id', $cancunPosition)->pluck('id', 'user_id');
        $cancunData = User::all()->whereIn('id', $cancunEmployeesPos)->pluck('id', 'id');
        $cancun = Vacations::all()->whereIn('users_id', $cancunData);

        $direccionID = 12;
        $direccionPosition = Position::all()->where('department_id', $direccionID)->pluck('id', 'id');
        $direccionEmployeesPos = Employee::all()->whereIn('position_id', $direccionPosition)->pluck('id', 'user_id');
        $direccionData = User::all()->whereIn('id', $direccionEmployeesPos)->pluck('id', 'id');
        $direccion = Vacations::all()->whereIn('users_id', $direccionData);

        return view('admin.vacations.index', compact('vacations', 'rh', 'admin', 'ventasBH', 'importaciones', 'diseno', 'sistemas', 'operaciones', 'ventasPL', 'tecnologia', 'ecommerce', 'cancun', 'direccion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all()->pluck('name', 'id');
        return view('admin.vacations.create', compact('users'));
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
            'days_availables' => 'required',
            'expiration' => 'required',
            'users_id' => 'required'
        ]);

        $vacation = new Vacations();
        $vacation->days_availables = $request->days_availables;
        $vacation->expiration = $request->expiration;
        $vacation->users_id = $request->users_id;
        $vacation->save();

        return redirect()->action([VacationsController::class, 'edit'], ['user'=>$request->users_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $vacations = $user->vacationsAvailables;

        return view('admin.vacations.edit', compact('vacations','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vacations $vacation)
    {
        request()->validate([
            'days_availables' => 'required',
            'expiration' => 'required',
        ]);

        $vacation->days_availables = $request->days_availables;
        $vacation->expiration = $request->expiration;
        $vacation->save();

        return redirect()->action([VacationsController::class, 'edit'], ['user'=>$vacation->users_id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vacations $vacation)
    {
        $vacation->delete();
        return redirect()->action([VacationsController::class, 'edit'], ['user'=>$vacation->users_id]);
    }
}
