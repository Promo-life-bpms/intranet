<?php

namespace App\Http\Controllers;

use App\Models\VacationDays;
use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacationRequestController extends Controller
{
    public function CreatePurchase(Request $request)
    {
        /* $currentDate = Carbon::now();
        $startOfYear = Carbon::create($currentDate->year, 1, 1);
        $endOfYear = Carbon::create($currentDate->year, 12, 31);
        $daysInYear = $endOfYear->diffInDays($startOfYear) + 1;
        return $daysInYear; */

        $user = auth()->user();

        $request->validate([
            'details' => 'required',
            'reveal_id' => 'required',
            'dates' => 'required|array|min:1'
        ]);

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
        $jefedirecto = $Ingreso->jefe_directo_id;
        $fechaIngreso = Carbon::parse($Ingreso->date_admission);
        $fechaActual = Carbon::now();
        $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

        if ($mesesTranscurridos < 6) {
            return back()->with('message', 'No has cumplido el tiempo suficiente para solicitar vacaciones.');
        }

        $Vacaciones = DB::table('vacations_availables')
            ->where('users_id', $user->id)
            ->where('cutoff_date', '>=', $fechaActual)
            ->orderBy('cutoff_date', 'asc')
            ->get();

        $Datos = [];
        foreach ($Vacaciones as $vaca) {
            $Datos[] = [
                'dv' => $vaca->dv,
                'cutoff_date' => $vaca->cutoff_date,
                'period' => $vaca->period,
                'days_enjoyed' => $vaca->days_enjoyed,
            ];
        }

        if (count($Datos) > 1) {
            $PrimerPeriodo = $Datos[0]['dv'];
            $Periodo = $Datos[0]['period'];
            $SegundoPeriodo = $Datos[1]['dv'];
            $Periododos = $Datos[1]['period'];
            $totalambosperidos = $Datos[0]['dv'] + $Datos[1]['dv'];
        } elseif (count($Datos) == 1) {
            $totalambosperidos = $Datos[0]['dv'];
        }

        $dates = [
            '2024-08-24',
            '2024-08-25',
            '2024-08-26',
            '2024-08-27'
        ];

        $diasTotales = count($dates);
        //return $diasTotales;

        if ($diasTotales > $totalambosperidos) {
            return back()->with('message', 'No cuentas con los días solicitados.');
        }

        $path = '';
        if ($request->hasFile('archivos')) {
            $filenameWithExt = $request->file('archivos')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('archivos')->clientExtension();
            $fileNameToStore = time() . $filename . '.' . $extension;
            $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
        }

        $Vacaciones = VacationRequest::create([
            'user_id' => $user->id,
            'request_type_id' => 1,
            'file' => $path,
            'details' => 'Esto es una prueba',
            'reveal_id' => 159,
            'direct_manager_id' => $jefedirecto,
            'direct_manager_status' => 'Pendiente',
            'rh_status' => 'Pendiente'
        ]);

        
        foreach ($dates as $dia) {
            VacationDays::create([
                'day' => $dia,
                'vacation_request_id' => $Vacaciones->id,
                'status' => 0,
            ]);
        }

        return back()->with('message', 'Se creo tu solicitud de vacaciones.');


    }

    public function CreateVacationRequest(Request $request)
    {
        $user = auth()->user();

        /* $this->validate($request, [
            'details' => 'required',
            'reveal_id' => 'required'

        ]); */

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $soliVaca = 1;
        /////OBTENEMOS LAS VACACIONES QUE NO SE ENCUENTREN EN PERIODOS CADUCADOS//////
        $hoy = Carbon::now()->format('Y-m-d');
        $Vacaciones = DB::table('vacations_availables')
            ->where('users_id', $user->id)
            ->where('cutoff_date', '>=', $hoy)
            ->orderBy('cutoff_date', 'asc')
            ->get();

        $Datos = [];
        foreach ($Vacaciones as $vaca) {
            $Datos[] = [
                'Vacaciones' => $vaca->dv,
                'Fecha de caducidad' => $vaca->cutoff_date,
                'period' => $vaca->period,
                'days_enjoyed' => $vaca->days_enjoyed,
            ];
        }

        $PrimerPeriodo = $Datos[0]['Vacaciones'];
        $Periodo = $Datos[0]['period'];
        $SegundoPeriodo = $Datos[1]['Vacaciones'];
        $Periododos = $Datos[1]['period'];
        $totalambosperidos = $Datos[0]['Vacaciones'] + $Datos[1]['Vacaciones'];
        if ($soliVaca <= $totalambosperidos) {
            if ($PrimerPeriodo > 0) {
                if ($soliVaca > $PrimerPeriodo) {
                    $diasFaltan = ($PrimerPeriodo - $soliVaca) * (-1);
                    $restadedv = $soliVaca - $diasFaltan;

                    if ($PrimerPeriodo == $restadedv) {
                        $disfrutadas = ($Datos[0]['days_enjoyed']) + $restadedv;
                        DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                            'dv' => 0,
                            'days_enjoyed' => $disfrutadas,
                        ]);
                    }
                    if ($diasFaltan > 0) {
                        if ($SegundoPeriodo > 0) {
                            $nuevodv = $SegundoPeriodo - $diasFaltan;
                            $disfrutadasdos = ($Datos[1]['days_enjoyed']) + $diasFaltan;

                            DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                'dv' => $nuevodv,
                                'days_enjoyed' => $disfrutadasdos,
                            ]);
                        }
                    }
                }

                if ($soliVaca <= $PrimerPeriodo) {
                    $vacasoli = $Datos[0]['Vacaciones'] - $soliVaca;
                    $disfrutadas = ($Datos[0]['days_enjoyed']) + $soliVaca;
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                        'dv' => $vacasoli,
                        'days_enjoyed' => $disfrutadas,
                    ]);
                }
            }
            if ($SegundoPeriodo > 0) {
                if ($soliVaca <= $SegundoPeriodo) {
                    $vacasoli = $Datos[1]['Vacaciones'] - $soliVaca;
                    $disfrutadas = ($Datos[1]['days_enjoyed']) + $soliVaca;
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                        'dv' => $vacasoli,
                        'days_enjoyed' => $disfrutadas,
                    ]);
                }
            }

            /*  if ($SegundoPeriodo <= $soliVaca) {
                $disfrutadasdos = ($Datos[1]['days_enjoyed']) + $diasFaltan;

                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                    'dv' => $nuevodv,
                    'days_enjoyed' => $disfrutadasdos,
                ]);
            } */
        } else {
            return 0;
        }
    }
}
