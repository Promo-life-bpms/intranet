<?php

namespace App\Http\Controllers;

use App\Exports\VacationsExport;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use App\Models\User;
use App\Models\VacationPerYear;
use App\Models\Vacations;
use App\Notifications\InfoRemembersAdmin;
use App\Notifications\TakeVacationsNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VacationsController extends Controller
{
    public $time;
    public function __construct()
    {
        $this->time = Carbon::now();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', 1)->get();
        return view('admin.vacations.index', compact('users'));
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

        return redirect()->action([VacationsController::class, 'index']);
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
    public function edit(Vacations $vacation)
    {
        return view('admin.vacations.edit', compact('vacation'));
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
            'period_days' => 'required',
            'current_days' => 'required',
            'dv' => 'required',
        ]);

        $vacation->period_days = $request->period_days;
        $vacation->current_days = $request->current_days;
        $vacation->dv = $request->dv;

        $vacation->save();

        return redirect()->action([VacationsController::class, 'index']);
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
        return redirect()->action([VacationsController::class, 'edit'], ['user' => $vacation->users_id]);
    }

    public function export()
    {
        return Excel::download(new VacationsExport, 'vacaciones.xlsx');
    }

    public function updateInformationVacations()
    {
        $users = User::where('status', 1)->get();
        foreach ($users as $user) {
            if (!$user->hasRole('becario') && !$user->hasRole('boss')) {
                $date = Carbon::parse($user->employee->date_admission);
                $antiguedad = Carbon::parse($date)->diffInYears($this->time);
                for ($i = 0; $i <= $antiguedad; $i++) {
                    // Obtener Vacaciones
                    $fecha_inicio = Carbon::parse($user->employee->date_admission)->addYears($i);
                    $fecha_fin = $fecha_inicio->copy()->addYear();
                    // dd([$fecha_inicio, $fecha_fin]);
                    $estado = $this->obtenerEstadoVacaciones($fecha_fin, $fecha_inicio);
                    $periodo_actual =  $i == $antiguedad;
                    $dias_vacaciones = $this->obtenerDiasVacaciones($i, $fecha_fin, $periodo_actual);
                    $dataVacation = Vacations::where('users_id', $user->id)->where('date_end',  $fecha_fin)->first();
                    if ($dataVacation) {
                        $dataVacation->update([
                            'users_id' => $user->id,
                            'date_end' => $fecha_fin,
                            'period' => $estado,
                            'dv' => floor($dias_vacaciones) - $dataVacation->days_enjoyed,
                            'days_enjoyed' => $dataVacation->days_enjoyed,
                            'days_availables' => $dias_vacaciones,
                            'date_start' => $fecha_inicio,
                            'date_end' => $fecha_fin,
                            'cutoff_date' => $fecha_fin->addYear(),
                        ]);
                    } else {
                        Vacations::create([
                            'users_id' => $user->id,
                            'date_end' => $fecha_fin,
                            'period' => $estado,
                            'dv' => 0,
                            'days_enjoyed' => $periodo_actual ? 0 : $dias_vacaciones,
                            'days_availables' => $dias_vacaciones,
                            'date_start' => $fecha_inicio,
                            'date_end' => $fecha_fin,
                            'cutoff_date' => $fecha_fin->addYear(),
                        ]);
                    }
                }
            }
        }
    }

    public function obtenerEstadoVacaciones($fecha_fin, $fecha_inicio)
    {
        $un_anos_despues = $fecha_fin->copy()->addYears(1);
        $fecha_actual = $this->time;

        if ($fecha_actual->between($fecha_inicio, $fecha_fin)) {
            return 1;
        } elseif ($fecha_actual->between($fecha_fin, $un_anos_despues)) {
            return 2;
        } else {
            return 3;
        }
    }

    function obtenerDiasVacaciones($antiguedad, $fecha_fin, $actual = false)
    {
        if ($fecha_fin->isBefore(Carbon::parse('2023-01-01'))) {
            $daysPerYear = [
                6, 6, 8, 10, 12, 14, 14, 14, 14, 14, 16, 16, 16, 16, 16, 18, 18, 18, 18, 18
            ];
            return $daysPerYear[$antiguedad + 1];
        } else {
            $daysPerYearCurrent = VacationPerYear::where('year', $antiguedad + 1)->first();
            if ($actual) {

                $daysItsYear = ($fecha_fin->copy()->subYear())->diffInDays($this->time);
                $daysPerYearCurrent = round(($daysItsYear * $daysPerYearCurrent->days) / 365, 2);
                return $daysPerYearCurrent;
            }
            return $daysPerYearCurrent->days;
        }

        // Lógica para calcular el número de días de vacaciones según la antigüedad del empleado.
        // Devuelve el número de días correspondientes.
    }

    public function obtenerInformacionDeLosUsuarios()
    {
        $str = '';
        foreach (Vacations::all() as $vacation) {
            $str .= "('" .
                $vacation->period . "','" .
                $vacation->days_availables . "','" .
                $vacation->dv . "','" .
                $vacation->days_enjoyed . "','" .
                Carbon::parse($vacation->cutoff_date)->subYears(2)->format('Y-m-d') . "','" .
                Carbon::parse($vacation->cutoff_date)->subYear()->format('Y-m-d') . "','" .
                $vacation->cutoff_date . "','" .
                $vacation->users_id
                . "'),";
        }
        return $str;
    }

    public function sendRemembers()
    {
        $users =  User::where('status', 1)->get();
        $errors = [];
        $usersData = [];
        foreach ($users as $user) {
            if (!$user->hasRole('becario') && !$user->hasRole('boss')) {
                try {
                    $dataVacations = $user->vacationsAvailables()->where('period', '=', 2)->get();
                    if (count($dataVacations) == 1) {
                        $dataVacation = $dataVacations[0];
                        if ($dataVacation->dv > 0) {
                            $dateExpiration = Carbon::parse($dataVacation->date_end)->addYear();
                            $dateNow = $this->time;
                            $diff = $dateNow->diffInDays($dateExpiration);
                            $diffInMonths = $dateNow->diffInMonths($dateExpiration);
                            if ($diff <= 65) {
                                try {
                                    $fecha = $dateExpiration->format('d \d\e ') . $dateExpiration->formatLocalized('%B') . ' de ' . $dateExpiration->format('Y');
                                    $dataUser = [
                                        "id" => $user->id,
                                        "user" => $user->name . ' ' . $user->lastname,
                                        "exp" => $fecha,
                                        "diff" => $diff,
                                        "diffInMonths" => $diffInMonths,
                                        "days" => $dataVacation->dv
                                    ];
                                    array_push($usersData, $dataUser);
                                    $user->notify(new TakeVacationsNotification($dataUser));
                                } catch (Exception $th) {
                                    array_push(
                                        $errors,
                                        [
                                            "id" => $user->id,
                                            "user" => $user->name . ' ' . $user->lastname,
                                            "msg" => $th->getMessage()
                                        ]
                                    );
                                }
                            }
                        }
                    } elseif (count($dataVacations) >= 2) {
                        array_push(
                            $errors,
                            [
                                "id" => $user->id,
                                "user" => $user->name . ' ' . $user->lastname,
                                "msg" => "Este usuario tiene mas de un registro de segundo periodo, revisalo"
                            ]
                        );
                    }
                } catch (Exception $th) {
                    array_push(
                        $errors,
                        [
                            "id" => $user->id,
                            "user" => $user->name . ' ' . $user->lastname,
                            "msg" => $th->getMessage()
                        ]
                    );
                }
            }
        }
        $usersAdmin = User::find(32);
        if ($usersAdmin) {
            $usersAdmin->notify(new InfoRemembersAdmin([$errors, $usersData]));
        }
    }
}
