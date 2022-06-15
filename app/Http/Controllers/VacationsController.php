<?php

namespace App\Http\Controllers;

use App\Exports\VacationsExport;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use App\Models\VacationPerYear;
use App\Models\Vacations;
use Carbon\Carbon;
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
        $users = User::all();
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

    public function updateVacations()
    {
        // Obtener a todos los usuarios
        $users = User::all();
        // Calcular, en base a su fecha de ingreso, los dias disponibles del periodo actual y del anterior
        foreach ($users as $user) {
            $date = Carbon::parse($user->employee->date_admission);
            $now = $this->time;
            // Años laborando
            $yearsWork = $date->diffInYears($now);

            // Dias laborando en el transcurso de este año
            $daysItsYear = $date->diffInDays($this->time);
            if ($yearsWork <= 0) {
                // Calcular los dias en base a su primer año
                $daysPerYearCurrent = VacationPerYear::find(1);
                $diasDispobibles = round(($daysItsYear * $daysPerYearCurrent->days) / 365, 2);
                $dataVacations =  $user->vacationsAvailables()->where('period', 1)->first();
                if ($dataVacations) {
                    $dataVacations->days_availables =  $diasDispobibles;
                    $dataVacations->dv =  floor($diasDispobibles) - $dataVacations->days_enjoyed;
                    $dataVacations->cutoff_date = $date->addYears(2);
                    $dataVacations->save();
                } else {
                    $user->vacationsAvailables()->firstOrCreate([
                        'period' => 1,
                        'cutoff_date' => $date->addYears(2),
                        'days_availables' => $diasDispobibles,
                        'dv' => floor($diasDispobibles),
                    ]);
                }
            } else if ($yearsWork > 0) {
                // Revisar los dias que le tocan por año y sumar los que no han expirado, obtener el periodo actual
                $daysPerYearCurrent = VacationPerYear::where('year', $yearsWork + 1)->first();
                $lastPeriodYear = (string)((int)$date->format('Y') + $yearsWork);
                $lastPeriodCurrent = Carbon::parse($lastPeriodYear . '-' . (string) $date->format('m-d'));
                $daysItsYear = $lastPeriodCurrent->diffInDays($this->time);
                $diasDispobibles = round(($daysItsYear * $daysPerYearCurrent->days) / 365, 2);
                $dataVacations =  $user->vacationsAvailables()->where('period', 1)->first();
                if ($dataVacations) {
                    $dataVacations->days_availables =  $diasDispobibles;
                    $dataVacations->dv =  floor($diasDispobibles) - $dataVacations->days_enjoyed;
                    $dataVacations->cutoff_date =  $lastPeriodCurrent->addYears(2);
                    $dataVacations->save();
                } else {
                    $user->vacationsAvailables()->firstOrCreate([
                        'period' => 1,
                        'cutoff_date' => $lastPeriodCurrent->addYears(2),
                        'days_availables' => $diasDispobibles,
                        'dv' => floor($diasDispobibles),
                    ]);
                }
                if ($yearsWork >= 1) {
                    $daysPerYearCurrent = VacationPerYear::where('year', (int)$yearsWork)->first();
                    $diasDispobibles = $daysPerYearCurrent->days;
                    $lastPeriodYear = (string)((int)$date->format('Y') + $yearsWork - 1);
                    $lastPeriod = Carbon::parse($lastPeriodYear . '-' . (string) $date->format('m-d'));
                    $dataVacations =  $user->vacationsAvailables()->where('period', 2)->first();
                    if ($dataVacations) {
                        $dataVacations->days_availables =  $diasDispobibles;
                        $dataVacations->cutoff_date =  $lastPeriod->addYears(2);
                        $dataVacations->dv =  floor($diasDispobibles) - $dataVacations->days_enjoyed;
                        $dataVacations->save();
                    } else {
                        $user->vacationsAvailables()->firstOrCreate([
                            'period' => 2,
                            'cutoff_date' => $lastPeriod->addYears(2),
                            'days_availables' => $diasDispobibles,
                            'dv' => floor($diasDispobibles),
                        ]);
                    }
                }
            }
            echo '<br>';
        }
    }
    public function updateExpiration()
    {
        $users = User::all();
        // Calcular, en base a su fecha de ingreso, los dias disponibles del periodo actual y del anterior
        foreach ($users as $user) {
            $dataVacations = $user->vacationsAvailables()->where('period', '<>', 3)->get();
            foreach ($dataVacations as $vacation) {
                $cutoff_date = Carbon::parse($vacation->cutoff_date);
                $daysDiference = $cutoff_date->diffInDays($this->time, false);
                if ($daysDiference > 0 && $vacation->period == "2") {
                    $vacation->period = '3';
                    $vacation->save();
                } else if ($daysDiference > -365 && $vacation->period == "1") {
                    $vacation->period = '2';
                    $vacation->save();
                } else {
                    print_r($daysDiference);
                    echo 'Sin cambios';
                }
                echo '<br>';
            }
            echo '<br>';
        }
        $this->updateVacations();
    }
}
