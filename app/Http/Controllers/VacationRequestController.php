<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Vacations\Vacations;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MakeUpVacations;
use App\Models\Position;
use App\Models\RequestType;
use App\Models\User;
use App\Models\VacationDays;
use App\Models\VacationInformation;
use App\Models\VacationPerYear;
use App\Models\VacationRequest;
use App\Models\Vacations as ModelsVacations;
use App\Models\VacationsAvailablePerUser;
use App\Notifications\ApprovalNoticeByDirectBoss;
use App\Notifications\AuthorizeRequest;
use App\Notifications\AuthorizeRequestByRH;
use App\Notifications\PermissionRequest;
use App\Notifications\PermissionRequestUpdate;
use App\Notifications\RejectRequest;
use App\Notifications\RejectRequestBoss;
use Carbon\Carbon;
use Doctrine\DBAL\Schema\Index;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

use PhpParser\Node\Expr\FuncCall;

use function PHPUnit\Framework\isEmpty;

class VacationRequestController extends Controller
{


    public function index(Request $request)
    {

        $user = auth()->user();
        $dep = auth()->user()->employee->position->department;
        $positions = Position::where("department_id", $dep)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];
        $vacaciones_entrantes = null;
        $fecha_expiracion_entrante = null;

        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                if ($emp->user->status == 1) {
                    $roles = DB::table('role_user')->where('user_id', $emp->user->id)->pluck('role_id');
                    if (!$roles->contains(7)) {
                        $users[$emp->user->id] = //Guardar el user name , lastname y el id
                            [
                                'name' => $emp->user->name . " " . $emp->user->lastname,
                                'id' => $emp->user->id
                            ];
                    }
                }
            }
        }

        $vacaciones = DB::table('vacation_requests')->where('user_id', $user->id)->orderBy('created_at', 'desc');

        if ($request->has('jefeDirecto') && $request->jefeDirecto != '') {
            $vacaciones->where('direct_manager_status', $request->jefeDirecto);
        }

        if ($request->has('rhStatus') && $request->rhStatus != '') {
            $vacaciones->where('rh_status', $request->rhStatus);
        }

        $solicitudesCollection = $vacaciones->get()->map(function ($vacacion) {
            $nameResponsable = User::where('id', $vacacion->reveal_id)->first();
            $nameManager = User::where('id', $vacacion->direct_manager_id)->first();
            $typeRequest = RequestType::where('id', $vacacion->request_type_id)->value('type');
            $Days = VacationDays::where('vacation_request_id', $vacacion->id)->get();
            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }
            // Crear un objeto en lugar de un array
            $solicitud = new \stdClass();
            $solicitud->id_request = $vacacion->id;
            $solicitud->tipo = $typeRequest;
            $solicitud->details = $vacacion->details;
            $solicitud->id_reveal = $nameResponsable->id;
            $solicitud->reveal_id = $nameResponsable->name . ' ' . $nameResponsable->lastname;
            $solicitud->direct_manager_id = $nameManager->name . ' ' . $nameManager->lastname;
            $solicitud->direct_manager_status = $vacacion->direct_manager_status;
            $solicitud->rh_status = $vacacion->rh_status;
            $solicitud->request_type_id = $vacacion->request_type_id;
            $solicitud->file = $vacacion->file == null ? 'No hay justificante' : $vacacion->file;
            $solicitud->commentary = $vacacion->commentary == null ? 'No hay un comentario' : $vacacion->commentary;
            $solicitud->days = $dias;
            $solicitud->time = in_array($vacacion->request_type_id, [2]) ? $time : null;
            $solicitud->more_information = $vacacion->more_information == null ? null : json_decode($vacacion->more_information, true);
            return $solicitud;
        });


        // Si hay un filtro de tipo, se aplica sobre la colección mapeada
        if ($request->has('tipo') && $request->tipo != '') {
            $solicitudesCollection = $solicitudesCollection->filter(function ($solicitud) use ($request) {
                return $solicitud->tipo == $request->tipo;
            });
        }
        //Si hay un filtro de fecha, se aplica sobre la colección mapeada
        if ($request->has('fecha') && $request->fecha != '') {
            $solicitudesCollection = $solicitudesCollection->filter(function ($solicitud) use ($request) {
                return in_array($request->fecha, $solicitud->days);
            });
        }

        $page = $request->get('page', 1);
        $perPage = 5;
        $solicitudes = new LengthAwarePaginator(
            $solicitudesCollection->forPage($page, $perPage),
            $solicitudesCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
        $fechaIngreso = Carbon::parse($Ingreso->date_admission);
        $fechaActual = Carbon::now();

        $NewUser = DB::table('vacations_available_per_users')->where('users_id', $user->id)->exists();
        if (!$NewUser) {
            $date_end = Carbon::parse($fechaIngreso)->addYear()->format('Y-m-d');
            $date_cutoff_date = Carbon::parse($date_end)->addYear()->format('Y-m-d');
            VacationsAvailablePerUser::create([
                'period' => 1,
                'days_availables' => 0,
                'dv' => 0,
                'days_enjoyed' => 0,
                'date_start' => $fechaIngreso,
                'date_end' => $date_end,
                'cutoff_date' => $date_cutoff_date,
                'anniversary_number' => 1,
                'waiting' => 0,
                'users_id' => $user->id,
            ]);
        }

        $Primerperiodo = DB::table('vacations_available_per_users')->where('users_id', $user->id)
            ->where('period', 1)->first();
        $PeriodOne = $Primerperiodo->date_end;
        if ($PeriodOne && $fechaActual->greaterThanOrEqualTo(Carbon::parse($PeriodOne))) {
            $date_end = $Primerperiodo->cutoff_date;
            $fechaCaducidad = Carbon::parse($Primerperiodo->cutoff_date)->addYear()->format('Y-m-d');
            $Aniversario = $Primerperiodo->anniversary_number + 1;
            $DiasPorAñoCumplido = VacationPerYear::where('year',  $Primerperiodo->anniversary_number)->value('days');

            $Segundoperiodo = DB::table('vacations_available_per_users')->where('users_id', $user->id)
                ->where('period', 2)->exists();

            if ($Segundoperiodo) {
                DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', 2)->update([
                    'period' => 3,
                ]);
            }

            DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', 1)->update([
                'period' => 2,
                'days_availables' =>  $DiasPorAñoCumplido,
            ]);

            VacationsAvailablePerUser::create([
                'period' => 1,
                'days_availables' => 0,
                'dv' => 0,
                'days_enjoyed' => 0,
                'date_start' => $PeriodOne,
                'date_end' => $date_end,
                'cutoff_date' => $fechaCaducidad,
                'anniversary_number' => $Aniversario,
                'waiting' => 0,
                'users_id' => $user->id,
            ]);
        }

        $Vacaciones = DB::table('vacations_available_per_users')
            ->where('users_id', $user->id)
            ->where('cutoff_date', '>=', $fechaActual)
            ->orderBy('cutoff_date', 'asc')
            ->get();
        $Datos = [];
        foreach ($Vacaciones as $vaca) {
            $Datos[] = [
                'id' => $vaca->id,
                'dv' => $vaca->dv,
                'cutoff_date' => $vaca->cutoff_date,
                'period' => $vaca->period,
                'days_enjoyed' => $vaca->days_enjoyed,
                'waiting' => $vaca->waiting,
                'days_enjoyed' => $vaca->days_enjoyed,
                'days_availables' => $vaca->days_availables,
                'date_start' => $vaca->date_start,
                'date_end' => $vaca->date_end,
                'anniversary_number' => $vaca->anniversary_number,
            ];
        }

        if (count($Datos) > 1) {
            $fechaActual = Carbon::now();
            $fechaInicio = Carbon::parse($Datos[1]['date_start']);
            $fechaFin = Carbon::parse($Datos[1]['date_end']);
            $DiasPorAñoCumplido = VacationPerYear::where('year',  $Datos[1]['anniversary_number'])->value('days');
            $diasTranscurridos = $fechaInicio->diffInDays($fechaActual);
            $diasaño = $fechaInicio->diffInDays($fechaFin);
            $calculoVacaciones = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido), 2);
            $calculoVacacionesRedondeado = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido));
            $dv = $Datos[1]['dv'];
            $days_enjoyed = $Datos[1]['days_enjoyed'];
            $WaitingOne = $Datos[1]['waiting'];
            $dvNew = $calculoVacacionesRedondeado - $days_enjoyed - $WaitingOne;
            if ($calculoVacaciones <= $DiasPorAñoCumplido) {
                DB::table('vacations_available_per_users')->where('id', $Datos[1]['id'])->update([
                    'days_availables' =>  $calculoVacaciones,
                    'dv' => $dvNew
                ]);
            } else {
                DB::table('vacations_available_per_users')->where('id', $Datos[1]['id'])->update([
                    'days_availables' =>  $DiasPorAñoCumplido,
                    'dv' => $dvNew
                ]);
            }
        }

        if (count($Datos) == 1) {
            $fechaActual = Carbon::now();
            $fechaInicio = Carbon::parse($Datos[0]['date_start']);
            $fechaFin = Carbon::parse($Datos[0]['date_end']);
            $DiasPorAñoCumplido = VacationPerYear::where('year',  $Datos[0]['anniversary_number'])->value('days');
            $diasTranscurridos = $fechaInicio->diffInDays($fechaActual);
            $diasaño = $fechaInicio->diffInDays($fechaFin);
            $calculoVacaciones = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido), 2);
            $calculoVacacionesRedondeado = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido));
            $dv = $Datos[0]['dv'];
            $days_enjoyed = $Datos[0]['days_enjoyed'];
            $WaitingOne = $Datos[0]['waiting'];
            $dvNew = $calculoVacacionesRedondeado - $days_enjoyed - $WaitingOne;
            if ($calculoVacaciones <= $DiasPorAñoCumplido) {
                DB::table('vacations_available_per_users')->where('id', $Datos[0]['id'])->update([
                    'days_availables' =>  $calculoVacaciones,
                    'dv' => $dvNew
                ]);
            } else {
                DB::table('vacations_available_per_users')->where('id', $Datos[0]['id'])->update([
                    'days_availables' =>  $DiasPorAñoCumplido,
                    'dv' => $dvNew
                ]);
            }
        }

        if (count($Datos) > 1) {
            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $user->id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();
            $DatosNew = [];
            foreach ($Vacaciones as $vaca) {
                $DatosNew[] = [
                    'id' => $vaca->id,
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                    'period' => $vaca->period,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'date_start' => $vaca->date_start,
                    'date_end' => $vaca->date_end,
                    'anniversary_number' => $vaca->anniversary_number,
                ];
            }
            $diasreservados = $DatosNew[0]['waiting'] + $DatosNew[1]['waiting'];
            $diasdisponibles = $DatosNew[0]['dv'] + $DatosNew[1]['dv'];
            $totalvacaciones = $DatosNew[0]['days_availables'] + $DatosNew[1]['days_availables'];
            $totalvacaionestomadas = $DatosNew[0]['days_enjoyed'] + $DatosNew[1]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
            $porcentajetomadas = round($porcentajetomadas);
            $fecha_expiracion_actual = $DatosNew[0]['cutoff_date'];
            $vacaciones_actuales = $DatosNew[0]['dv'];
            $fecha_expiracion_entrante = $DatosNew[1]['cutoff_date'];
            $vacaciones_entrantes = $DatosNew[1]['dv'];
        } elseif (count($Datos) == 1) {
            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $user->id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();
            $DatosNew = [];
            foreach ($Vacaciones as $vaca) {
                $DatosNew[] = [
                    'id' => $vaca->id,
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                    'period' => $vaca->period,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'date_start' => $vaca->date_start,
                    'date_end' => $vaca->date_end,
                    'anniversary_number' => $vaca->anniversary_number,
                ];
            }
            $diasreservados = $DatosNew[0]['waiting'];
            $diasdisponibles = $DatosNew[0]['dv'];
            $totalvacaciones = $DatosNew[0]['days_availables'];
            $totalvacaionestomadas = $DatosNew[0]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
            $porcentajetomadas = round($porcentajetomadas, 2);
            $fecha_expiracion_actual = $DatosNew[0]['cutoff_date'];
            $vacaciones_actuales = $DatosNew[0]['dv'];
        }

        $vacacionesDias = [];
        $ausenciaDias = [];
        $paternidadDias = [];
        $incapacidadDias = [];
        $permisosEspecialesDias = [];
        foreach ($solicitudes as $daysonthecalendar) {
            // Obtener los días asociados a la solicitud
            $Days = VacationDays::where('vacation_request_id', $daysonthecalendar->id_request)->get();
            $dias = [];

            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }

            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            if ($daysonthecalendar->request_type_id == 1) {
                $vacacionesDias = array_merge($vacacionesDias, $dias);
            } elseif ($daysonthecalendar->request_type_id == 2) {
                $ausenciaDias = array_merge($ausenciaDias, $dias);
            } elseif ($daysonthecalendar->request_type_id == 3) {
                $paternidadDias = array_merge($paternidadDias, $dias);
            } elseif ($daysonthecalendar->request_type_id == 4) {
                $incapacidadDias = array_merge($incapacidadDias, $dias);
            } elseif ($daysonthecalendar->request_type_id == 5) {
                $permisosEspecialesDias = array_merge($permisosEspecialesDias, $dias);
            }
        }

        // Crear el arreglo final
        $vacacionescalendar = [
            'vacaciones' => $vacacionesDias,
            'ausencias' => $ausenciaDias,
            'paternidad' => $paternidadDias,
            'incapacidad' => $incapacidadDias,
            'permisos_especiales' => $permisosEspecialesDias,

        ];

        //PERMISOS ESPECIALES//
        $currentYear = date('Y');
        $AsuntosPersonales = DB::table('vacation_requests')
            ->where('user_id', $user->id)
            ->where('request_type_id', 5)
            ->where('direct_manager_status', 'Aprobada')
            ->where('rh_status', 'Aprobada')
            ->whereBetween('created_at', ["$currentYear-01-01 00:00:00", "$currentYear-12-31 23:59:59"])
            ->get();

        $contadorAsuntosPersonales = 0;
        foreach ($AsuntosPersonales as $asuntoPersonal) {
            $moreInformation = json_decode($asuntoPersonal->more_information, true);
            if (!empty($moreInformation) && isset($moreInformation[0]['Tipo_de_permiso_especial']) && $moreInformation[0]['Tipo_de_permiso_especial'] === 'Asuntos personales') {
                $contadorAsuntosPersonales++;
            }
        }

        $porcentajeespecial = round(($contadorAsuntosPersonales / 3) * 100);

        return view('request.vacations-collaborators', compact('users', 'vacaciones', 'solicitudes', 'diasreservados', 'diasdisponibles', 'totalvacaciones', 'totalvacaionestomadas', 'porcentajetomadas', 'fecha_expiracion_actual', 'vacaciones_actuales', 'fecha_expiracion_entrante', 'vacaciones_entrantes', 'vacacionescalendar', 'porcentajeespecial'));
    }

    public function CreatePurchase(Request $request)
    {
        $user = auth()->user();

        if ($request->reveal_id == $user->id) {
            return back()->with('error', 'No puedes ser tú mismo el responsable de tus deberes.');
        }

        $dates = $request->dates;
        $datesArray = json_decode($dates, true);

        $UserEmployee = Employee::where('user_id', $user->id)->value('user_id');
        $company = DB::table('company_employee')->where('employee_id', $UserEmployee)->pluck('company_id');
        if ($company->contains(2)) {
            $DaysJudios = [
                '03-10-2024',
                '04-10-2024',
                '17-10-2024',
                '18-10-2024',
                '24-10-2024',
            ];

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($DaysJudios as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Algunos de los días seleccionados no están disponibles para tu solicitud.');
            }
        }

        $DaysFeridos = [
            '18-11-2024',
            '25-12-2024',
            '01-01-2025',
            '03-02-2025',
            '17-10-2025',
            '01-05-2025',
            '16-09-2025',
            '17-09-2025',
            '25-09-2025'
        ];

        $diasParecidosFestivos = [];
        foreach ($datesArray as $date) {
            foreach ($DaysFeridos as $Feriados) {
                if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($Feriados))) {
                    $diasParecidosFestivos[] = $Feriados;
                }
            }
        }

        if (!empty($diasParecidosFestivos)) {
            return back()->with('error', 'Algunos de los días seleccionados son feriados, por lo tanto no puedes solicitarlos.');
        }


        ///VACACIONES
        if ($request->request_type_id == 1) {
            $request->validate([
                'details' => 'required',
                'reveal_id' => 'required',
                'dates' => 'required'
            ]);

            if (auth()->user()->employee->jefe_directo_id == null) {
                return back()->with('error', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

            if ($mesesTranscurridos < 6) {
                return back()->with('error', 'No has cumplido el tiempo suficiente para solicitar vacaciones.');
            }

            $Vacaciones = DB::table('vacations_available_per_users')
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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'id' => $vaca->id
                ];
            }


            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $diasTotales = count($datesArray);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            if ($diasTotales == 0) {
                return back()->with('error', 'Debes enviar al menos un día de vacaciones.');
            }

            $path = '';
            if ($request->hasFile('archivos')) {
                $filenameWithExt = $request->file('archivos')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivos')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
            }

            if (count($Datos) > 1) {
                // Extracción de datos de los dos periodos
                $VacacionesOne = $Datos[0]['dv'];
                $VacacionesTwo = $Datos[1]['dv'];
                $PeriodoOne = $Datos[0]['period'];
                $PeriodoTwo = $Datos[1]['period'];
                $WaitingOne = $Datos[0]['waiting'];
                $WaitingTwo = $Datos[1]['waiting'];
                $idOne = $Datos[0]['id'];
                $idTwo = $Datos[1]['id'];


                //Vacaciones en el primer periodo de vacaciones
                if ($diasTotales <= $VacacionesOne) {
                    $RestaDv = $VacacionesOne - $diasTotales;
                    ////ReservaWaiting nos va ayudar para cuando nieguen las vacaciones, las regresemos al periodo actual///
                    $ReservaWaiting = $WaitingOne + $diasTotales;
                    DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoOne)->update([
                        'waiting' => $ReservaWaiting,
                        'dv' => $RestaDv
                    ]);

                    $Vacaciones = VacationRequest::create([
                        'user_id' => $user->id,
                        'request_type_id' => 1,
                        'file' => $path,
                        'details' => $request->details,
                        'reveal_id' => $request->reveal_id,
                        'direct_manager_id' => $jefedirecto,
                        'direct_manager_status' => 'Pendiente',
                        'rh_status' => 'Pendiente',
                        'more_information' => 'No hay más información'
                    ]);

                    foreach ($datesArray as $dia) {
                        VacationDays::create([
                            'day' => $dia,
                            'vacation_request_id' => $Vacaciones->id,
                            'status' => 0,
                        ]);
                    }

                    VacationInformation::create([
                        'total_days' => $diasTotales,
                        'id_vacations_availables' => $idOne,
                        'id_vacation_request' => $Vacaciones->id

                    ]);


                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);

                        $boss->notify(new PermissionRequest(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Vacaciones creadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Vacaciones creadas exitosamente.');
                }

                $totalVacaciones = $VacacionesOne + $VacacionesTwo;
                //LAS VACACIONES ESTAN EN AMBOS PERIODOS DE VACACIONES//
                if ($diasTotales > $VacacionesOne) {
                    if ($diasTotales <= $totalVacaciones && $totalVacaciones != 0) {
                        ////VERIFICAMOS QUE EN EL PRIMER PERIODO HAYA VACACIONES
                        if ($VacacionesOne > 0) {
                            $FaltanVacacionesOne = $diasTotales - $VacacionesOne;
                            $DispoVacaOne = $diasTotales - $FaltanVacacionesOne;
                            if ($FaltanVacacionesOne <= $VacacionesTwo && $DispoVacaOne == $VacacionesOne) {
                                $ReservaWaitingOne = $WaitingOne + $DispoVacaOne;
                                $RestaDvOne = $VacacionesOne - $DispoVacaOne;
                                $ReservaWaitingTwo = $WaitingTwo + $FaltanVacacionesOne;
                                $RestaDvTwo = $VacacionesTwo - $FaltanVacacionesOne;

                                DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoOne)->update([
                                    'waiting' => $ReservaWaitingOne,
                                    'dv' => $RestaDvOne
                                ]);

                                DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoTwo)->update([
                                    'waiting' => $ReservaWaitingTwo,
                                    'dv' => $RestaDvTwo
                                ]);

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

                                VacationInformation::create([
                                    'total_days' => $DispoVacaOne,
                                    'id_vacations_availables' => $idOne,
                                    'id_vacation_request' => $Vacaciones->id

                                ]);
                                VacationInformation::create([
                                    'total_days' => $FaltanVacacionesOne,
                                    'id_vacations_availables' => $idTwo,
                                    'id_vacation_request' => $Vacaciones->id

                                ]);

                                try {
                                    $emisor = User::find($user->id);
                                    $requestType = RequestType::find($request->request_type_id);
                                    $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                                        ->pluck('day')
                                        ->implode(', ');
                                    $boss = User::find($Ingreso->jefe_directo_id);

                                    $boss->notify(new PermissionRequest(
                                        $boss->name,
                                        $emisor->name,
                                        $requestType->type,
                                        $days,
                                        $request->details,
                                    ));
                                } catch (\Exception $e) {
                                    return back()->with('warning', 'Vacaciones creadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                }
                                return back()->with('message', 'Vacaciones creadas exitosamente.');
                            } else {
                                return back()->with('error', 'No cuentas con las vacaciones suficientes. 1');
                            }
                        }

                        if ($VacacionesOne == 0 && $VacacionesTwo > 0 && $VacacionesTwo != 0) {
                            $RestadvTwo = $VacacionesTwo - $diasTotales;
                            $ReservaWaitingTwo = $WaitingTwo + $diasTotales;
                            DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoTwo)->update([
                                'waiting' => $ReservaWaitingTwo,
                                'dv' => $RestadvTwo
                            ]);

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
                            VacationInformation::create([
                                'total_days' => $diasTotales,
                                'id_vacations_availables' => $idTwo,
                                'id_vacation_request' => $Vacaciones->id
                            ]);

                            try {
                                $emisor = User::find($user->id);
                                $requestType = RequestType::find($request->request_type_id);
                                $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                                    ->pluck('day')
                                    ->implode(', ');
                                $boss = User::find($Ingreso->jefe_directo_id);

                                $boss->notify(new PermissionRequest(
                                    $boss->name,
                                    $emisor->name,
                                    $requestType->type,
                                    $days,
                                    $request->details,
                                ));
                            } catch (\Exception $e) {
                                return back()->with('warning', 'Vacaciones creadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                            }
                            return back()->with('message', 'Vacaciones creadas exitosamente.');
                        } else {
                            return back()->with('error', 'No cuentas con las vacaciones suficientes. 2');
                        }
                    } else {
                        return back()->with('error', 'No cuentas con las vacaciones suficientes.');
                    }
                }
            }

            if (count($Datos) == 1) {
                $VacacionesOne = $Datos[0]['dv'];
                $PeriodoOne = $Datos[0]['period'];
                $WaitingOne = $Datos[0]['waiting'];
                $idOne = $Datos[0]['id'];

                if ($diasTotales > $VacacionesOne) {
                    return back()->with('error', 'No cuentas con los días solicitados.');
                }

                if ($diasTotales <= $VacacionesOne) {
                    $RestaDv = $VacacionesOne - $diasTotales;
                    ////ReservaWaiting nos va ayudar para cuando nieguen las vacaciones, las regresemos al periodo actual///
                    $ReservaWaiting = $WaitingOne + $diasTotales;
                    DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoOne)->update([
                        'waiting' => $ReservaWaiting,
                        'dv' => $RestaDv
                    ]);

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

                    VacationInformation::create([
                        'total_days' => $diasTotales,
                        'id_vacations_availables' => $idOne,
                        'id_vacation_request' => $Vacaciones->id

                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);

                        $boss->notify(new PermissionRequest(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Vacaciones creadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Vacaciones creadas exitosamente.');
                }
            }
        }

        ///AUSENCIAS
        if ($request->request_type_id == 2) {

            $request->validate([
                'details' => 'required',
                'reveal_id' => 'required',
                'dates' => 'required',
            ]);

            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $dias = count($datesArray);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;

            $path = '';
            if ($request->hasFile('archivos')) {
                $filenameWithExt = $request->file('archivos')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivos')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
            }

            if ($dias == 0) {
                return back()->with('error', 'Debes ingresar el día en que saldrás temprano de la jornada.');
            }

            if ($request->ausenciaTipo == 'retardo') {
                if ($dias > 1) {
                    return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
                }

                $hora12PM = Carbon::today()->setHour(12)->setMinute(00);
                $totalMinutos = $hora12PM->hour * 60 + $hora12PM->minute;

                $Hora8AM = Carbon::today()->setHour(8)->setMinute(00);
                $totalMinutos8AM = $Hora8AM->hour * 60 + $Hora8AM->minute;


                $retardo = $request->hora_regreso;
                $Retardo = Carbon::parse($retardo);
                $totalMinutosRetardo = $Retardo->hour * 60 + $Retardo->minute;

                if ($totalMinutosRetardo > $totalMinutos) {
                    return back()->with('error', 'La hora del retardo no puede ser después de las 12PM.');
                }

                if ($totalMinutosRetardo < $totalMinutos8AM) {
                    return back()->with('error', 'Verifica tu hora de ingreso a la empresa.');
                }

                $currentMonth = now()->format('Y-m');
                $firstDayOfMonth = now()->startOfMonth();
                $today = now(); // Día actual

                // Contar solicitudes de retardo
                $retardoCount = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 2)
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', [$firstDayOfMonth, $today])
                    ->whereJsonContains('more_information', ['Tipo_de_ausencia' => 'Retardo'])
                    ->count();

                if ($retardoCount >= 3) {
                    return back()->with('error', 'Solo tienes derecho a tres retardos al mes');
                }

                $more_information[] = [
                    'Tipo_de_ausencia' => $request->ausenciaTipo == 'retardo' ? 'Retardo' : 'No encontro el valor',
                    'value_type' => $request->ausenciaTipo,
                ];
                $moreinformation = json_encode($more_information);
                $Vacaciones = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 2,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'end' => $retardo,
                        'vacation_request_id' => $Vacaciones->id,
                        'status' => 0,
                    ]);
                }
                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud creada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Solicitud creada exitosamente.');
            }
            if ($request->ausenciaTipo == 'salida_antes') {

                if ($dias > 1) {
                    return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
                }

                $hora13PM = Carbon::today()->setHour(13)->setMinute(00);
                $totalMinutos = $hora13PM->hour * 60 + $hora13PM->minute;

                $start = $request->hora_salida;
                $salidaAntes = Carbon::parse($start);
                $totalMinutosRetardo = $salidaAntes->hour * 60 + $salidaAntes->minute;

                if ($totalMinutosRetardo < $totalMinutos) {
                    return back()->with('error', 'No puedes salir antes de las 13PM.');
                }

                $more_information[] = [
                    'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_antes' ? 'Salir antes' : 'No encontro el valor',
                    'value_type' => $request->ausenciaTipo,
                ];
                $moreinformation = json_encode($more_information);
                $Vacaciones = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 2,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'start' => $start,
                        'vacation_request_id' => $Vacaciones->id,
                        'status' => 0,
                    ]);
                }

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud creada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Solicitud creada exitosamente.');
            }

            if ($request->ausenciaTipo == 'salida_durante') {
                $start = $request->hora_salida;
                $end = $request->hora_regreso;

                $hora1Carbon = Carbon::createFromFormat('H:i', $start);
                $hora2Carbon = Carbon::createFromFormat('H:i', $end);

                $hora4 = Carbon::today()->setHour(4)->setMinute(00);
                $totalMinutos = $hora4->hour * 60 + $hora4->minute;

                $hora8 = Carbon::today()->setHour(8)->setMinute(00);
                $total8 = $hora8->hour * 60 + $hora8->minute;

                $hora17 = Carbon::today()->setHour(17)->setMinute(00);
                $total17 = $hora17->hour * 60 + $hora17->minute;

                $start = $request->hora_salida;
                $salidaAntes = Carbon::parse($start);
                $totalSalida = $salidaAntes->hour * 60 + $salidaAntes->minute;

                $end = $request->hora_regreso;
                $salidaRegreso = Carbon::parse($end);
                $totalRegreso = $salidaRegreso->hour * 60 + $salidaRegreso->minute;

                $DiferenciaMinutos = $totalRegreso - $totalSalida;

                if ($DiferenciaMinutos > $totalMinutos) {
                    return back()->with('error', 'No puedes irte de tus labores más de cuatro horas.');
                }

                if ($totalSalida < $total8 || $totalSalida > $total17 || $totalRegreso > $total17) {
                    return back()->with('error', 'Verifica tu información de salida y regreso.');
                }
                if ($totalSalida == $totalRegreso) {
                    return back()->with('error', 'La hora de salida no puede ser la misma que la de regreso');
                }

                $more_information[] = [
                    'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_durante' ? 'Salir durante la jornada' : 'No encontro el valor',
                    'value_type' => $request->ausenciaTipo,
                ];
                $moreinformation = json_encode($more_information);

                $Vacaciones = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 2,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'start' => $hora1Carbon,
                        'end' => $hora2Carbon,
                        'vacation_request_id' => $Vacaciones->id,
                        'status' => 0,
                    ]);
                }
                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud creada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }
                return back()->with('message', 'Solicitud creada exitosamente.');
            }
        }

        ///PATERNIDAD
        if ($request->request_type_id == 3) {
            $request->validate([
                'details' => 'required',
                'reveal_id' => 'required',
                'dates' => 'required',
            ]);

            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $dias = count($datesArray);

            if ($dias > 5) {
                return back()->with('error', 'Solo puedes tomar cinco días.');
            }

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;

            $path = '';
            if ($request->hasFile('archivos')) {
                $filenameWithExt = $request->file('archivos')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivos')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
            }

            if ($dias > 5) {
                return back()->with('error', 'Solo tienes permitido tomar cinco días.');
            }

            if ($dias == 0) {
                return back()->with('error', 'Debes ingresar al menos un día');
            }

            $Vacaciones = VacationRequest::create([
                'user_id' => $user->id,
                'request_type_id' => 3,
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
            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($request->request_type_id);
                $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                    ->pluck('day')
                    ->implode(', ');
                $boss = User::find($Ingreso->jefe_directo_id);

                $boss->notify(new PermissionRequest(
                    $boss->name,
                    $emisor->name,
                    $requestType->type,
                    $days,
                    $request->details,
                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Se creó exitosamente la solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
            }
            return back()->with('message', 'Se creó exitosamente la solicitud.');
        }

        ///INCAPACIDAD
        if ($request->request_type_id == 4) {
            $request->validate([
                'details' => 'required',
                'reveal_id' => 'required',
                'dates' => 'required'
            ]);

            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $dias = count($datesArray);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;

            $path = '';
            if ($request->hasFile('archivos')) {
                $filenameWithExt = $request->file('archivos')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivos')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
            }

            if ($dias == 0) {
                return back()->with('error', 'Debes ingresar al menos un día');
            }

            $Vacaciones = VacationRequest::create([
                'user_id' => $user->id,
                'request_type_id' => 4,
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
            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($request->request_type_id);
                $days = VacationDays::where('vacation_request_id', $Vacaciones->id)
                    ->pluck('day')
                    ->implode(', ');
                $boss = User::find($Ingreso->jefe_directo_id);

                $boss->notify(new PermissionRequest(
                    $boss->name,
                    $emisor->name,
                    $requestType->type,
                    $days,
                    $request->details,
                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Se creó exitosamente la solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
            }

            return back()->with('message', 'Se creó exitosamente la solicitud. Recuerda que estos días son naturales y además estos los paga el IMSS.');
        }

        ///PERMISOS ESPECIALES
        if ($request->request_type_id == 5) {
            $request->validate([
                'details' => 'required',
                'reveal_id' => 'required',
                'dates' => 'required'
            ]);

            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $dias = count($datesArray);

            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

            $path = '';
            if ($request->hasFile('archivos')) {
                $filenameWithExt = $request->file('archivos')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('archivos')->clientExtension();
                $fileNameToStore = time() . $filename . '.' . $extension;
                $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
            }


            if ($dias == 0) {
                return back()->with('error', 'Debes ingresar al menos un día');
            }

            if ($request->Permiso == 'Fallecimiento de un familiar') {
                ///VACACIONES PENDIENTES O APROBADAS///
                if ($dias > 3) {
                    return back()->with('error', 'Solo tienes derecho a tomar tres días.');
                }

                $more_information[] = [
                    'Tipo_de_permiso_especial' => $request->Permiso,
                    'familiar_finado' => $request->familiar
                ];
                $moreinformation = json_encode($more_information);
                $permisoespecial = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 5,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'vacation_request_id' => $permisoespecial->id,
                        'status' => 0,
                    ]);
                }

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $diasAusencia = VacationDays::where('vacation_request_id', $permisoespecial->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $diasAusencia,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Se creó con éxito tu solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Se creó con éxito tu solicitud.');
            }

            if ($request->Permiso == 'Matrimonio del colaborador') {
                ///VACACIONES PENDIENTES O APROBADAS///
                if ($dias > 5) {
                    return back()->with('error', 'Solo tienes derecho a tomar cinco días.');
                }

                if ($dias <= 5) {
                    $more_information[] = [
                        'Tipo_de_permiso_especial' => $request->Permiso,
                    ];
                    $moreinformation = json_encode($more_information);
                    $permisoespecial = VacationRequest::create([
                        'user_id' => $user->id,
                        'request_type_id' => 5,
                        'file' => $path,
                        'details' => $request->details,
                        'more_information' => $moreinformation,
                        'reveal_id' => $request->reveal_id,
                        'direct_manager_id' => $jefedirecto,
                        'direct_manager_status' => 'Pendiente',
                        'rh_status' => 'Pendiente'
                    ]);

                    foreach ($datesArray as $dia) {
                        VacationDays::create([
                            'day' => $dia,
                            'vacation_request_id' => $permisoespecial->id,
                            'status' => 0,
                        ]);
                    }

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $permisoespecial->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);

                        $boss->notify(new PermissionRequest(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Se creó con éxito tu solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Se creó con éxito tu solicitud.');
                }
            }

            if ($request->Permiso == 'Motivos académicos/escolares') {

                if ($request->Posicion == 'colaborador') {
                    if ($path == null) {
                        return back()->with('error', 'Debes ingresar un justificamente.');
                    }
                }

                if ($dias > 1) {
                    return back()->with('error', 'Solo puedes tomar un día a la vez.');
                }

                $more_information[] = [
                    'Tipo_de_permiso_especial' => $request->Permiso,
                    'El_permiso_involucra_a' => $request->Posicion
                ];
                $moreinformation = json_encode($more_information);
                $permisoespecial = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 5,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'vacation_request_id' => $permisoespecial->id,
                        'status' => 0,
                    ]);
                }

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $permisoespecial->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Se creó con éxito tu solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Se creó con éxito tu solicitud.');
            }

            if ($request->Permiso == 'Asuntos personales') {
                ///VACACIONES PENDIENTES O APROBADAS///
                //dd($request);
                if ($mesesTranscurridos < 3) {
                    return back()->with('error', 'No has cumplido el tiempo suficiente para solicitar este permiso.');
                }

                if ($dias > 1) {
                    return back()->with('error', 'Solo puedes tomar un día a la vez.');
                }

                $moreInformationVacation = DB::table('vacations_available_per_users')->where('period', 1)->where('users_id', $user->id)->first();
                $start = $moreInformationVacation->date_start;
                $end = $moreInformationVacation->date_end;

                $permisoEspecial = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 5)
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', [$start, $end])
                    ->whereJsonContains('more_information', ['Tipo_de_permiso_especial' => 'Asuntos personales'])
                    ->count();

                if ($permisoEspecial >= 3) {
                    return back()->with('error', 'Solo tienes derecho a 3 permisos especiales por año.');
                }

                $more_information[] = [
                    'Tipo_de_permiso_especial' => $request->Permiso
                ];
                $moreinformation = json_encode($more_information);
                $permisoespecial = VacationRequest::create([
                    'user_id' => $user->id,
                    'request_type_id' => 5,
                    'file' => $path,
                    'details' => $request->details,
                    'more_information' => $moreinformation,
                    'reveal_id' => $request->reveal_id,
                    'direct_manager_id' => $jefedirecto,
                    'direct_manager_status' => 'Pendiente',
                    'rh_status' => 'Pendiente'
                ]);

                foreach ($datesArray as $dia) {
                    VacationDays::create([
                        'day' => $dia,
                        'vacation_request_id' => $permisoespecial->id,
                        'status' => 0,
                    ]);
                }

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $permisoespecial->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequest(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Se creó con éxito tu solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Se creó con éxito tu solicitud.');
            }
        }
    }

    public function RequestBoss(Request $request)
    {
        $user = auth()->user();

        $HeIsBossOf = Employee::where('jefe_directo_id', $user->id)->where('status', 1)->pluck('user_id');
        $Solicitudes = DB::table('vacation_requests')->whereIn('user_id', $HeIsBossOf)->orderBy('created_at', 'desc');

        $SumaSolicitudes = count($Solicitudes->get());
        // Recorre las solicitudes paginadas, pero no crees un nuevo array desde cero
        $solicitudesCollection = $Solicitudes->get()->map(function ($Solicitud) {
            $nameUser = User::where('id', $Solicitud->user_id)->first();
            $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
            $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
            $Reveal = User::where('id', $Solicitud->reveal_id)->first();

            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }
            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();

            $Datos = [];
            foreach ($Vacaciones as $vaca) {
                $Datos[] = [
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                ];
            }

            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }

            // return (object)[
            $solicitud = new \stdClass();
            $solicitud->image = $nameUser->image;
            $solicitud->created_at = $Solicitud->created_at;
            $solicitud->id = $Solicitud->id;
            $solicitud->name = $nameUser->name . ' ' . $nameUser->lastname;
            $solicitud->current_vacation = $Datos[0]['dv'] ?? null;
            $solicitud->current_vacation_expiration = $Datos[0]['cutoff_date'] ?? null;
            $solicitud->next_vacation = empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
            $solicitud->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
            $solicitud->direct_manager_status = $Solicitud->direct_manager_status;
            $solicitud->rh_status = $Solicitud->rh_status;
            $solicitud->request_type = $RequestType->type;
            $solicitud->specific_type = $RequestType->type == 1 ?: '-';
            $solicitud->days_absent = $dias ?? null;
            $solicitud->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
            $solicitud->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
            $solicitud->file = $Solicitud->file == null ? null : $Solicitud->file;
            $solicitud->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
            $solicitud->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
            $solicitud->details = $Solicitud->details;
            return $solicitud;
        });


        if ($request->filled('search')) {
            $solicitudesCollection = $solicitudesCollection->filter(function ($solicitud) use ($request) {
                return stripos($solicitud->name, $request->search) !== false;
            });
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $solicitudesCollection = $solicitudesCollection->filter(function ($solicitud) use ($request) {
                return $solicitud->request_type == $request->tipo;
            });
        }
        if ($request->has('fecha') && $request->fecha != '') {
            $solicitudesCollection = $solicitudesCollection->filter(function ($solicitud) use ($request) {
                return in_array($request->fecha, $solicitud->days_absent);
            });
        }

        $page = $request->get('page', 1);
        $perPage = 5;
        $solicitudes = new LengthAwarePaginator(
            $solicitudesCollection->forPage($page, $perPage),
            $solicitudesCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );


        return view('request.authorize', compact('solicitudes', 'SumaSolicitudes'));
    }

    // public function authorizeRequestRH(Request $request)
    // {
    //     $user = auth()->user();

    //     $Solicitudes = DB::table('vacation_requests')->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada')->orderBy('created_at', 'desc')->get();
    //     $sumaAprobadas = count($Solicitudes);

    //     // $SolicitudesAprobadas = [];
    //     $solicitudesAprobadasCollection = $Solicitudes->map(function ($Solicitud) {
    //         $nameUser = User::where('id', $Solicitud->user_id)->first();
    //         $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
    //         $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
    //         $Reveal = User::where('id', $Solicitud->reveal_id)->first();

    //         $dias = [];
    //         foreach ($Days as $Day) {
    //             $dias[] = $Day->day;
    //         }

    //         // Ordenar las fechas de la más cercana a la más lejana
    //         usort($dias, function ($a, $b) {
    //             return strtotime($a) - strtotime($b);
    //         });

    //         $time = [];
    //         foreach ($Days as $Day) {
    //             $time[] = [
    //                 'start' => Carbon::parse($Day->start)->format('H:i'),
    //                 'end' => Carbon::parse($Day->end)->format('H:i')
    //             ];
    //         }

    //         $fechaActual = Carbon::now();
    //         $Vacaciones = DB::table('vacations_available_per_users')
    //             ->where('users_id', $Solicitud->user_id)
    //             ->where('cutoff_date', '>=', $fechaActual)
    //             ->orderBy('cutoff_date', 'asc')
    //             ->get();
    //         $Datos = [];
    //         foreach ($Vacaciones as $vaca) {
    //             $Datos[] = [
    //                 'dv' => $vaca->dv,
    //                 'cutoff_date' => $vaca->cutoff_date,
    //             ];
    //         }

    //         $solicitudesAprobadas = new \stdClass();
    //         $solicitudesAprobadas->image = $nameUser->image;
    //         $solicitudesAprobadas->created_at = $Solicitud->created_at;
    //         $solicitudesAprobadas->id = $Solicitud->id;
    //         $solicitudesAprobadas->name = $nameUser->name . ' ' . $nameUser->lastname;
    //         $solicitudesAprobadas->current_vacation = $Datos[0]['dv'];
    //         $solicitudesAprobadas->current_vacation_expiration = $Datos[0]['cutoff_date'];
    //         $solicitudesAprobadas->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
    //         $solicitudesAprobadas->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
    //         $solicitudesAprobadas->direct_manager_status = $Solicitud->direct_manager_status;
    //         $solicitudesAprobadas->rh_status = $Solicitud->rh_status;
    //         $solicitudesAprobadas->request_type = $RequestType->type;
    //         $solicitudesAprobadas->specific_type = $RequestType->type == 1 ?: '-';
    //         $solicitudesAprobadas->days_absent = $dias;
    //         $solicitudesAprobadas->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
    //         $solicitudesAprobadas->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
    //         $solicitudesAprobadas->file = $Solicitud->file == null ? null : $Solicitud->file;
    //         $solicitudesAprobadas->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
    //         $solicitudesAprobadas->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
    //         return $solicitudesAprobadas;
    //     });

    //     if ($request->filled('search')) {
    //         $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
    //             return stripos($solicitud->name, $request->search) !== false;
    //         });
    //     }

    //     if ($request->has('tipo') && $request->tipo != '') {
    //         $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
    //             return $solicitud->request_type == $request->tipo;
    //         });
    //     }
    //     if ($request->has('fecha') && $request->fecha != '') {
    //         $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
    //             return in_array($request->fecha, $solicitud->days_absent);
    //         });
    //     }


    //     $page = $request->get('page', 1);
    //     $perPage = 5;
    //     $Aprobadas = new LengthAwarePaginator(
    //         $solicitudesAprobadasCollection->forPage($page, $perPage),
    //         $solicitudesAprobadasCollection->count(),
    //         $perPage,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );



    //     $SolicitudesPendientes = DB::table('vacation_requests')
    //         ->where('direct_manager_status', 'Aprobada')
    //         ->where('rh_status', 'Pendiente')
    //         ->orderBy('created_at', 'desc');
    //     $sumaPendientes = count($SolicitudesPendientes->get());

    //     $solicitudesPendientesCollection = $SolicitudesPendientes->get()->map(function ($Solicitud) {
    //         $nameUser = User::where('id', $Solicitud->user_id)->first();
    //         $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
    //         $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
    //         $Reveal = User::where('id', $Solicitud->reveal_id)->first();

    //         $dias = [];
    //         foreach ($Days as $Day) {
    //             $dias[] = $Day->day;
    //         }

    //         // Ordenar las fechas de la más cercana a la más lejana
    //         usort($dias, function ($a, $b) {
    //             return strtotime($a) - strtotime($b);
    //         });

    //         $time = [];
    //         foreach ($Days as $Day) {
    //             $time[] = [
    //                 'start' => Carbon::parse($Day->start)->format('H:i'),
    //                 'end' => Carbon::parse($Day->end)->format('H:i')
    //             ];
    //         }

    //         $fechaActual = Carbon::now();
    //         $Vacaciones = DB::table('vacations_available_per_users')
    //             ->where('users_id', $Solicitud->user_id)
    //             ->where('cutoff_date', '>=', $fechaActual)
    //             ->orderBy('cutoff_date', 'asc')
    //             ->get();
    //         $Datos = [];
    //         foreach ($Vacaciones as $vaca) {
    //             $Datos[] = [
    //                 'dv' => $vaca->dv,
    //                 'cutoff_date' => $vaca->cutoff_date,
    //             ];
    //         }

    //         // $Pendientes[] = [
    //         $SolicitudesPendientes = new \stdClass();
    //         $SolicitudesPendientes->image = $nameUser->image;
    //         $SolicitudesPendientes->created_at = $Solicitud->created_at;
    //         $SolicitudesPendientes->id = $Solicitud->id;
    //         $SolicitudesPendientes->name = $nameUser->name . ' ' . $nameUser->lastname;
    //         $SolicitudesPendientes->current_vacation = $Datos[0]['dv'];
    //         $SolicitudesPendientes->current_vacation_expiration = $Datos[0]['cutoff_date'];
    //         $SolicitudesPendientes->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
    //         $SolicitudesPendientes->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
    //         $SolicitudesPendientes->direct_manager_status = $Solicitud->direct_manager_status;
    //         $SolicitudesPendientes->rh_status = $Solicitud->rh_status;
    //         $SolicitudesPendientes->request_type = $RequestType->type;
    //         $SolicitudesPendientes->specific_type = $RequestType->type == 1 ?: '-';
    //         $SolicitudesPendientes->days_absent = $dias;
    //         $SolicitudesPendientes->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
    //         $SolicitudesPendientes->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
    //         $SolicitudesPendientes->file = $Solicitud->file == null ? null : $Solicitud->file;
    //         $SolicitudesPendientes->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
    //         $SolicitudesPendientes->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
    //         return $SolicitudesPendientes;
    //     });

    //     if ($request->filled('search')) {
    //         $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
    //             return stripos($solicitud->name, $request->search) !== false;
    //         });
    //     }

    //     if ($request->has('tipo') && $request->tipo != '') {
    //         $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
    //             return $solicitud->request_type == $request->tipo;
    //         });
    //     }
    //     if ($request->has('fecha') && $request->fecha != '') {
    //         $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
    //             return in_array($request->fecha, $solicitud->days_absent);
    //         });
    //     }
    //     $Pendientes = new LengthAwarePaginator(
    //         $solicitudesPendientesCollection->forPage($page, $perPage),
    //         $solicitudesPendientesCollection->count(),
    //         $perPage,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );



    //     $SolicitudesRechazadas = DB::table('vacation_requests')->where('direct_manager_status', 'Cancelada por el usuario')
    //         ->where('rh_status', 'Cancelada por el usuario')->orderBy('created_at', 'desc')->get();

    //     $sumaCanceladasUsuario = count($SolicitudesRechazadas);

    //     $solicitudesRechazadasCollection = $SolicitudesRechazadas->map(function ($Solicitud) {
    //         $nameUser = User::where('id', $Solicitud->user_id)->first();
    //         $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
    //         $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
    //         $Reveal = User::where('id', $Solicitud->reveal_id)->first();

    //         $dias = [];
    //         foreach ($Days as $Day) {
    //             $dias[] = $Day->day;
    //         }

    //         // Ordenar las fechas de la más cercana a la más lejana
    //         usort($dias, function ($a, $b) {
    //             return strtotime($a) - strtotime($b);
    //         });

    //         $time = [];
    //         foreach ($Days as $Day) {
    //             $time[] = [
    //                 'start' => Carbon::parse($Day->start)->format('H:i'),
    //                 'end' => Carbon::parse($Day->end)->format('H:i')
    //             ];
    //         }

    //         $fechaActual = Carbon::now();
    //         $Vacaciones = DB::table('vacations_available_per_users')
    //             ->where('users_id', $Solicitud->user_id)
    //             ->where('cutoff_date', '>=', $fechaActual)
    //             ->orderBy('cutoff_date', 'asc')
    //             ->get();
    //         $Datos = [];
    //         foreach ($Vacaciones as $vaca) {
    //             $Datos[] = [
    //                 'dv' => $vaca->dv,
    //                 'cutoff_date' => $vaca->cutoff_date,
    //             ];
    //         }

    //         // $rechazadas[] = [
    //         $rechazadas = new \stdClass();
    //         $rechazadas->image = $nameUser->image;
    //         $rechazadas->created_at = $Solicitud->created_at;
    //         $rechazadas->id = $Solicitud->id;
    //         $rechazadas->name = $nameUser->name . ' ' . $nameUser->lastname;
    //         $rechazadas->current_vacation = $Datos[0]['dv'];
    //         $rechazadas->current_vacation_expiration = $Datos[0]['cutoff_date'];
    //         $rechazadas->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
    //         $rechazadas->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
    //         $rechazadas->details = $Solicitud->details;
    //         $rechazadas->commentary = $Solicitud->commentary;
    //         $rechazadas->direct_manager_status = $Solicitud->direct_manager_status;
    //         $rechazadas->rh_status = $Solicitud->rh_status;
    //         $rechazadas->request_type = $RequestType->type;
    //         $rechazadas->specific_type = $RequestType->type == 1 ?: '-';
    //         $rechazadas->days_absent = $dias;
    //         $rechazadas->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
    //         $rechazadas->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
    //         $rechazadas->file = $Solicitud->file == null ? null : $Solicitud->file;
    //         $rechazadas->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
    //         $rechazadas->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
    //         return $rechazadas;
    //     });

    //     if ($request->filled('search')) {
    //         $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
    //             return stripos($solicitud->name, $request->search) !== false;
    //         });
    //     }

    //     if ($request->has('tipo') && $request->tipo != '') {
    //         $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
    //             return $solicitud->request_type == $request->tipo;
    //         });
    //     }
    //     if ($request->has('fecha') && $request->fecha != '') {
    //         $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
    //             return in_array($request->fecha, $solicitud->days_absent);
    //         });
    //     }

    //     $rechazadas = new LengthAwarePaginator(
    //         $solicitudesRechazadasCollection->forPage($page, $perPage),
    //         $solicitudesRechazadasCollection->count(),
    //         $perPage,
    //         $page,
    //         ['path' => $request->url(), 'query' => $request->query()]
    //     );

    //     $usersid = DB::table('employees')->where('status', 1)->pluck('user_id');
    //     $IdandNameUser = [];
    //     foreach ($usersid as $userid) {
    //         $Usuario = User::where('id', $userid)->first();
    //         $IdandNameUser[] = [
    //             'name' => $Usuario->name . ' ' . $Usuario->lastname,
    //             'id' => $userid
    //         ];
    //     }

    //     $agregarvacaciones = MakeUpVacations::all();
    //     $vacacionesagregadas = [];
    //     foreach ($agregarvacaciones as $vacacionesUser) {
    //         $Usuario = User::where('id', $vacacionesUser->user_id)->first();
    //         $vacacionesagregadas[] = [
    //             'iduser' => $Usuario->name . ' ' . $Usuario->lastname,
    //             'num_days' => $vacacionesUser->num_days,
    //             'description' => $vacacionesUser->description
    //         ];
    //     }
    //     return view('request.autorize_rh_pendientes', compact('Pendientes', 'Aprobadas',  'sumaAprobadas', 'sumaPendientes', 'sumaCanceladasUsuario', 'rechazadas', 'IdandNameUser', 'vacacionesagregadas'));
    // }

    public function RequestAuthorizeRH(Request $request)
    {
        $user = auth()->user();

        $Solicitudes = DB::table('vacation_requests')->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada')->orderBy('created_at', 'desc')->get();
        $sumaAprobadas = count($Solicitudes);

        // $SolicitudesAprobadas = [];
        $solicitudesAprobadasCollection = $Solicitudes->map(function ($Solicitud) {
            $nameUser = User::where('id', $Solicitud->user_id)->first();
            $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
            $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
            $Reveal = User::where('id', $Solicitud->reveal_id)->first();

            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }

            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();
            $Datos = [];
            foreach ($Vacaciones as $vaca) {
                $Datos[] = [
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                ];
            }

            $solicitudesAprobadas = new \stdClass();
            $solicitudesAprobadas->image = $nameUser->image;
            $solicitudesAprobadas->created_at = $Solicitud->created_at;
            $solicitudesAprobadas->id = $Solicitud->id;
            $solicitudesAprobadas->name = $nameUser->name . ' ' . $nameUser->lastname;
            $solicitudesAprobadas->current_vacation = $Datos[0]['dv'];
            $solicitudesAprobadas->current_vacation_expiration = $Datos[0]['cutoff_date'];
            $solicitudesAprobadas->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
            $solicitudesAprobadas->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
            $solicitudesAprobadas->direct_manager_status = $Solicitud->direct_manager_status;
            $solicitudesAprobadas->rh_status = $Solicitud->rh_status;
            $solicitudesAprobadas->request_type = $RequestType->type;
            $solicitudesAprobadas->specific_type = $RequestType->type == 1 ?: '-';
            $solicitudesAprobadas->days_absent = $dias;
            $solicitudesAprobadas->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
            $solicitudesAprobadas->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
            $solicitudesAprobadas->file = $Solicitud->file == null ? null : $Solicitud->file;
            $solicitudesAprobadas->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
            $solicitudesAprobadas->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
            $solicitudesAprobadas->details = $Solicitud->details;
            return $solicitudesAprobadas;
        });

        if ($request->filled('search')) {
            $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
                return stripos($solicitud->name, $request->search) !== false;
            });
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
                return $solicitud->request_type == $request->tipo;
            });
        }
        if ($request->has('fecha') && $request->fecha != '') {
            $solicitudesAprobadasCollection = $solicitudesAprobadasCollection->filter(function ($solicitud) use ($request) {
                return in_array($request->fecha, $solicitud->days_absent);
            });
        }


        $page = $request->get('page', 1);
        $perPage = 5;
        $Aprobadas = new LengthAwarePaginator(
            $solicitudesAprobadasCollection->forPage($page, $perPage),
            $solicitudesAprobadasCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $usersid = DB::table('employees')->where('status', 1)->pluck('user_id');
        $IdandNameUser = [];
        foreach ($usersid as $userid) {
            $Usuario = User::where('id', $userid)->first();
            $IdandNameUser[] = [
                'name' => $Usuario->name . ' ' . $Usuario->lastname,
                'id' => $userid
            ];
        }

        $SolicitudesPendientes = DB::table('vacation_requests')
            ->where('direct_manager_status', 'Aprobada')
            ->where('rh_status', 'Pendiente')
            ->orderBy('created_at', 'desc')->get();
        $sumaPendientes = count($SolicitudesPendientes);

        $SolicitudesRechazadas = DB::table('vacation_requests')->where('direct_manager_status', 'Cancelada por el usuario')
            ->where('rh_status', 'Cancelada por el usuario')->orderBy('created_at', 'desc')->get();

        $sumaCanceladasUsuario = count($SolicitudesRechazadas);
        return view('request.authorize_rh_aprobadas', compact('Aprobadas', 'sumaAprobadas', 'IdandNameUser', 'sumaPendientes', 'sumaCanceladasUsuario'));
    }

    public function RequestRejectUserRH(Request $request)
    {
        $user = auth()->user();
        $SolicitudesRechazadas = DB::table('vacation_requests')->where('direct_manager_status', 'Cancelada por el usuario')
            ->where('rh_status', 'Cancelada por el usuario')->orderBy('created_at', 'desc')->get();

        $sumaCanceladasUsuario = count($SolicitudesRechazadas);

        $solicitudesRechazadasCollection = $SolicitudesRechazadas->map(function ($Solicitud) {
            $nameUser = User::where('id', $Solicitud->user_id)->first();
            $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
            $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
            $Reveal = User::where('id', $Solicitud->reveal_id)->first();

            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }

            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();
            $Datos = [];
            foreach ($Vacaciones as $vaca) {
                $Datos[] = [
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                ];
            }

            // $rechazadas[] = [
            $rechazadas = new \stdClass();
            $rechazadas->image = $nameUser->image;
            $rechazadas->created_at = $Solicitud->created_at;
            $rechazadas->id = $Solicitud->id;
            $rechazadas->name = $nameUser->name . ' ' . $nameUser->lastname;
            $rechazadas->current_vacation = $Datos[0]['dv'];
            $rechazadas->current_vacation_expiration = $Datos[0]['cutoff_date'];
            $rechazadas->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
            $rechazadas->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
            $rechazadas->details = $Solicitud->details;
            $rechazadas->commentary = $Solicitud->commentary;
            $rechazadas->direct_manager_status = $Solicitud->direct_manager_status;
            $rechazadas->rh_status = $Solicitud->rh_status;
            $rechazadas->request_type = $RequestType->type;
            $rechazadas->specific_type = $RequestType->type == 1 ?: '-';
            $rechazadas->days_absent = $dias;
            $rechazadas->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
            $rechazadas->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
            $rechazadas->file = $Solicitud->file == null ? null : $Solicitud->file;
            $rechazadas->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
            $rechazadas->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
            return $rechazadas;
        });

        if ($request->filled('search')) {
            $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
                return stripos($solicitud->name, $request->search) !== false;
            });
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
                return $solicitud->request_type == $request->tipo;
            });
        }
        if ($request->has('fecha') && $request->fecha != '') {
            $solicitudesRechazadasCollection = $solicitudesRechazadasCollection->filter(function ($solicitud) use ($request) {
                return in_array($request->fecha, $solicitud->days_absent);
            });
        }

        $page = $request->get('page', 1);
        $perPage = 5;
        $rechazadas = new LengthAwarePaginator(
            $solicitudesRechazadasCollection->forPage($page, $perPage),
            $solicitudesRechazadasCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $usersid = DB::table('employees')->where('status', 1)->pluck('user_id');
        $IdandNameUser = [];
        foreach ($usersid as $userid) {
            $Usuario = User::where('id', $userid)->first();
            $IdandNameUser[] = [
                'name' => $Usuario->name . ' ' . $Usuario->lastname,
                'id' => $userid
            ];
        }

        $Solicitudes = DB::table('vacation_requests')->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada')->orderBy('created_at', 'desc')->get();
        $sumaAprobadas = count($Solicitudes);

        $SolicitudesPendientes = DB::table('vacation_requests')
            ->where('direct_manager_status', 'Aprobada')
            ->where('rh_status', 'Pendiente')
            ->orderBy('created_at', 'desc')->get();
        $sumaPendientes = count($SolicitudesPendientes);

        return view('request.autorize_rh_rechazadas', compact('sumaCanceladasUsuario', 'rechazadas', 'IdandNameUser', 'sumaAprobadas', 'sumaPendientes'));
    }

    public function RequestPendingRH(Request $request)
    {

        $user = auth()->user();

        $SolicitudesPendientes = DB::table('vacation_requests')
            ->where('direct_manager_status', 'Aprobada')
            ->where('rh_status', 'Pendiente')
            ->orderBy('created_at', 'desc')->get();
        $sumaPendientes = count($SolicitudesPendientes);

        $solicitudesPendientesCollection = $SolicitudesPendientes->map(function ($Solicitud) {
            $nameUser = User::where('id', $Solicitud->user_id)->first();
            $RequestType = RequestType::where('id', $Solicitud->request_type_id)->first();
            $Days = VacationDays::where('vacation_request_id', $Solicitud->id)->get();
            $Reveal = User::where('id', $Solicitud->reveal_id)->first();

            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }

            // Ordenar las fechas de la más cercana a la más lejana
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
                ->where('cutoff_date', '>=', $fechaActual)
                ->orderBy('cutoff_date', 'asc')
                ->get();
            $Datos = [];
            foreach ($Vacaciones as $vaca) {
                $Datos[] = [
                    'dv' => $vaca->dv,
                    'cutoff_date' => $vaca->cutoff_date,
                ];
            }

            // $Pendientes[] = [
            $SolicitudesPendientes = new \stdClass();
            $SolicitudesPendientes->image = $nameUser->image;
            $SolicitudesPendientes->created_at = $Solicitud->created_at;
            $SolicitudesPendientes->id = $Solicitud->id;
            $SolicitudesPendientes->name = $nameUser->name . ' ' . $nameUser->lastname;
            $SolicitudesPendientes->current_vacation = $Datos[0]['dv'];
            $SolicitudesPendientes->current_vacation_expiration = $Datos[0]['cutoff_date'];
            $SolicitudesPendientes->next_vacation =  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'];
            $SolicitudesPendientes->expiration_of_next_vacation = empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'];
            $SolicitudesPendientes->direct_manager_status = $Solicitud->direct_manager_status;
            $SolicitudesPendientes->rh_status = $Solicitud->rh_status;
            $SolicitudesPendientes->request_type = $RequestType->type;
            $SolicitudesPendientes->specific_type = $RequestType->type == 1 ?: '-';
            $SolicitudesPendientes->days_absent = $dias;
            $SolicitudesPendientes->method_of_payment = $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro'))));
            $SolicitudesPendientes->reveal_id = $Reveal->name . ' ' . $Reveal->lastname;
            $SolicitudesPendientes->file = $Solicitud->file == null ? null : $Solicitud->file;
            $SolicitudesPendientes->time = in_array($Solicitud->request_type_id, [2]) ? $time : null;
            $SolicitudesPendientes->more_information = $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true);
            $SolicitudesPendientes->details = $Solicitud->details;
            return $SolicitudesPendientes;
        });

        if ($request->filled('search')) {
            $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
                return stripos($solicitud->name, $request->search) !== false;
            });
        }

        if ($request->has('tipo') && $request->tipo != '') {
            $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
                return $solicitud->request_type == $request->tipo;
            });
        }
        if ($request->has('fecha') && $request->fecha != '') {
            $solicitudesPendientesCollection = $solicitudesPendientesCollection->filter(function ($solicitud) use ($request) {
                return in_array($request->fecha, $solicitud->days_absent);
            });
        }

        $page = $request->get('page', 1);
        $perPage = 5;
        $Pendientes = new LengthAwarePaginator(
            $solicitudesPendientesCollection->forPage($page, $perPage),
            $solicitudesPendientesCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $usersid = DB::table('employees')->where('status', 1)->pluck('user_id');
        $IdandNameUser = [];
        foreach ($usersid as $userid) {
            $Usuario = User::where('id', $userid)->first();
            $IdandNameUser[] = [
                'name' => $Usuario->name . ' ' . $Usuario->lastname,
                'id' => $userid
            ];
        }

        $SolicitudesRechazadas = DB::table('vacation_requests')->where('direct_manager_status', 'Cancelada por el usuario')
            ->where('rh_status', 'Cancelada por el usuario')->orderBy('created_at', 'desc')->get();

        $sumaCanceladasUsuario = count($SolicitudesRechazadas);

        $Solicitudes = DB::table('vacation_requests')->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada')->orderBy('created_at', 'desc')->get();
        $sumaAprobadas = count($Solicitudes);

        return view('request.autorize_rh_pendientes', compact('Pendientes', 'sumaPendientes', 'IdandNameUser', 'sumaCanceladasUsuario', 'sumaAprobadas'));
    }

    public function UserVacationInformation()
    {
        $Primerperiodos = DB::table('vacations_available_per_users')->where('period', 1)->get();
        foreach ($Primerperiodos as $Primerperiodo) {
            $fechaActual = Carbon::now();
            $PeriodOne = $Primerperiodo->date_end;
            if ($PeriodOne && $fechaActual->greaterThanOrEqualTo(Carbon::parse($PeriodOne))) {
                $date_end = $Primerperiodo->cutoff_date;
                $fechaCaducidad = Carbon::parse($Primerperiodo->cutoff_date)->addYear()->format('Y-m-d');
                $Aniversario = $Primerperiodo->anniversary_number + 1;
                $DiasPorAñoCumplido = VacationPerYear::where('year',  $Primerperiodo->anniversary_number)->value('days');

                $Segundoperiodo = DB::table('vacations_available_per_users')->where('users_id', $Primerperiodo->users_id)
                    ->where('period', 2)->exists();

                if ($Segundoperiodo) {
                    DB::table('vacations_available_per_users')->where('users_id', $Primerperiodo->users_id)->where('period', 2)->update([
                        'period' => 3,
                    ]);
                }

                DB::table('vacations_available_per_users')->where('users_id', $Primerperiodo->users_id)->where('period', 1)->update([
                    'period' => 2,
                    'days_availables' =>  $DiasPorAñoCumplido,
                ]);

                VacationsAvailablePerUser::create([
                    'period' => 1,
                    'days_availables' => 0,
                    'dv' => 0,
                    'days_enjoyed' => 0,
                    'date_start' => $PeriodOne,
                    'date_end' => $date_end,
                    'cutoff_date' => $fechaCaducidad,
                    'anniversary_number' => $Aniversario,
                    'waiting' => 0,
                    'users_id' => $Primerperiodo->users_id,
                ]);
            }
        }

        $Primerperiodos = DB::table('vacations_available_per_users')->where('period', 1)->get();
        foreach ($Primerperiodos as $Primerperiodo) {
            $fechaActual = Carbon::now();
            $fechaInicio = Carbon::parse($Primerperiodo->date_start);
            $fechaFin = Carbon::parse($Primerperiodo->date_end);
            $DiasPorAñoCumplido = VacationPerYear::where('year',  $Primerperiodo->anniversary_number)->value('days');
            $diasTranscurridos = $fechaInicio->diffInDays($fechaActual);
            $diasaño = $fechaInicio->diffInDays($fechaFin);
            $calculoVacaciones = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido), 2);
            $calculoVacacionesRedondeado = round(($diasTranscurridos / $diasaño) * ($DiasPorAñoCumplido));
            $dv = $Primerperiodo->dv;
            $days_enjoyed = $Primerperiodo->days_enjoyed;
            $WaitingOne = $Primerperiodo->waiting;
            $dvNew = $calculoVacacionesRedondeado - $days_enjoyed - $WaitingOne;
            if ($calculoVacaciones <= $DiasPorAñoCumplido) {
                DB::table('vacations_available_per_users')->where('id', $Primerperiodo->id)->update([
                    'days_availables' =>  $calculoVacaciones,
                    'dv' => $dvNew
                ]);
            } else {
                DB::table('vacations_available_per_users')->where('id', $Primerperiodo->id)->update([
                    'days_availables' =>  $DiasPorAñoCumplido,
                    'dv' => $dvNew
                ]);
            }
        }

        $users = User::where('status', 1)->get();
        return view('admin.vacations.index', compact('users'));
    }

    public function AllVacationsAndPermits()
    {
        $RequestAndVacations = VacationRequest::orderBy('created_at', 'desc')->get();

        $Information = [];
        foreach ($RequestAndVacations as $Requests) {
            $creador = User::where('id', $Requests->user_id)->first();
            $typeRequest = RequestType::where('id', $Requests->request_type_id)->value('type');
            $Days = VacationDays::where('vacation_request_id', $Requests->id)->get();
            $dias = [];
            foreach ($Days as $Day) {
                $dias[] = $Day->day;
            }
            usort($dias, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });
            $time = [];
            foreach ($Days as $Day) {
                $time[] = [
                    'start' => Carbon::parse($Day->start)->format('H:i'),
                    'end' => Carbon::parse($Day->end)->format('H:i')
                ];
            }

            $Information[] = [
                'id' => $Requests->id,
                'Solicitante' => $creador->name . ' ' . $creador->lastname,
                'Tipo_solicitud' => $typeRequest,
                'dias' => $dias,
                'Motivo' => $Requests->details
            ];
        }

        dd($Information);
        return back()->with('Information');
    }

    public function ConfirmRejectedByRh(Request $request)
    {
        if ($request->value == 'aprobada') {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Verificada RH',
                'direct_manager_status' => 'Verificada RH',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            return back()->with('nessage', 'La solicitud rechazo correctamente. Puedes regresarle las vacaciones faltantes al usuario.');
        }

        if ($request->value == 'rechazada') {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Denegada RH',
                'direct_manager_status' => 'Denegada RH',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 1
            ]);

            return back()->with('nessage', 'La solicitud rechazo correctamente. No es necesario aumentarle días al usuario.');
        }
    }

    public function AuthorizePermissionBoss(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required',
        ]);

        $solicitud = DB::table('vacation_requests')->where('id', $request->id)->first();

        $IsBoss = VacationRequest::where('id', $request->id)->value('direct_manager_id');
        if ($IsBoss != $user->id) {
            return back()->with('error', 'Sólo su jefe directo puede autorizar la solicitud');
        }

        DB::table('vacation_requests')->where('id', $request->id)->update([
            'direct_manager_status' => 'Aprobada'
        ]);

        try {
            $emisor = User::find($user->id);
            $requestType = RequestType::find($solicitud->request_type_id);
            $ApplicationOwner = User::find($solicitud->user_id);
            $ApplicationOwner->notify(new AuthorizeRequest(
                $ApplicationOwner->name,
                $emisor->name,
                $requestType->type,
                $solicitud->details,
            ));
        } catch (\Exception $e) {
            return back()->with('warning', 'Solicitud aprobada exitosamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
        }

        /* $dep = Department::find(1);
        $positions = Position::where("department_id", 1)->pluck("name", "id");
        $data = $dep->positions;
        $users = [];
        foreach ($data as $dat) {
            foreach ($dat->getEmployees as $emp) {
                if ($emp->user->status == 1) {
                    $users["{$emp->user->id}"] = $emp->user->id;
                }
            }
        }

        foreach ($users as $userID) {
            if ($userID == 6) {
                $emisor = User::find($user->id);
                $RH = User::where('id', $userID)->first();
                $ApplicationOwner = User::find($solicitud->user_id);
                $requestType = RequestType::find($solicitud->request_type_id);
                $days = VacationDays::where('vacation_request_id', $solicitud->id)
                    ->pluck('day')
                    ->implode(', ');
                $RHName = $RH->name;
                try {
                    $RH->notify(new ApprovalNoticeByDirectBoss(
                        $RHName,
                        $emisor->name,
                        $ApplicationOwner->name,
                        $requestType->type,
                        $days,
                        $solicitud->details
                    ));
                } catch (Exception $e) {
                    return back()->with('warning', 'Solicitud aprobada exitosamente. Sin embargo, no se pudo enviar el correo electrónico al jefe de Recursos Humanos.');
                }
            }
        } */

        return back()->with('message', 'Solicitud aprobada exitosamente.');
    }

    public function RejectPermissionBoss(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required',
            'commentary' => 'required'
        ]);

        $solicitud = VacationRequest::where('id', $request->id)->first();
        if ($solicitud->direct_manager_id != $user->id) {
            return back()->with('error', 'Solo su jefe directo puede rechazar la solicitud');
        }

        if ($solicitud->direct_manager_status == 'Aprobada') {
            return redirect()->with('error', 'La solicitud ya fue aprobada, por lo tanto ya no puede ser rechazada.');
        }

        if ($request->commentary == null) {
            return back()->with('error', 'Debes indicar el motivo por el cual deseas rechazar la solicitud.');
        }

        if ($solicitud->request_type_id == 1) {
            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $solicitud->user_id)
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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'id' => $vaca->id,
                ];
            }

            $InfoVacaciones = DB::table('vacation_information')->where('id_vacation_request', $request->id)->get();
            $total = count($InfoVacaciones);
            if ($total == 2) {
                $idOne = (int) $Datos[0]['id'];
                $idTwo = (int) $Datos[1]['id'];
                $WaitingOne = $Datos[0]['waiting'];
                $WaitingTwo = $Datos[1]['waiting'];
                $dvOne = $Datos[0]['dv'];
                $dvTwo = $Datos[1]['dv'];

                $InfoTwo = DB::table('vacation_information')->where('id_vacation_request', $solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                $InfoOne = DB::table('vacation_information')->where('id_vacation_request', $solicitud->id)->where('id_vacations_availables', $idOne)->first();
                $totaldaysOne = $InfoOne->total_days == null ? 0 : $InfoOne->total_days;
                $newWaiting = $WaitingOne - $totaldaysOne;
                $totaldvOne = $dvOne + $totaldaysOne;

                $totaldaysTwo = $InfoTwo->total_days == null ? 0 : $InfoTwo->total_days;
                $newWaitingTwo = $WaitingTwo - $totaldaysTwo;
                $totaldvTwo = $dvTwo + $totaldaysTwo;

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Rechazada',
                    'direct_manager_status' => 'Rechazada',
                    'commentary' => $request->commentary
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 0
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $solicitud->user_id)->where('id', $idOne)->update([
                    'waiting' => $newWaiting,
                    'dv' => $totaldvOne
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $solicitud->user_id)->where('id', $idTwo)->update([
                    'waiting' => $newWaitingTwo,
                    'dv' => $totaldvTwo
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($solicitud->request_type_id);
                    $ApplicationOwner = User::find($solicitud->user_id);
                    $ApplicationOwner->notify(new RejectRequestBoss(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $request->commentary,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones rechazadas correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
                }

                return back()->with('message', 'Vacaciones rechazadas correctamente');
            }

            if ($total == 1) {
                $VacaInfo = DB::table('vacation_information')->where('id_vacation_request', $solicitud->id)->first();
                $id_vacations_availables = $VacaInfo->id_vacations_availables;
                $VacacionesAviles = DB::table('vacations_available_per_users')->where('id', $id_vacations_availables)->first();
                $totaldaysOne =  //Si no esta el campo que no lo tome en cuenta
                    $VacaInfo->total_days == null ? 0 : $VacaInfo->total_days;
                $newWaiting = $VacacionesAviles->waiting - $totaldaysOne;
                $totaldv = $VacacionesAviles->dv + $totaldaysOne;
                //dd('totaldevacaciones'.':'.$totaldaysOne.'Waiting'.$newWaiting.'dv'.$totaldv.'VA'.$id_vacations_availables);

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Rechazada',
                    'direct_manager_status' => 'Rechazada',
                    'commentary' => $request->commentary
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 0
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $solicitud->user_id)->where('id', $id_vacations_availables)->update([
                    'waiting' => $newWaiting,
                    'dv' => $totaldv
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($solicitud->request_type_id);
                    $ApplicationOwner = User::find($solicitud->user_id);
                    $ApplicationOwner->notify(new RejectRequestBoss(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $request->commentary,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones rechazadas correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
                }

                return back()->with('message', 'Vacaciones rechazadas correctamente');
            }
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Rechazada',
                'direct_manager_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);
            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($solicitud->request_type_id);
                $ApplicationOwner = User::find($solicitud->user_id);
                $ApplicationOwner->notify(new RejectRequestBoss(
                    $ApplicationOwner->name,
                    $emisor->name,
                    $requestType->type,
                    $request->commentary,
                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Solicitud rechazada correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
            }

            return back()->with('message', 'Solicitud rechazada exitosamente.');
        }
    }

    public function RejectPermissionUser(Request $request)
    {

        $user = auth()->user();

        $request->validate([
            'id' => 'required',
            'commentary' => 'required'
        ]);

        $Solicitud = VacationRequest::where('id', $request->id)->first();


        if ($Solicitud->user_id != $user->id) {
            return back()->with('error', 'Solo el creador de la solicitud puede rechazar la solicitud');
        }

        if ($Solicitud->direct_manager_status == 'Rechazada') {
            return back()->with('error', 'Esta solicitud ya fue rechazada por tu jefe directo.');
        }

        if ($request->commentary == null) {
            return back()->with('error', 'Debes indicar el motivo por el cual deseas cancelar la solicitud.');
        }

        if ($Solicitud->request_type_id == 1) {
            if ($Solicitud->direct_manager_status == 'Aprobada' && $Solicitud->rh_status == 'Aprobada') {
                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'commentary' => $request->commentary,
                    'direct_manager_status' => 'Cancelada por el usuario',
                    'rh_status' => 'Cancelada por el usuario'
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 0
                ]);
                return back()->with('message', 'Vacaciones canceladas correctamente; sin embargo, RH debe considerar cuántos días debe regresarte.');
            }

            if (($Solicitud->direct_manager_status == 'Aprobada' && $Solicitud->rh_status == 'Pendiente') || ($Solicitud->direct_manager_status == 'Pendiente' && $Solicitud->rh_status == 'Pendiente')) {
                $fechaActual = Carbon::now();
                $Vacaciones = DB::table('vacations_available_per_users')
                    ->where('users_id', $Solicitud->user_id)
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
                        'waiting' => $vaca->waiting,
                        'days_enjoyed' => $vaca->days_enjoyed,
                        'days_availables' => $vaca->days_availables,
                        'id' => $vaca->id,
                    ];
                }

                $InfoVacaciones = DB::table('vacation_information')->where('id_vacation_request', $request->id)->get();
                $total = count($InfoVacaciones);
                if ($total == 2) {
                    $idOne = (int) $Datos[0]['id'];
                    $idTwo = (int) $Datos[1]['id'];
                    $WaitingOne = $Datos[0]['waiting'];
                    $WaitingTwo = $Datos[1]['waiting'];
                    $dvOne = $Datos[0]['dv'];
                    $dvTwo = $Datos[1]['dv'];

                    $InfoTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                    $InfoOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();
                    $totaldaysOne =  //Si no esta el campo que no lo tome en cuenta
                        $InfoOne->total_days == null ? 0 : $InfoOne->total_days;
                    $newWaiting = $WaitingOne - $totaldaysOne;
                    $totaldvOne = $dvOne + $totaldaysOne;

                    $totaldaysTwo = $InfoTwo->total_days == null ? 0 : $InfoTwo->total_days;
                    $newWaitingTwo = $WaitingTwo - $totaldaysTwo;
                    $totaldvTwo = $dvTwo + $totaldaysTwo;

                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'commentary' => $request->commentary,
                        'direct_manager_status' => 'Cancelada por el usuario',
                        'rh_status' => 'Cancelada por el usuario'
                    ]);

                    DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                        'status' => 0
                    ]);

                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                        'waiting' => $newWaiting,
                        'dv' => $totaldvOne
                    ]);

                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                        'waiting' => $newWaitingTwo,
                        'dv' => $totaldvTwo
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($Solicitud->request_type_id);
                        $boss = User::find($Solicitud->direct_manager_id);
                        $boss->notify(new RejectRequest(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $request->commentary,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Vacaciones canceladas correctamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Vacaciones canceladas correctamente.');
                }

                if ($total == 1) {
                    $VacaInfo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                    $id_vacations_availables = $VacaInfo->id_vacations_availables;
                    $VacacionesAviles = DB::table('vacations_available_per_users')->where('id', $id_vacations_availables)->first();
                    $totaldaysOne = //Si no esta el campo que no lo tome en cuenta
                        $VacaInfo->total_days == null ? 0 : $VacaInfo->total_days;
                    $newWaiting = $VacacionesAviles->waiting - $totaldaysOne;
                    $totaldv = $VacacionesAviles->dv + $totaldaysOne;
                    //dd('totaldevacaciones'.':'.$totaldaysOne.'Waiting'.$newWaiting.'dv'.$totaldv.'VA'.$id_vacations_availables);

                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'commentary' => $request->commentary,
                        'direct_manager_status' => 'Cancelada por el usuario',
                        'rh_status' => 'Cancelada por el usuario'
                    ]);

                    DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                        'status' => 0
                    ]);

                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $id_vacations_availables)->update([
                        'waiting' => $newWaiting,
                        'dv' => $totaldv
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($Solicitud->request_type_id);
                        $boss = User::find($Solicitud->direct_manager_id);
                        $boss->notify(new RejectRequest(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $request->commentary,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Vacaciones canceladas correctamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Vacaciones canceladas correctamente.');
                }
            }
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'commentary' => $request->commentary,
                'direct_manager_status' => 'Cancelada por el usuario',
                'rh_status' => 'Cancelada por el usuario'
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($Solicitud->request_type_id);
                $boss = User::find($Solicitud->direct_manager_id);
                $boss->notify(new RejectRequest(
                    $boss->name,
                    $emisor->name,
                    $requestType->type,
                    $request->commentary,
                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Vacaciones canceladas correctamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
            }
            return back()->with('message', 'Se rechazó la solicitud exitosamente.');
        }
    }

    public function UpdateRequest(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'details' => 'required',
            'reveal_id' => 'required',
            'dates' => 'required',
            'id' => 'required'
        ]);

        $Solicitud = DB::table('vacation_requests')->where('id', $request->id)->first();

        if ($Solicitud->user_id != $user->id) {
            return back()->with('error', 'Solo el dueño de la solicitud la puede editar.');
        }

        if ($request->reveal_id == $user->id) {
            return back()->with('error', 'No puedes ser tú mismo el responsable de tus deberes.');
        }

        $dates = $request->dates;
        $datesArray = json_decode($dates, true);
        $UserEmployee = Employee::where('user_id', $user->id)->value('user_id');
        $company = DB::table('company_employee')->where('employee_id', $UserEmployee)->pluck('company_id');
        if ($company->contains(2)) {
            $DaysJudios = [
                '03-10-2024',
                '04-10-2024',
                '17-10-2024',
                '18-10-2024',
                '24-10-2024',
            ];

            $diasParecidos = [];
            foreach ($datesArray as $date) {
                foreach ($DaysJudios as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Algunos de los días seleccionados no están disponibles para tu solicitud.');
            }
        }

        $DaysFeridos = [
            '18-11-2024',
            '25-12-2024',
            '01-01-2025',
            '03-02-2025',
            '17-10-2025',
            '01-05-2025',
            '16-09-2025',
            '17-09-2025',
            '25-09-2025'
        ];

        $diasParecidosFestivos = [];
        foreach ($datesArray as $date) {
            foreach ($DaysFeridos as $Feriados) {
                if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($Feriados))) {
                    $diasParecidosFestivos[] = $Feriados;
                }
            }
        }

        if (!empty($diasParecidosFestivos)) {
            return back()->with('error', 'Algunos de los días seleccionados son feriados, por lo tanto no puedes solicitarlos.');
        }

        $path = '';
        if ($request->hasFile('archivos')) {
            $filenameWithExt = $request->file('archivos')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('archivos')->clientExtension();
            $fileNameToStore = time() . $filename . '.' . $extension;
            $path = $request->file('archivos')->move('storage/vacation/files/', $fileNameToStore);
        }

        ///VACACIONES
        if ($request->request_type_id == 1) {
            //Solicitud
            $dias = DB::table('vacation_days')
                ->where('vacation_request_id', $Solicitud->id)
                ->pluck('day')
                ->toArray();

            //LO QUE SE OBTIENE DE LA VISTA//
            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [2, 3, 4, 5])
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($dates as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }


            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

            if ($diasTotales == 0) {
                return back()->with('error', 'Debes enviar al menos un día de vacaciones.');
            }


            if ($mesesTranscurridos < 6) {
                return back()->with('error', 'No has cumplido el tiempo suficiente para solicitar vacaciones.');
            }

            $Vacaciones = DB::table('vacations_available_per_users')
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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'id' => $vaca->id,
                ];
            }


            $diasSet = collect($dias)->unique()->sort()->values();
            $datesSet = collect($dates)->unique()->sort()->values();

            // Comparar los conjuntos para encontrar diferencias
            $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
            $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

            if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);

                    $boss->notify(new PermissionRequestUpdate(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Se actualizó correctamente tu solicitud. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Se actualizó correctamente tu solicitud.');
            } else {
                ///VERIFICAR SI LE ALCANZAN LOS DIAS ANTES DE ELIMINAR///
                if (count($Datos) == 1) {
                    $diasneuvos = 0;
                    if (!$missingInDias->isEmpty()) {
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasneuvos = count($registrarArray);
                    }
                    $diaseliminar = 0;
                    if (!$missingInDates->isEmpty()) {
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        $diaseliminar = count($eliminarArray);
                    }

                    $Vacaciones = $Datos[0]['dv'];
                    if ((($Vacaciones + $diaseliminar) - $diasneuvos) < 0) {
                        return back()->with('error', 'Verifica tu disponibilidad de vacaciones');
                    }
                }

                if (count($Datos) > 1) {
                    $diasneuvos = 0;
                    if (!$missingInDias->isEmpty()) {
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasneuvos = count($registrarArray);
                    }
                    $diaseliminar = 0;
                    if (!$missingInDates->isEmpty()) {
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        $diaseliminar = count($eliminarArray);
                    }
                    $Vacaciones = $Datos[0]['dv'] + $Datos[1]['dv'];
                    if ((($Vacaciones + $diaseliminar) - $diasneuvos) < 0) {
                        return back()->with('error', 'Verifica tu disponibilidad de vacaciones');
                    }
                }

                //DÍAS A ELIMINAR
                if (!$missingInDates->isEmpty()) {
                    $eliminar = $missingInDates->implode(', ');
                    $eliminarArray = explode(', ', $eliminar);
                    $dias = count($eliminarArray);

                    $Vacaciones = DB::table('vacations_available_per_users')
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
                            'waiting' => $vaca->waiting,
                            'days_enjoyed' => $vaca->days_enjoyed,
                            'days_availables' => $vaca->days_availables,
                            'id' => $vaca->id,
                        ];
                    }

                    if (count($Datos) > 1) {
                        ///ELIMINEMOS LOS DÍAS///
                        $VacacionesOne = $Datos[0]['dv'];
                        $VacacionesTwo = $Datos[1]['dv'];
                        $WaitingOne = $Datos[0]['waiting'];
                        $WaitingTwo = $Datos[1]['waiting'];
                        $idOne = $Datos[0]['id'];
                        $idTwo = $Datos[1]['id'];

                        $InfoVacas = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->get();
                        $idsPeriodos = [];
                        foreach ($InfoVacas as $InfoVaca) {
                            $idsPeriodos[] = $InfoVaca->id_vacations_availables;
                        }


                        if (count($idsPeriodos) == 2) {
                            if (in_array($idOne, $idsPeriodos)) {
                                $dvTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                                if ($dias <= $dvTwo->total_days && $dvTwo->total_days != 0) {
                                    $NewTotalDays = $dvTwo->total_days - $dias;
                                    $NewWaitingTwo = $WaitingTwo - $dias;
                                    $NewDvTwo = $VacacionesTwo + $dias;
                                    DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                        ->where('id_vacations_availables', $idTwo)->update([
                                            'total_days' => $NewTotalDays,
                                        ]);

                                    DB::table('vacation_requests')->where('id', $request->id)->update([
                                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        'direct_manager_status' => 'Pendiente',
                                    ]);

                                    foreach ($eliminarArray as $eliminar) {
                                        $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                        DB::table('vacation_days')
                                            ->where('id', $idfecha)
                                            ->delete();
                                    }

                                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                                        'waiting' => $NewWaitingTwo,
                                        'dv' => $NewDvTwo
                                    ]);
                                }
                                if ($dias > $dvTwo->total_days) {
                                    $dvOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();
                                    $dvTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                                    if ($dias <= ($dvOne->total_days + $dvTwo->total_days)) {
                                        $faltantotaldaysOne = $dias - $dvTwo->total_days;
                                        $faltantotaldaysTwo = $dias - $faltantotaldaysOne;
                                        //dd($faltantotaldaysOne . ' ' . $faltantotaldaysTwo);
                                        if ($faltantotaldaysTwo <= $dvTwo->total_days && $faltantotaldaysOne <= $dvOne->total_days) {
                                            $NewTotalDaysTwo = $dvTwo->total_days - $faltantotaldaysTwo;
                                            $NewTotalDaysOne = $dvOne->total_days - $faltantotaldaysOne;
                                            $NewWaitingTwo = $WaitingTwo - $faltantotaldaysTwo;
                                            $NewDvTwo = $VacacionesTwo +  $faltantotaldaysTwo;
                                            $NewWaitingOne = $WaitingOne - $faltantotaldaysOne;
                                            $NewDvOne = $VacacionesOne + $faltantotaldaysOne;


                                            DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                                ->where('id_vacations_availables', $idTwo)->update([
                                                    'total_days' => $NewTotalDaysTwo,
                                                ]);

                                            DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                                ->where('id_vacations_availables', $idOne)->update([
                                                    'total_days' => $NewTotalDaysOne,
                                                ]);

                                            DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                                                'waiting' => $NewWaitingTwo,
                                                'dv' => $NewDvTwo
                                            ]);
                                            DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                                                'waiting' => $NewWaitingOne,
                                                'dv' => $NewDvOne
                                            ]);

                                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                                'direct_manager_status' => 'Pendiente',
                                            ]);

                                            foreach ($eliminarArray as $eliminar) {
                                                $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                                DB::table('vacation_days')
                                                    ->where('id', $idfecha)
                                                    ->delete();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (count($idsPeriodos) == 1) {
                            $infosoli = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                            if ($dias <= $infosoli->total_days) {
                                $vacacionesdisponibles = DB::table('vacations_available_per_users')->where('id', $infosoli->id_vacations_availables)->first();
                                $NewTotalDays = $infosoli->total_days - $dias;
                                $NewWaitingTwo = $vacacionesdisponibles->waiting - $dias;
                                $NewDvTwo = $vacacionesdisponibles->dv + $dias;
                                DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                    ->where('id_vacations_availables', $infosoli->id_vacations_availables)->update([
                                        'total_days' => $NewTotalDays,
                                    ]);

                                DB::table('vacation_requests')->where('id', $request->id)->update([
                                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                                    'direct_manager_status' => 'Pendiente',
                                ]);

                                foreach ($eliminarArray as $eliminar) {
                                    $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                    DB::table('vacation_days')
                                        ->where('id', $idfecha)
                                        ->delete();
                                }

                                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $infosoli->id_vacations_availables)->update([
                                    'waiting' => $NewWaitingTwo,
                                    'dv' => $NewDvTwo
                                ]);
                            }
                        }
                    } elseif (count($Datos) == 1) {
                        $VacacionesOne = $Datos[0]['dv'];
                        $WaitingOne = $Datos[0]['waiting'];
                        $idOne = $Datos[0]['id'];
                        $dvOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();
                        if ($dias <= $dvOne->total_days && $dvOne->total_days != 0) {
                            $NewTotalDays = $dvOne->total_days - $dias;
                            $NewWaitingTwo = $WaitingOne - $dias;
                            $NewDvTwo = $VacacionesOne + $dias;
                            DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                ->where('id_vacations_availables', $idOne)->update([
                                    'total_days' => $NewTotalDays,
                                ]);

                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                'direct_manager_status' => 'Pendiente',
                            ]);

                            foreach ($eliminarArray as $eliminar) {
                                $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                DB::table('vacation_days')
                                    ->where('id', $idfecha)
                                    ->delete();
                            }

                            DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                                'waiting' => $NewWaitingTwo,
                                'dv' => $NewDvTwo
                            ]);
                        }
                    }
                }

                if ($diasneuvos == 0) {
                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Se eliminarón los días de vacaciones exitosamente. Sin embargo, no sepudo enviar el correo electrónico.');
                    }
                }

                //NUEVOS DÍAS//
                if (!$missingInDias->isEmpty()) {
                    $newVacaciones = DB::table('vacations_available_per_users')
                        ->where('users_id', $user->id)
                        ->where('cutoff_date', '>=', $fechaActual)
                        ->orderBy('cutoff_date', 'asc')
                        ->get();

                    $Datosnew = [];
                    foreach ($newVacaciones as $vaca) {
                        $Datosnew[] = [
                            'dv' => $vaca->dv,
                            'cutoff_date' => $vaca->cutoff_date,
                            'period' => $vaca->period,
                            'days_enjoyed' => $vaca->days_enjoyed,
                            'waiting' => $vaca->waiting,
                            'days_enjoyed' => $vaca->days_enjoyed,
                            'days_availables' => $vaca->days_availables,
                            'id' => $vaca->id
                        ];
                    }

                    ///Estos días son nuevos en el arreglo, se deben agregar a la solicitud:
                    $registrar = $missingInDias->implode(', ');
                    $registrarArray = explode(', ', $registrar);
                    $diasTotales = count($registrarArray);

                    if (count($Datosnew) > 1) {
                        $VacacionesOne = $Datosnew[0]['dv'];
                        $VacacionesTwo = $Datosnew[1]['dv'];
                        $PeriodoOne = $Datosnew[0]['period'];
                        $PeriodoTwo = $Datosnew[1]['period'];
                        $WaitingOne = $Datosnew[0]['waiting'];
                        $WaitingTwo = $Datosnew[1]['waiting'];
                        $idOne = $Datosnew[0]['id'];
                        $idTwo = $Datosnew[1]['id'];

                        $InfoVacas = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->get();
                        $idsPeriodos = [];
                        foreach ($InfoVacas as $InfoVaca) {
                            $idsPeriodos[] = $InfoVaca->id_vacations_availables;
                        }
                        if (count($idsPeriodos) == 2) {
                            if (in_array($idOne, $idsPeriodos)) {
                                $dvTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                                $dvOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();
                                if ($diasTotales <= $VacacionesOne) {
                                    $RestaDv = $VacacionesOne - $diasTotales;
                                    ////ReservaWaiting nos va ayudar para cuando nieguen las vacaciones, las regresemos al periodo actual///
                                    $NewWaitingOne = $WaitingOne + $diasTotales;
                                    $NewDvOne = $VacacionesOne - $diasTotales;
                                    $NewTotalDaysOne = $dvOne->total_days + $diasTotales;

                                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                                        'waiting' => $NewWaitingOne,
                                        'dv' => $NewDvOne
                                    ]);

                                    DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                        ->where('id_vacations_availables', $idOne)->update([
                                            'total_days' => $NewTotalDaysOne,
                                        ]);

                                    DB::table('vacation_requests')->where('id', $request->id)->update([
                                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        'direct_manager_status' => 'Pendiente',
                                    ]);


                                    foreach ($registrarArray as $dia) {
                                        VacationDays::create([
                                            'day' => $dia,
                                            'vacation_request_id' => $Solicitud->id,
                                            'status' => 0,
                                        ]);
                                    }
                                    try {
                                        $emisor = User::find($user->id);
                                        $requestType = RequestType::find($request->request_type_id);
                                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                            ->pluck('day')
                                            ->implode(', ');
                                        $boss = User::find($Ingreso->jefe_directo_id);
                                        $boss->notify(new PermissionRequestUpdate(
                                            $boss->name,
                                            $emisor->name,
                                            $requestType->type,
                                            $days,
                                            $request->details,
                                        ));
                                    } catch (\Exception $e) {
                                        return back()->with('warning', 'Vacaciones actualizadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                    }
                                    return back()->with('message', 'Vacaciones actualizadas exitosamente.');
                                }

                                $totalVacaciones = $VacacionesOne + $VacacionesTwo;
                                //LAS VACACIONES ESTAN EN AMBOS PERIODOS DE VACACIONES//
                                if ($diasTotales > $VacacionesOne) {
                                    if ($diasTotales <= $totalVacaciones && $totalVacaciones != 0) {
                                        ////VERIFICAMOS QUE EN EL PRIMER PERIODO HAYA VACACIONES
                                        if ($VacacionesOne > 0) {
                                            $FaltanVacacionesOne = $diasTotales - $VacacionesOne;
                                            $DispoVacaOne = $diasTotales - $FaltanVacacionesOne;
                                            if ($FaltanVacacionesOne <= $VacacionesTwo && $DispoVacaOne == $VacacionesOne) {
                                                $ReservaWaitingOne = $WaitingOne + $DispoVacaOne;
                                                $RestaDvOne = $VacacionesOne - $DispoVacaOne;
                                                $ReservaWaitingTwo = $WaitingTwo + $FaltanVacacionesOne;
                                                $RestaDvTwo = $VacacionesTwo - $FaltanVacacionesOne;
                                                $NewTotalDaysOne = $dvOne->total_days + $DispoVacaOne;
                                                $NewTotalDaysTwo = $dvTwo->total_days + $FaltanVacacionesOne;

                                                DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoOne)->update([
                                                    'waiting' => $ReservaWaitingOne,
                                                    'dv' => $RestaDvOne
                                                ]);

                                                DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoTwo)->update([
                                                    'waiting' => $ReservaWaitingTwo,
                                                    'dv' => $RestaDvTwo
                                                ]);


                                                DB::table('vacation_requests')->where('id', $request->id)->update([
                                                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                                                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                                                    'direct_manager_status' => 'Pendiente',
                                                ]);


                                                foreach ($registrarArray as $dia) {
                                                    VacationDays::create([
                                                        'day' => $dia,
                                                        'vacation_request_id' => $Solicitud->id,
                                                        'status' => 0,
                                                    ]);
                                                }

                                                DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                                    ->where('id_vacations_availables', $idOne)->update([
                                                        'total_days' => $NewTotalDaysOne,
                                                    ]);

                                                DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                                    ->where('id_vacations_availables', $idTwo)->update([
                                                        'total_days' => $NewTotalDaysTwo,
                                                    ]);

                                                try {
                                                    $emisor = User::find($user->id);
                                                    $requestType = RequestType::find($request->request_type_id);
                                                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                                        ->pluck('day')
                                                        ->implode(', ');
                                                    $boss = User::find($Ingreso->jefe_directo_id);
                                                    $boss->notify(new PermissionRequestUpdate(
                                                        $boss->name,
                                                        $emisor->name,
                                                        $requestType->type,
                                                        $days,
                                                        $request->details,
                                                    ));
                                                } catch (\Exception $e) {
                                                    return back()->with('warning', 'Vacaciones modificadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                                }

                                                return back()->with('message', 'Vacaciones modificadas exitosamente.');
                                            } else {
                                                return back()->with('error', 'No cuentas con las vacaciones suficientes.');
                                            }
                                        }

                                        if ($VacacionesOne == 0 && $VacacionesTwo > 0 && $VacacionesTwo != 0) {
                                            $RestadvTwo = $VacacionesTwo - $diasTotales;
                                            $ReservaWaitingTwo = $WaitingTwo + $diasTotales;
                                            $NewTotalDaysTwo = $dvTwo->total_days + $diasTotales;
                                            DB::table('vacations_available_per_users')->where('users_id', $user->id)->where('period', $PeriodoTwo)->update([
                                                'waiting' => $ReservaWaitingTwo,
                                                'dv' => $RestadvTwo
                                            ]);

                                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                                'direct_manager_status' => 'Pendiente',
                                            ]);

                                            foreach ($registrarArray as $dia) {
                                                VacationDays::create([
                                                    'day' => $dia,
                                                    'vacation_request_id' => $Solicitud->id,
                                                    'status' => 0,
                                                ]);
                                            }

                                            DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                                ->where('id_vacations_availables', $idTwo)->update([
                                                    'total_days' => $NewTotalDaysTwo,
                                                ]);

                                            try {
                                                $emisor = User::find($user->id);
                                                $requestType = RequestType::find($request->request_type_id);
                                                $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                                    ->pluck('day')
                                                    ->implode(', ');
                                                $boss = User::find($Ingreso->jefe_directo_id);
                                                $boss->notify(new PermissionRequestUpdate(
                                                    $boss->name,
                                                    $emisor->name,
                                                    $requestType->type,
                                                    $days,
                                                    $request->details,
                                                ));
                                            } catch (\Exception $e) {
                                                return back()->with('warning', 'Vacaciones modificadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                            }

                                            return back()->with('message', 'Vacaciones modificadas exitosamente.');
                                        } else {
                                            return back()->with('error', 'No cuentas con las vacaciones suficientes. 2');
                                        }
                                    } else {
                                        return back()->with('error', 'No cuentas con las vacaciones suficientes.');
                                    }
                                }
                            }
                        }
                        if (count($idsPeriodos) == 1) {
                            $infosoli = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                            $Periodo = $infosoli->id_vacations_availables;
                            $InfoVacaciones = DB::table('vacations_available_per_users')->where('id', $Periodo)->first();
                            if ($diasTotales <= $InfoVacaciones->dv) {
                                $RestaDv = $InfoVacaciones->dv - $diasTotales;
                                ////ReservaWaiting nos va ayudar para cuando nieguen las vacaciones, las regresemos al periodo actual///
                                $NewWaitingOne = $InfoVacaciones->waiting + $diasTotales;
                                $NewTotalDaysOne = $infosoli->total_days + $diasTotales;

                                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $InfoVacaciones->id)->update([
                                    'waiting' => $NewWaitingOne,
                                    'dv' => $RestaDv
                                ]);

                                DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                    ->where('id_vacations_availables', $infosoli->id_vacations_availables)->update([
                                        'total_days' => $NewTotalDaysOne,
                                    ]);

                                DB::table('vacation_requests')->where('id', $request->id)->update([
                                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                                    'direct_manager_status' => 'Pendiente',
                                ]);


                                foreach ($registrarArray as $dia) {
                                    VacationDays::create([
                                        'day' => $dia,
                                        'vacation_request_id' => $Solicitud->id,
                                        'status' => 0,
                                    ]);
                                }
                                try {
                                    $emisor = User::find($user->id);
                                    $requestType = RequestType::find($request->request_type_id);
                                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                        ->pluck('day')
                                        ->implode(', ');
                                    $boss = User::find($Ingreso->jefe_directo_id);
                                    $boss->notify(new PermissionRequestUpdate(
                                        $boss->name,
                                        $emisor->name,
                                        $requestType->type,
                                        $days,
                                        $request->details,
                                    ));
                                } catch (\Exception $e) {
                                    return back()->with('warning', 'Vacaciones actualizadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                }
                                return back()->with('message', 'Vacaciones actualizadas exitosamente.');
                            } elseif ($diasTotales > $InfoVacaciones->dv) {
                                if ($diasTotales <= $VacacionesOne && $VacacionesTwo == 0) {
                                    $NewWaitingOne = $WaitingOne + $diasTotales;
                                    $RestaDv = $VacacionesOne - $diasTotales;

                                    VacationInformation::create([
                                        'total_days' => $diasTotales,
                                        'id_vacations_availables' => $idOne,
                                        'id_vacation_request' => $Solicitud->id
                                    ]);

                                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                                        'waiting' => $NewWaitingOne,
                                        'dv' => $RestaDv
                                    ]);
                                    DB::table('vacation_requests')->where('id', $request->id)->update([
                                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        'direct_manager_status' => 'Pendiente',
                                    ]);

                                    foreach ($registrarArray as $dia) {
                                        VacationDays::create([
                                            'day' => $dia,
                                            'vacation_request_id' => $Solicitud->id,
                                            'status' => 0,
                                        ]);
                                    }
                                    try {
                                        $emisor = User::find($user->id);
                                        $requestType = RequestType::find($request->request_type_id);
                                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                            ->pluck('day')
                                            ->implode(', ');
                                        $boss = User::find($Ingreso->jefe_directo_id);
                                        $boss->notify(new PermissionRequestUpdate(
                                            $boss->name,
                                            $emisor->name,
                                            $requestType->type,
                                            $days,
                                            $request->details,
                                        ));
                                    } catch (\Exception $e) {
                                        return back()->with('warning', 'Vacaciones actualizadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                    }
                                    return back()->with('message', 'Vacaciones actualizadas correctamente.');
                                } elseif ($diasTotales <= $VacacionesTwo && $VacacionesOne == 0) {
                                    $NewWaitingTwo = $WaitingTwo + $diasTotales;
                                    $RestaDv = $VacacionesTwo - $diasTotales;

                                    VacationInformation::create([
                                        'total_days' => $diasTotales,
                                        'id_vacations_availables' => $idTwo,
                                        'id_vacation_request' => $Solicitud->id
                                    ]);

                                    DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                                        'waiting' => $NewWaitingTwo,
                                        'dv' => $RestaDv
                                    ]);
                                    DB::table('vacation_requests')->where('id', $request->id)->update([
                                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        'direct_manager_status' => 'Pendiente',
                                    ]);

                                    foreach ($registrarArray as $dia) {
                                        VacationDays::create([
                                            'day' => $dia,
                                            'vacation_request_id' => $Solicitud->id,
                                            'status' => 0,
                                        ]);
                                    }
                                    try {
                                        $emisor = User::find($user->id);
                                        $requestType = RequestType::find($request->request_type_id);
                                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                            ->pluck('day')
                                            ->implode(', ');
                                        $boss = User::find($Ingreso->jefe_directo_id);
                                        $boss->notify(new PermissionRequestUpdate(
                                            $boss->name,
                                            $emisor->name,
                                            $requestType->type,
                                            $days,
                                            $request->details,
                                        ));
                                    } catch (\Exception $e) {
                                        return back()->with('warning', 'Vacaciones actualizadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                                    }
                                    return back()->with('message', 'Vacaciones actualizadas correctamente.');
                                } else {
                                    return back()->with('error', 'Verifica tu información de días.');
                                }
                            } else {

                                return back()->with('error', 'No tienes suficientes días.');
                            }
                        }
                    }

                    if (count($Datosnew) == 1) {
                        $infosoli = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                        $Periodo = $infosoli->id_vacations_availables;
                        $InfoVacaciones = DB::table('vacations_available_per_users')->where('id', $Periodo)->first();
                        if ($diasTotales <= $InfoVacaciones->dv) {
                            $RestaDv = $InfoVacaciones->dv - $diasTotales;
                            ////ReservaWaiting nos va ayudar para cuando nieguen las vacaciones, las regresemos al periodo actual///
                            $NewWaitingOne = $InfoVacaciones->waiting + $diasTotales;
                            $NewTotalDaysOne = $infosoli->total_days + $diasTotales;

                            DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $InfoVacaciones->id)->update([
                                'waiting' => $NewWaitingOne,
                                'dv' => $RestaDv
                            ]);

                            DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)
                                ->where('id_vacations_availables', $infosoli->id_vacations_availables)->update([
                                    'total_days' => $NewTotalDaysOne,
                                ]);

                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                'direct_manager_status' => 'Pendiente',
                            ]);


                            foreach ($registrarArray as $dia) {
                                VacationDays::create([
                                    'day' => $dia,
                                    'vacation_request_id' => $Solicitud->id,
                                    'status' => 0,
                                ]);
                            }

                            try {
                                $emisor = User::find($user->id);
                                $requestType = RequestType::find($request->request_type_id);
                                $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                    ->pluck('day')
                                    ->implode(', ');
                                $boss = User::find($Ingreso->jefe_directo_id);
                                $boss->notify(new PermissionRequestUpdate(
                                    $boss->name,
                                    $emisor->name,
                                    $requestType->type,
                                    $days,
                                    $request->details,
                                ));
                            } catch (\Exception $e) {
                                return back()->with('warning', 'Vacaciones actualizadas exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                            }

                            return back()->with('message', 'Vacaciones actualizadas exitosamente.');
                        } else {
                            return back()->with('error', 'No tienes suficientes días.');
                        }
                    }
                }

                return back()->with('message', 'Vacaciones actualizadas');
            }
        }

        ///AUSENCIAS
        if ($request->request_type_id == 2) {
            $dias = DB::table('vacation_days')
                ->where('vacation_request_id', $Solicitud->id)
                ->pluck('day')
                ->toArray();

            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [1, 3, 4, 5])
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($dates as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            if ($diasTotales > 1) {
                return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
            }

            if ($diasTotales == 0) {
                return back()->with('error', 'Debes ingresar el día en que saldrás temprano de la jornada.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();

            if ($request->ausenciaTipo == 'retardo') {
                if ($diasTotales > 1) {
                    return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
                }

                $hora12PM = Carbon::today()->setHour(12)->setMinute(00);
                $totalMinutos = $hora12PM->hour * 60 + $hora12PM->minute;

                $Hora8AM = Carbon::today()->setHour(8)->setMinute(00);
                $totalMinutos8AM = $Hora8AM->hour * 60 + $Hora8AM->minute;


                $retardo = $request->hora_regreso;
                $Retardo = Carbon::parse($retardo);
                $totalMinutosRetardo = $Retardo->hour * 60 + $Retardo->minute;

                if ($totalMinutosRetardo > $totalMinutos) {
                    return back()->with('error', 'La hora del retardo no puede ser después de las 12PM.');
                }

                if ($totalMinutosRetardo < $totalMinutos8AM) {
                    return back()->with('error', 'Verifica tu hora de ingreso a la empresa.');
                }

                $currentMonth = now()->format('Y-m');
                $firstDayOfMonth = now()->startOfMonth();
                $today = now(); // Día actual

                // Contar solicitudes de retardo
                $retardoCount = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 2)
                    ->whereNotIn('direct_manager_status', ['Pendiente', 'Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Pendiente', 'Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', [$firstDayOfMonth, $today])
                    ->whereJsonContains('more_information', ['Tipo_de_ausencia' => 'Retardo'])
                    ->count();

                if ($retardoCount >= 3) {
                    return back()->with('error', 'Solo tienes derecho a tres retardos al mes');
                }

                // Convertir ambos arrays a conjuntos (sets) para la comparación
                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    $more_information[] = [
                        'Tipo_de_ausencia' => $request->ausenciaTipo == 'retardo' ? 'Retardo' : 'No encontro el valor',
                        'value_type' => $request->ausenciaTipo,
                    ];
                    $moreinformation = json_encode($more_information);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);
                    DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                        'end' => $retardo,
                        'start' => null,
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Solicitud actualizada exitosamente.');
                } else {
                    if (!$missingInDias->isEmpty()) {
                        ///Dias que no vienen en el arreglo
                        $more_information[] = [
                            'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_antes' ? 'Salir antes' : 'No encontro el valor',
                            'value_type' => $request->ausenciaTipo,
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);
                        foreach ($dates as $dia) {
                            VacationDays::create([
                                'day' => $dia,
                                'end' => $retardo,
                                'vacation_request_id' => $request->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')
                                ->where('id', $idfecha)
                                ->delete();
                        }
                    }

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Solicitud actualizada correctamente.');
                }
            }

            if ($request->ausenciaTipo == 'salida_antes') {
                $hora13PM = Carbon::today()->setHour(13)->setMinute(00);
                $totalMinutos = $hora13PM->hour * 60 + $hora13PM->minute;

                $start = $request->hora_salida;
                $salidaAntes = Carbon::parse($start);
                $totalMinutosRetardo = $salidaAntes->hour * 60 + $salidaAntes->minute;

                if ($totalMinutosRetardo < $totalMinutos) {
                    return back()->with('error', 'No puedes salir antes de las 13PM.');
                }

                // Convertir ambos arrays a conjuntos (sets) para la comparación
                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    $more_information[] = [
                        'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_antes' ? 'Salir antes' : 'No encontro el valor',
                        'value_type' => $request->ausenciaTipo,
                    ];
                    $moreinformation = json_encode($more_information);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);

                    DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                        'start' => $start,
                        'end' => null,
                    ]);
                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                } else {
                    if (!$missingInDias->isEmpty()) {
                        ///Dias que no vienen en el arreglo
                        $more_information[] = [
                            'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_antes' ? 'Salir antes' : 'No encontro el valor',
                            'value_type' => $request->ausenciaTipo,
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);

                        foreach ($dates as $dia) {
                            VacationDays::create([
                                'day' => $dia,
                                'start' => $start,
                                'vacation_request_id' => $request->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')
                                ->where('id', $idfecha)
                                ->delete();
                        }
                    }
                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                }
                return back()->with('message', 'Se actualizó correctamente la solicitud.');
            }

            if ($request->ausenciaTipo == 'salida_durante') {
                $start = $request->hora_salida;
                $end = $request->hora_regreso;

                $hora1Carbon = Carbon::createFromFormat('H:i', $start);
                $hora2Carbon = Carbon::createFromFormat('H:i', $end);

                $hora4 = Carbon::today()->setHour(4)->setMinute(00);
                $totalMinutos = $hora4->hour * 60 + $hora4->minute;

                $hora8 = Carbon::today()->setHour(8)->setMinute(00);
                $total8 = $hora8->hour * 60 + $hora8->minute;

                $hora17 = Carbon::today()->setHour(17)->setMinute(00);
                $total17 = $hora17->hour * 60 + $hora17->minute;

                $start = $request->hora_salida;
                $salidaAntes = Carbon::parse($start);
                $totalSalida = $salidaAntes->hour * 60 + $salidaAntes->minute;

                $end = $request->hora_regreso;
                $salidaRegreso = Carbon::parse($end);
                $totalRegreso = $salidaRegreso->hour * 60 + $salidaRegreso->minute;

                $DiferenciaMinutos = $totalRegreso - $totalSalida;

                if ($DiferenciaMinutos > $totalMinutos) {
                    return back()->with('error', 'No puedes irte de tus labores más de cuatro horas.');
                }

                if ($totalSalida < $total8 || $totalSalida > $total17 || $totalRegreso > $total17) {
                    return back()->with('error', 'Verifica tu información de salida y regreso.');
                }

                if ($totalSalida == $totalRegreso) {
                    return back()->with('error', 'La hora de salida no puede ser la misma que la de regreso');
                }

                // Convertir ambos arrays a conjuntos (sets) para la comparación
                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {

                    // Hora dentro del rango permitido
                    $more_information[] = [
                        'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_durante' ? 'Salir durante la jornada' : 'No encontro el valor',
                        'value_type' => $request->ausenciaTipo,
                    ];
                    $moreinformation = json_encode($more_information);

                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);

                    DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                        'start' => $hora1Carbon,
                        'end' => $hora2Carbon,
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Solicitud actualizada correctamente.');
                } else {
                    if (!$missingInDias->isEmpty()) {
                        $more_information[] = [
                            'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_durante' ? 'Salir durante la jornada' : 'No encontro el valor',
                            'value_type' => $request->ausenciaTipo,
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);

                        foreach ($dates as $dia) {
                            VacationDays::create([
                                'day' => $dia,
                                'start' => $hora1Carbon,
                                'end' => $hora2Carbon,
                                'vacation_request_id' => $request->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')
                                ->where('id', $idfecha)
                                ->delete();
                        }
                    }
                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                }
                return back()->with('message', 'Se actualizó correctamente tu solicitud.');
            }
        }

        ///PATERNIDAD
        if ($request->request_type_id == 3) {
            $dias = DB::table('vacation_days')
                ->where('vacation_request_id', $Solicitud->id)
                ->pluck('day')
                ->toArray();  // Convertir a array para facilitar la comparación

            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [1, 2, 4, 5])
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($dates as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            if ($diasTotales > 5) {
                return back()->with('error', 'Solo puedes tomar cinco días.');
            }

            if ($Solicitud->file == null) {
                if ($path == null) {
                    return back()->with('error', 'Ingresa tu justificante; de lo contrario, no podrás continuar con el proceso.');
                }
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();

            // Convertir ambos arrays a conjuntos (sets) para la comparación
            $diasSet = collect($dias)->unique()->sort()->values();
            $datesSet = collect($dates)->unique()->sort()->values();

            // Comparar los conjuntos para encontrar diferencias
            $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
            $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

            if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);
                    $boss->notify(new PermissionRequestUpdate(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            } else {
                if (!$missingInDias->isEmpty()) {
                    $registrar = $missingInDias->implode(', ');
                    $registrarArray = explode(', ', $registrar);
                    $diasTotales = count($registrarArray);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'direct_manager_status' => 'Pendiente',
                    ]);

                    foreach ($registrarArray as $registro) {
                        VacationDays::create([
                            'day' => $registro,
                            'vacation_request_id' => $Solicitud->id,
                            'status' => 0,
                        ]);
                    }
                }
                if (!$missingInDates->isEmpty()) {
                    ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                    $eliminar = $missingInDates->implode(', ');
                    $eliminarArray = explode(', ', $eliminar);
                    foreach ($eliminarArray as $eliminar) {
                        $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                        DB::table('vacation_days')
                            ->where('id', $idfecha)
                            ->delete();
                    }
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'direct_manager_status' => 'Pendiente',
                    ]);
                }

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);
                    $boss->notify(new PermissionRequestUpdate(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            }
        }

        ///INCAPACIDAD
        if ($request->request_type_id == 4) {
            $dias = DB::table('vacation_days')
                ->where('vacation_request_id', $Solicitud->id)
                ->pluck('day')
                ->toArray();  // Convertir a array para facilitar la comparación

            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [1, 2, 3, 5])
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($dates as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            if ($diasTotales == 0) {
                return back()->with('error', 'Debes ingresar al menos un día.');
            }

            if ($Solicitud->file == null) {
                if ($path == null) {
                    return back()->with('error', 'Ingresa tu justificante; de lo contrario, no podrás continuar con el proceso.');
                }
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();

            // Convertir ambos arrays a conjuntos (sets) para la comparación
            $diasSet = collect($dias)->unique()->sort()->values();
            $datesSet = collect($dates)->unique()->sort()->values();

            // Comparar los conjuntos para encontrar diferencias
            $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
            $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

            if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                ]);
                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);
                    $boss->notify(new PermissionRequestUpdate(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Solicitud actualizada correctamente.');
            } else {
                if (!$missingInDias->isEmpty()) {
                    $registrar = $missingInDias->implode(', ');
                    $registrarArray = explode(', ', $registrar);
                    $diasTotales = count($registrarArray);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'direct_manager_status' => 'Pendiente',
                    ]);

                    foreach ($registrarArray as $registro) {
                        VacationDays::create([
                            'day' => $registro,
                            'vacation_request_id' => $Solicitud->id,
                            'status' => 0,
                        ]);
                    }
                }
                if (!$missingInDates->isEmpty()) {
                    ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                    $eliminar = $missingInDates->implode(', ');
                    $eliminarArray = explode(', ', $eliminar);
                    foreach ($eliminarArray as $eliminar) {
                        $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                        DB::table('vacation_days')
                            ->where('id', $idfecha)
                            ->delete();
                    }
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'direct_manager_status' => 'Pendiente',
                    ]);
                }
                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($request->request_type_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $boss = User::find($Ingreso->jefe_directo_id);
                    $boss->notify(new PermissionRequestUpdate(
                        $boss->name,
                        $emisor->name,
                        $requestType->type,
                        $days,
                        $request->details,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            }
        }

        ///PERMISOS ESPECIALES
        if ($request->request_type_id == 5) {
            $dias = DB::table('vacation_days')
                ->where('vacation_request_id', $Solicitud->id)
                ->pluck('day')
                ->toArray();

            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);
            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [1, 2, 3, 4])
                ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                ->get();

            $diasVacaciones = [];
            foreach ($vacaciones as $diasvacaciones) {
                $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                foreach ($Days as $Day) {
                    $diasVacaciones[] = $Day->day;
                }
            }
            usort($diasVacaciones, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $diasParecidos = [];
            foreach ($dates as $date) {
                foreach ($diasVacaciones as $vacationDate) {
                    if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                        $diasParecidos[] = $vacationDate;
                    }
                }
            }

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();

            if ($request->Permiso == 'Fallecimiento de un familiar') {
                if ($diasTotales > 3) {
                    return back()->with('error', 'Solo tienes derecho a tomar tres días.');
                }

                // Convertir ambos arrays a conjuntos (sets) para la comparación
                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    $more_information[] = [
                        'Tipo_de_permiso_especial' => $request->Permiso,
                        'familiar_finado' => $request->familiar
                    ];
                    $moreinformation = json_encode($more_information);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Solicitud actualizada correctamente.');
                } else {
                    if (!$missingInDias->isEmpty()) {
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasTotales = count($registrarArray);

                        $more_information[] = [
                            'Tipo_de_permiso_especial' => $request->Permiso,
                            'familiar_finado' => $request->familiar
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);

                        foreach ($registrarArray as $registro) {
                            VacationDays::create([
                                'day' => $registro,
                                'vacation_request_id' => $Solicitud->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        $more_information[] = [
                            'Tipo_de_permiso_especial' => $request->Permiso,
                            'familiar_finado' => $request->familiar
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);

                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')->where('id', $idfecha)->delete();
                        }
                    }
                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Solicitud actualizada correctamente.');
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            }

            if ($request->Permiso == 'Matrimonio del colaborador') {
                if ($diasTotales > 5) {
                    return back()->with('error', 'Solo tienes derecho a tomar cinco días.');
                }

                if ($diasTotales <= 5) {
                    // Convertir ambos arrays a conjuntos (sets) para la comparación
                    $diasSet = collect($dias)->unique()->sort()->values();
                    $datesSet = collect($dates)->unique()->sort()->values();

                    // Comparar los conjuntos para encontrar diferencias
                    $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                    $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                    if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                        $more_information[] = [
                            'Tipo_de_permiso_especial' => $request->Permiso
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                        ]);
                        try {
                            $emisor = User::find($user->id);
                            $requestType = RequestType::find($request->request_type_id);
                            $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                ->pluck('day')
                                ->implode(', ');
                            $boss = User::find($Ingreso->jefe_directo_id);
                            $boss->notify(new PermissionRequestUpdate(
                                $boss->name,
                                $emisor->name,
                                $requestType->type,
                                $days,
                                $request->details,
                            ));
                        } catch (\Exception $e) {
                            return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                        }
                        return back()->with('message', 'Solicitud actualizada correctamente.');
                    } else {
                        if (!$missingInDias->isEmpty()) {
                            $registrar = $missingInDias->implode(', ');
                            $registrarArray = explode(', ', $registrar);
                            $diasTotales = count($registrarArray);

                            $more_information[] = [
                                'Tipo_de_permiso_especial' => $request->Permiso
                            ];
                            $moreinformation = json_encode($more_information);
                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                'more_information' => $moreinformation,
                                'direct_manager_status' => 'Pendiente',
                            ]);

                            foreach ($registrarArray as $registro) {
                                VacationDays::create([
                                    'day' => $registro,
                                    'vacation_request_id' => $Solicitud->id,
                                    'status' => 0,
                                ]);
                            }
                        }
                        if (!$missingInDates->isEmpty()) {
                            ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                            $eliminar = $missingInDates->implode(', ');
                            $eliminarArray = explode(', ', $eliminar);
                            $more_information[] = [
                                'Tipo_de_permiso_especial' => $request->Permiso
                            ];
                            $moreinformation = json_encode($more_information);
                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                'more_information' => $moreinformation,
                                'direct_manager_status' => 'Pendiente',
                            ]);

                            foreach ($eliminarArray as $eliminar) {
                                $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                DB::table('vacation_days')->where('id', $idfecha)->delete();
                            }
                        }

                        try {
                            $emisor = User::find($user->id);
                            $requestType = RequestType::find($request->request_type_id);
                            $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                                ->pluck('day')
                                ->implode(', ');
                            $boss = User::find($Ingreso->jefe_directo_id);
                            $boss->notify(new PermissionRequestUpdate(
                                $boss->name,
                                $emisor->name,
                                $requestType->type,
                                $days,
                                $request->details,
                            ));
                        } catch (\Exception $e) {
                            return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                        }

                        return back()->with('message', 'Actualización exitosa.');
                    }
                }
            }

            if ($request->Permiso == 'Motivos académicos/escolares') {
                if ($diasTotales == 0) {
                    return back()->with('error', 'Debes ingresar el día en que faltarás a tus labores.');
                }

                if ($diasTotales > 1) {
                    return back()->with('error', 'Solo puedes tomar un día a la vez.');
                }

                if ($request->Posicion == 'colaborador') {
                    if ($Solicitud->file == null) {
                        if ($path == null) {
                            return back()->with('error', 'Ingresa tu justificante; de lo contrario, no podrás continuar con el proceso.');
                        }
                    }
                }

                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    $more_information[] = [
                        'Tipo_de_permiso_especial' => $request->Permiso,
                        'El_permiso_involucra_a' => $request->Posicion
                    ];
                    $moreinformation = json_encode($more_information);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }

                    return back()->with('message', 'Solicitud actualizada correctamente.');
                } else {
                    if (!$missingInDias->isEmpty()) {
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasTotales = count($registrarArray);
                        $more_information[] = [
                            'Tipo_de_permiso_especial' => $request->Permiso,
                            'El_permiso_involucra_a' => $request->Posicion
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);
                        foreach ($registrarArray as $dia) {
                            VacationDays::create([
                                'day' => $dia,
                                'vacation_request_id' => $Solicitud->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')->where('id', $idfecha)->delete();
                        }
                    }

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Se actualizó correctamente la solicitud.');
                }
            }

            if ($request->Permiso == 'Asuntos personales') {
                ///VACACIONES PENDIENTES O APROBADAS///
                //dd($request);
                $vacaciones = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->whereIn('request_type_id', [5])
                    ->whereNotIn('direct_manager_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Verificada RH', 'Cancelada', 'Rechazada', 'Cancelada por el usuario'])
                    ->get();

                $diasVacaciones = [];
                foreach ($vacaciones as $diasvacaciones) {
                    $Days = VacationDays::where('vacation_request_id', $diasvacaciones->id)->get();
                    foreach ($Days as $Day) {
                        $diasVacaciones[] = $Day->day;
                    }
                }
                usort($diasVacaciones, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });

                $diasParecidos = [];
                foreach ($dates as $date) {
                    foreach ($diasVacaciones as $vacationDate) {
                        if (date('Y-m-d', strtotime($date)) === date('Y-m-d', strtotime($vacationDate))) {
                            $diasParecidos[] = $vacationDate;
                        }
                    }
                }
                if (!empty($diasParecidos)) {
                    return back()->with('error', 'Verifica que no tengas permisos especiales en status "Pendiente".');
                }

                $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
                $fechaIngreso = Carbon::parse($Ingreso->date_admission);
                $fechaActual = Carbon::now();
                $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

                if ($mesesTranscurridos < 3) {
                    return back()->with('error', 'No has cumplido el tiempo suficiente para solicitar este permiso.');
                }

                if ($diasTotales > 1) {
                    return back()->with('error', 'Solo puedes tomar un día a la vez.');
                }

                $moreInformationVacation = DB::table('vacations_available_per_users')->where('period', 1)->where('users_id', $user->id)->first();
                $start = $moreInformationVacation->date_start;
                $end = $moreInformationVacation->date_end;

                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                $contadorAsuntosPersonales = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 5)
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', [$start, $end])
                    ->whereJsonContains('more_information', ['Tipo_de_permiso_especial' => 'Asuntos personales'])
                    ->count();

                if ($contadorAsuntosPersonales) {
                    if (!$missingInDias->isEmpty()) {
                        $diasnuevos = 0;
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasnuevos = count($registrarArray);
                    }

                    if (!$missingInDates->isEmpty()) {
                        $diasEliminar = 0;
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        $diasEliminar =  count($eliminarArray);
                    }

                    $AsuntosPersonales = $contadorAsuntosPersonales + $diasnuevos  - $diasEliminar;

                    if ($AsuntosPersonales > 3) {
                        return back()->with('message', 'Solo puedes solicitar tres permisos especiales por año de tipo "Asuntos personales.');
                    }
                }

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    $more_information[] = [
                        'Tipo_de_permiso_especial' => $request->Permiso
                    ];
                    $moreinformation = json_encode($more_information);
                    DB::table('vacation_requests')->where('id', $request->id)->update([
                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                        'more_information' => $moreinformation,
                    ]);

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Solicitud actualizada correctamente.');
                } else {
                    if (!$missingInDias->isEmpty()) {
                        $registrar = $missingInDias->implode(', ');
                        $registrarArray = explode(', ', $registrar);
                        $diasTotales = count($registrarArray);

                        $more_information[] = [
                            'Tipo_de_permiso_especial' => $request->Permiso
                        ];
                        $moreinformation = json_encode($more_information);
                        DB::table('vacation_requests')->where('id', $request->id)->update([
                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                            'more_information' => $moreinformation,
                            'direct_manager_status' => 'Pendiente',
                        ]);

                        foreach ($registrarArray as $dia) {
                            VacationDays::create([
                                'day' => $dia,
                                'vacation_request_id' => $Solicitud->id,
                                'status' => 0,
                            ]);
                        }
                    }
                    if (!$missingInDates->isEmpty()) {
                        ///Estos días ya no vienen en el arreglo, por lo tanto se eliminan de la solicitud.
                        $eliminar = $missingInDates->implode(', ');
                        $eliminarArray = explode(', ', $eliminar);
                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')->where('id', $idfecha)->delete();
                        }
                    }

                    try {
                        $emisor = User::find($user->id);
                        $requestType = RequestType::find($request->request_type_id);
                        $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                            ->pluck('day')
                            ->implode(', ');
                        $boss = User::find($Ingreso->jefe_directo_id);
                        $boss->notify(new PermissionRequestUpdate(
                            $boss->name,
                            $emisor->name,
                            $requestType->type,
                            $days,
                            $request->details,
                        ));
                    } catch (\Exception $e) {
                        return back()->with('warning', 'Solicitud actualizada exitosamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                    }
                    return back()->with('message', 'Se actualizó correctamente la solicitud.');
                }
            }
        }
    }

    public function RejectPermissionHumanResources(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required',
            'commentary' => 'required'
        ]);

        $Solicitud = VacationRequest::where('id', $request->id)->first();


        if ($Solicitud->direct_manager_status != 'Aprobada') {
            return back()->with('error', 'Esta solicitud aún no ha sido aprobada.');
        }

        if ($request->commentary == null) {
            return back()->with('error', 'Debes indicar el motivo por el cual deseas rechazar la solicitud.');
        }

        if ($Solicitud->request_type_id == 1) {
            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'id' => $vaca->id,
                ];
            }

            $InfoVacaciones = DB::table('vacation_information')->where('id_vacation_request', $request->id)->get();
            $total = count($InfoVacaciones);
            if ($total == 2) {
                $idOne = (int) $Datos[0]['id'];
                $idTwo = (int) $Datos[1]['id'];
                $WaitingOne = $Datos[0]['waiting'];
                $WaitingTwo = $Datos[1]['waiting'];
                $dvOne = $Datos[0]['dv'];
                $dvTwo = $Datos[1]['dv'];
                $InfoTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                $InfoOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();
                // $totaldaysOne = $InfoOne->total_days;

                $totaldaysOne = //Si no esta el campo que no lo tome en cuenta
                    $InfoOne->total_days == null ? 0 : $InfoOne->total_days;

                $newWaiting = $WaitingOne - $totaldaysOne;
                $totaldvOne = $dvOne + $totaldaysOne;

                $totaldaysTwo = $InfoTwo->total_days == null ? 0 : $InfoTwo->total_days;
                $newWaitingTwo = $WaitingTwo - $totaldaysTwo;
                $totaldvTwo = $dvTwo + $totaldaysTwo;

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Rechazada',
                    'direct_manager_status' => 'Rechazada',
                    'commentary' => $request->commentary
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 0
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                    'waiting' => $newWaiting,
                    'dv' => $totaldvOne
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                    'waiting' => $newWaitingTwo,
                    'dv' => $totaldvTwo
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($Solicitud->request_type_id);
                    $ApplicationOwner = User::find($Solicitud->user_id);
                    $ApplicationOwner->notify(new RejectRequestBoss(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $request->commentary,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones rechazadas correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
                }

                return back()->with('message', 'Vacaciones rechazadas correctamente');
            }

            if ($total == 1) {
                $VacaInfo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                $id_vacations_availables = $VacaInfo->id_vacations_availables;
                $VacacionesAviles = DB::table('vacations_available_per_users')->where('id', $id_vacations_availables)->first();
                $totaldaysOne = $VacaInfo->total_days;
                $newWaiting = $VacacionesAviles->waiting - $totaldaysOne;
                $totaldv = $VacacionesAviles->dv + $totaldaysOne;
                //dd('totaldevacaciones'.':'.$totaldaysOne.'Waiting'.$newWaiting.'dv'.$totaldv.'VA'.$id_vacations_availables);

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Rechazada',
                    'direct_manager_status' => 'Rechazada',
                    'commentary' => $request->commentary
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 0
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $id_vacations_availables)->update([
                    'waiting' => $newWaiting,
                    'dv' => $totaldv
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($Solicitud->request_type_id);
                    $ApplicationOwner = User::find($Solicitud->user_id);
                    $ApplicationOwner->notify(new RejectRequestBoss(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $request->commentary,
                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones rechazadas correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
                }

                return back()->with('message', 'Vacaciones rechazadas correctamente');
            }
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Rechazada',
                'direct_manager_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($Solicitud->request_type_id);
                $ApplicationOwner = User::find($Solicitud->user_id);
                $ApplicationOwner->notify(new RejectRequestBoss(
                    $ApplicationOwner->name,
                    $emisor->name,
                    $requestType->type,
                    $request->commentary,
                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Solicitud rechazada correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
            }
            return back('message', 'Solicitud rechazada exitosamente.');
        }
    }

    public function AuthorizePermissionHumanResources(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required',
        ]);

        $Solicitud = DB::table('vacation_requests')->where('id', $request->id)->first();
        if ($Solicitud->direct_manager_status == 'Rechazada') {
            return back()->with('error', 'La solicitud fue Rechazada por su jefe directo.');
        }

        if ($Solicitud->request_type_id == 1) {
            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_available_per_users')
                ->where('users_id', $Solicitud->user_id)
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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables,
                    'id' => $vaca->id,
                ];
            }


            $InfoVacaciones = DB::table('vacation_information')->where('id_vacation_request', $request->id)->get();
            $total = count($InfoVacaciones);
            if ($total == 2) {
                $idOne = (int) $Datos[0]['id'];
                $idTwo = (int) $Datos[1]['id'];
                $WaitingOne = $Datos[0]['waiting'];
                $WaitingTwo = $Datos[1]['waiting'];
                $DaysEnjoyedOne = $Datos[0]['days_enjoyed'];
                $DaysEnjoyedTwo = $Datos[1]['days_enjoyed'];
                $dvTwo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idTwo)->first();
                $dvOne = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->where('id_vacations_availables', $idOne)->first();

                $totaldaysOne = //Si no esta el campo que no lo tome en cuenta
                    $dvOne->total_days == null ? 0 : $dvOne->total_days;

                $newWaiting = $WaitingOne - $totaldaysOne;
                $totalDaysEnjoyedOne = $DaysEnjoyedOne + $totaldaysOne;

                $totaldaysTwo = $dvTwo->total_days == null ? 0 : $dvTwo->total_days;
                $newWaitingTwo = $WaitingTwo - $totaldaysTwo;
                $totalDaysEnjoyedtWO = $DaysEnjoyedTwo + $totaldaysTwo;

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Aprobada',
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 1
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idOne)->update([
                    'waiting' => $newWaiting,
                    'days_enjoyed' => $totalDaysEnjoyedOne
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $idTwo)->update([
                    'waiting' => $newWaitingTwo,
                    'days_enjoyed' => $totalDaysEnjoyedtWO
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($Solicitud->request_type_id);
                    $ApplicationOwner = User::find($Solicitud->user_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $ApplicationOwner->notify(new AuthorizeRequestByRH(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $Solicitud->details,
                        $days

                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones aprobadas correctamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Vacaciones aprobadas correctamente');
            }

            if ($total == 1) {
                $VacaInfo = DB::table('vacation_information')->where('id_vacation_request', $Solicitud->id)->first();
                $id_vacations_availables = $VacaInfo->id_vacations_availables;
                $VacacionesAviles = DB::table('vacations_available_per_users')->where('id', $id_vacations_availables)->first();
                // $totaldaysOne = $VacaInfo->total_days;
                $totaldaysOne = //Si no esta el campo que no lo tome en cuenta
                    $VacaInfo->total_days == null ? 0 : $VacaInfo->total_days;

                $newWaiting = $VacacionesAviles->waiting - $totaldaysOne;
                $totalDaysEnjoyedOne = $VacacionesAviles->days_enjoyed + $totaldaysOne;

                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'rh_status' => 'Aprobada'
                ]);

                DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                    'status' => 1
                ]);

                DB::table('vacations_available_per_users')->where('users_id', $Solicitud->user_id)->where('id', $id_vacations_availables)->update([
                    'waiting' => $newWaiting,
                    'days_enjoyed' => $totalDaysEnjoyedOne
                ]);

                try {
                    $emisor = User::find($user->id);
                    $requestType = RequestType::find($Solicitud->request_type_id);
                    $ApplicationOwner = User::find($Solicitud->user_id);
                    $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                        ->pluck('day')
                        ->implode(', ');
                    $ApplicationOwner->notify(new AuthorizeRequestByRH(
                        $ApplicationOwner->name,
                        $emisor->name,
                        $requestType->type,
                        $Solicitud->details,
                        $days

                    ));
                } catch (\Exception $e) {
                    return back()->with('warning', 'Vacaciones aprobadas correctamente. Sin embargo, no se pudo enviar el correo electrónico a tu jefe directo.');
                }

                return back()->with('message', 'Vacaciones aprobadas correctamente');
            }

            return back()->with('message', 'Autorización exitosa');
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Aprobada'
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 1
            ]);

            try {
                $emisor = User::find($user->id);
                $requestType = RequestType::find($Solicitud->request_type_id);
                $ApplicationOwner = User::find($Solicitud->user_id);
                $days = VacationDays::where('vacation_request_id', $Solicitud->id)
                    ->pluck('day')
                    ->implode(', ');
                $ApplicationOwner->notify(new AuthorizeRequestByRH(
                    $ApplicationOwner->name,
                    $emisor->name,
                    $requestType->type,
                    $Solicitud->details,
                    $days

                ));
            } catch (\Exception $e) {
                return back()->with('warning', 'Solicitud aprobada correctamente. Sin embargo, no se pudo enviar el correo electrónico al colaborador(a).');
            }

            return back()->with('message', 'Autorización exitosa.');
        }
    }
    public function UpdatePurchase(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'details' => 'required',
            'reveal_id' => 'required',
            'dates' => 'required'
        ]);

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }
    }

    public function MakeUpVacations(Request $request)
    {
        //dd($request);
        $request->validate([
            'team' => 'required',
            'commentary' => 'required',
            'days' => 'required',
            'Periodo' => 'required',
            'Opcion' => 'required'
        ]);

        $usuarios = $request->team;
        $fechaActual = Carbon::now();
        $vacaciones = DB::table('vacations_available_per_users')
            ->where('cutoff_date', '>=', $fechaActual)
            ->whereIn('users_id', $usuarios)
            ->orderBy('cutoff_date', 'asc')
            ->get();

        $Datos = [];
        foreach ($vacaciones as $vaca) {
            $Datos[$vaca->users_id][] = [
                'dv' => $vaca->dv,
                'cutoff_date' => $vaca->cutoff_date,
                'period' => $vaca->period,
                'days_enjoyed' => $vaca->days_enjoyed,
                'waiting' => $vaca->waiting,
                'days_availables' => $vaca->days_availables,
            ];
        }

        if ($request->Periodo === 'primer_periodo') {
            if ($request->Opcion === 'aumentar_dias') {
                foreach ($usuarios as $usuario) {
                    if (isset($Datos[$usuario][0])) {
                        $primerdv = $Datos[$usuario][0]['dv'];
                        $totalnuevodv = $primerdv + $request->days;
                        DB::table('vacations_available_per_users')
                            ->where('users_id', $usuario)
                            ->where('dv', $primerdv)
                            ->update(['dv' => $totalnuevodv]);

                        MakeUpVacations::create([
                            'user_id' => $usuario,
                            'description' => $request->commentary,
                            'num_days' => $request->days
                        ]);
                    }
                }
                return  back()->with('message', 'Se aumentaron los días exitosamente.');
            }
            if ($request->Opcion === 'descontar_dias') {
                foreach ($usuarios as $usuario) {
                    if (isset($Datos[$usuario][0])) {
                        $primerdv = $Datos[$usuario][0]['dv'];
                        $totalnuevodv = $primerdv - $request->days;
                        DB::table('vacations_available_per_users')
                            ->where('users_id', $usuario)
                            ->where('dv', $primerdv)
                            ->update(['dv' => $totalnuevodv]);

                        MakeUpVacations::create([
                            'user_id' => $usuario,
                            'description' => $request->commentary,
                            'subtract_days' => $request->days
                        ]);
                    }
                }
                return  back()->with('message', 'Se descontaron los días exitosamente.');
            }
        }

        $usuariosSinSegundoPeriodo = [];
        if ($request->Periodo === 'segundo_periodo') {
            foreach ($usuarios as $usuario) {
                if (!isset($Datos[$usuario][1])) {
                    $usuariosSinSegundoPeriodo[] = $usuario;
                }
            }

            if ($request->Opcion === 'aumentar_dias') {
                foreach ($usuarios as $usuario) {
                    if (isset($Datos[$usuario][1])) {
                        $primerdv = $Datos[$usuario][1]['dv'];
                        $totalnuevodv = $primerdv + $request->days;
                        DB::table('vacations_available_per_users')
                            ->where('users_id', $usuario)
                            ->where('dv', $primerdv)
                            ->update(['dv' => $totalnuevodv]);

                        MakeUpVacations::create([
                            'user_id' => $usuario,
                            'description' => $request->commentary,
                            'num_days' => $request->days
                        ]);
                    }
                }

                if ($usuariosSinSegundoPeriodo == null) {
                    return back()->with('message', 'Se aumentaron los días exitosamente.');
                }

                $Name = [];
                foreach ($usuariosSinSegundoPeriodo as $nameUser) {
                    $usuarioInfo = User::where('id', $nameUser)->first();
                    $Name[] = $usuarioInfo->name . ' ' . $usuarioInfo->lastname;
                }
                $users = implode(', ', $Name);
                return back()->with('message', 'Se aumentaron los días exitosamente, sin embargo, estos usuarios: ' . $users . ' ' . 'solo tienen un periodo, por lo tanto no se les aumentaron los días.');
            }

            if ($request->Opcion === 'descontar_dias') {
                foreach ($usuarios as $usuario) {
                    if (isset($Datos[$usuario][1])) {
                        $primerdv = $Datos[$usuario][1]['dv'];
                        $totalnuevodv = $primerdv - $request->days;
                        DB::table('vacations_available_per_users')
                            ->where('users_id', $usuario)
                            ->where('dv', $primerdv)
                            ->update(['dv' => $totalnuevodv]);

                        MakeUpVacations::create([
                            'user_id' => $usuario,
                            'description' => $request->commentary,
                            'subtract_days' => $request->days
                        ]);
                    }
                }
            }
            if ($usuariosSinSegundoPeriodo == null) {
                return back()->with('message', 'Se descontaron los días exitosamente.');
            }

            $Name = [];
            foreach ($usuariosSinSegundoPeriodo as $nameUser) {
                $usuarioInfo = User::where('id', $nameUser)->first();
                $Name[] = $usuarioInfo->name . ' ' . $usuarioInfo->lastname;
            }
            $users = implode(', ', $Name);

            return back()->with('message', 'Se descontaron los días exitosamente, sin embargo, estos usuarios: ' . $users . ' ' . 'solo tienen un periodo, por lo tanto, no se les descontaron los día.');
        }
    }

    ////////////DATOS DE USUARIOS///////////////////
    public function CreateVacationRequest(Request $request)
    {
        $Ingresos = Employee::all();
        $aniversariosPorUsuario = [];

        foreach ($Ingresos as $Ingreso) {
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();

            $aniversariosPorUsuario[$Ingreso->user_id] = [];

            // Fecha del primer aniversario
            $aniversario = $fechaIngreso->copy();


            $añosCumplidos = 0;

            // Itera hasta que el aniversario sea mayor que la fecha actual
            while ($aniversario->lessThanOrEqualTo($fechaActual)) {
                // Calcula el número de días en el año del aniversario (considera si es bisiesto)
                $diasEnAnio = $aniversario->isLeapYear() ? 366 : 365;

                // Obtiene el número de días de vacaciones para el año correspondiente
                $anos = DB::table('vacation_per_years')->where('year', $añosCumplidos)->value('days');
                $vacacionesAniversario = $anos; // Vacaciones solo para ese año

                // Agrega al array del usuario
                $aniversariosPorUsuario[$Ingreso->user_id][] = [
                    'aniversario' => $aniversario->format('Y-m-d'),
                    'años_Cumplidos' => $añosCumplidos > 0 ? $añosCumplidos : null, // No muestra años cumplidos para el primer aniversario
                    'dias_en_año' => $diasEnAnio,
                    'vacaciones_acumuladas' => $vacacionesAniversario,
                ];

                // Avanza al siguiente aniversario
                $aniversario->addYear();
                $añosCumplidos++;
            }

            // Calcula el número de días desde el último aniversario hasta la fecha actual
            $ultimoAniversario = $aniversario->subYear(); // Regresa al último aniversario cumplido
            $diasDesdeUltimoAniversarioHastaHoy = $fechaActual->diffInDays($ultimoAniversario);

            // Calcula el número de días en el año en curso
            $anioEnCurso = $fechaActual->year;
            $diasEnAnioUltimo = $ultimoAniversario->isLeapYear() ? 366 : 365;

            // Obtiene el número de días de vacaciones para el año en curso
            $anos = DB::table('vacation_per_years')->where('year', $añosCumplidos)->value('days');

            // Ajusta el cálculo de vacaciones para el período actual hasta hoy
            $vacacionesAcumuladasHastaHoy = ($diasDesdeUltimoAniversarioHastaHoy / $diasEnAnioUltimo) * $anos;

            // Agrega la acumulación final
            $aniversariosPorUsuario[$Ingreso->user_id][] = [
                'aniversario' => 'Aún no cumple el año, pero se comienzan a generar sus vacaciones',
                'años_Cumplidos' => $añosCumplidos, // Los años cumplidos hasta la fecha actual
                'dias_desde_ultimo_aniversario_hasta_hoy' => $diasDesdeUltimoAniversarioHastaHoy,
                'dias_en_año' => $diasEnAnioUltimo,
                'vacaciones_acumuladas' => $vacacionesAcumuladasHastaHoy,
            ];
        }
        // Muestra el resultado
        dd($aniversariosPorUsuario[31]);
    }
}
