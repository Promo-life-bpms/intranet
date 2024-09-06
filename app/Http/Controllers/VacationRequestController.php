<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\RequestType;
use App\Models\User;
use App\Models\VacationDays;
use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacationRequestController extends Controller
{

    public function InfoUser()
    {
        $user = auth()->user();
        $dep = auth()->user()->employee->position->department;
        $positions = Position::where("department_id", $dep)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];

        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                if ($emp->user->status == 1) {
                    $roles = DB::table('role_user')->where('user_id', $emp->user->id)->pluck('role_id');
                    if (!$roles->contains(7)) {
                        $users[$emp->user->id] = $emp->user->name . " " . $emp->user->lastname;
                    }
                }
            }
        }

        $vacaciones = DB::table('vacation_requests')->where('user_id', $user->id)->get();
        $solicitudes = [];
        foreach ($vacaciones as $vacacion) {
            $nameResponsable = User::where('id', $vacacion->reveal_id)->first();
            $nameManager = User::where('id', $vacacion->direct_manager_id)->first();
            $typeRequest = RequestType::where('id', $vacacion->request_type_id)->value('type');
            $Days = VacationDays::where('vacation_request_id', $vacacion->id)->get();
            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }

            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $solicitudes[] = [
                'id_request' => $vacacion->id,
                'tipo' => $typeRequest,
                'details' => $vacacion->details,
                'reveal_id' => $nameResponsable->name . ' ' . $nameResponsable->lastname,
                'direct_manager_id' => $nameManager->name . ' ' . $nameManager->lastname,
                'direct_manager_status' => $vacacion->direct_manager_status,
                'rh_status' => $vacacion->rh_status,
                'file' => $vacacion->file == null ? 'No hay justificante' : $vacacion->file,
                'commentary' => $vacacion->commentary == null ? 'No hay un comentario' : $vacacion->commentary,
                'days' => $dias,
            ];
        }

        dd($solicitudes);

        return view('soporte.vacations-collaborators', compact('users', 'solicitudes'));
    }

    public function CreatePurchase(Request $request)
    {
        //dd($request);
        /* $currentDate = Carbon::now();
        $startOfYear = Carbon::create($currentDate->year, 1, 1);
        $endOfYear = Carbon::create($currentDate->year, 12, 31);
        $daysInYear = $endOfYear->diffInDays($startOfYear) + 1;
        return $daysInYear; */

        $user = auth()->user();

        $request->validate([
            'details' => 'required',
            'reveal_id' => 'required',
            'dates' => 'required'
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
            $SegundoPeriodo = $Datos[1]['dv'];
            $totalambosperidos = $PrimerPeriodo + $SegundoPeriodo;
        } elseif (count($Datos) == 1) {
            $totalambosperidos = $Datos[0]['dv'];
        }

        $dates = $request->dates;
        $datesArray = json_decode($dates, true);
        $diasTotales = count($datesArray);


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
            'details' => $request->details,
            'reveal_id' => $request->reveal_id,
            'direct_manager_id' => $jefedirecto,
            'direct_manager_status' => 'Pendiente',
            'rh_status' => 'Pendiente'
        ]);


        foreach ($datesArray as $dia) {
            VacationDays::create([
                'day' => $dia,
                'vacation_request_id' => $Vacaciones->id,
                'status' => 0,
            ]);
        }

        if (count($Datos) > 1) {
            $PrimerPeriodo = $Datos[0]['dv'];
            $Periodo = $Datos[0]['period'];
            $SegundoPeriodo = $Datos[1]['dv'];
            $Periododos = $Datos[1]['period'];

            $totalambosperidos =  $PrimerPeriodo + $SegundoPeriodo;
            if ($diasTotales <= $totalambosperidos) {
                if ($diasTotales >= $PrimerPeriodo) {
                    $faltan = ($PrimerPeriodo - $diasTotales) * (-1);
                    $restadedv = $diasTotales - $faltan;
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                        'waiting' => $restadedv
                    ]);

                    if ($faltan > 0) {
                        if ($SegundoPeriodo > 0) {
                            DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                'waiting' => $faltan
                            ]);
                        }
                    }
                }
                if ($diasTotales <= $PrimerPeriodo) {
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                        'waiting' => $diasTotales
                    ]);
                }
            } else {
                dd('No te alcanza precioso');
            }
        } elseif (count($Datos) == 1) {
            $totalunsoloperido = $Datos[0]['dv'];
            $Periodo = $Datos[0]['period'];
            if ($diasTotales <= $totalunsoloperido) {
                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                    'waiting' => $diasTotales,
                ]);
            } else {
                return 0;
            }
        }

        return back()->with('message', 'Se creo tu solicitud de vacaciones.');
    }


    ////////////CONFIRMAR VACACIONES///////////////////
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

        $soliVaca = 11;
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
                'cutoff_date' => $vaca->cutoff_date,
                'period' => $vaca->period,
                'days_enjoyed' => $vaca->days_enjoyed,
            ];
        }

        if (count($Datos) > 1) {
            $PrimerPeriodo = $Datos[0]['Vacaciones'];
            $Periodo = $Datos[0]['period'];
            $SegundoPeriodo = $Datos[1]['Vacaciones'];
            $Periododos = $Datos[1]['period'];

            $totalambosperidos = $Datos[0]['Vacaciones'] + $Datos[1]['Vacaciones'];
            if ($soliVaca <= $totalambosperidos) {
                if ($soliVaca >= $PrimerPeriodo) {
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
                    return 'YA SE CREARON TUS VACACIONES';
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

                    return 'CUANDO YA NO TIENES PERIODO UNO';
                }
            } else {
                return 0;
            }
        } elseif (count($Datos) == 1) {
            $totalunsoloperido = $Datos[0]['Vacaciones'];
            $Periodo = $Datos[0]['period'];
            if ($soliVaca <= $totalunsoloperido) {
                $newdv =  $totalunsoloperido - $soliVaca;
                $disfrutadas = ($Datos[0]['days_enjoyed']) + $soliVaca;
                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                    'dv' => $newdv,
                    'days_enjoyed' => $disfrutadas,
                ]);
            } else {
                return 0;
            }
        }
    }
}
