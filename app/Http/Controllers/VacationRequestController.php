<?php

namespace App\Http\Controllers;

use App\Http\Livewire\Vacations\Vacations;
use App\Models\Employee;
use App\Models\MakeUpVacations;
use App\Models\Position;
use App\Models\RequestType;
use App\Models\User;
use App\Models\VacationDays;
use App\Models\VacationRequest;
use Carbon\Carbon;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
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

        $vacaciones = DB::table('vacation_requests')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(5);

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
            $solicitud->file = $vacacion->file == null ? 'No hay justificante' : $vacacion->file;
            $solicitud->commentary = $vacacion->commentary == null ? 'No hay un comentario' : $vacacion->commentary;
            $solicitud->days = $dias;
            $solicitud->request_type_id == 2 ? $time : null;
            $solicitud->more_information = $vacacion->more_information == null ? null : json_decode($vacacion->more_information, true);
            $solicitudes[] = $solicitud;
        }

        //dd($solicitudes);
        $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
        $fechaIngreso = Carbon::parse($Ingreso->date_admission);
        $fechaActual = Carbon::now();
        $Vacaciones = DB::table('vacations_availables')
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
                'cutoff_date' => $vaca->cutoff_date,
            ];
        }

        if (count($Datos) > 1) {
            $diasreservados = $Datos[0]['waiting'] + $Datos[1]['waiting'];
            $diasdisponibles = $Datos[0]['dv'] + $Datos[1]['dv'];
            $totalvacaciones = $Datos[0]['days_availables'] + $Datos[1]['days_availables'];
            $totalvacaionestomadas = $Datos[0]['days_enjoyed'] + $Datos[1]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
            $porcentajetomadas = round($porcentajetomadas);
            $fecha_expiracion_actual = $Datos[0]['cutoff_date'];
            $vacaciones_actuales = $Datos[0]['dv'];
            $fecha_expiracion_entrante = $Datos[1]['cutoff_date'];
            $vacaciones_entrantes = $Datos[1]['dv'];
        } elseif (count($Datos) == 1) {
            $diasreservados = $Datos[0]['waiting'];
            $diasdisponibles = $Datos[0]['dv'];
            $totalvacaciones = $Datos[0]['days_availables'];
            $totalvacaionestomadas = $Datos[0]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
            $porcentajetomadas = round($porcentajetomadas, 2);
            //dd($porcentajetomadas);
            $fecha_expiracion_actual = $Datos[0]['cutoff_date'];
            $vacaciones_actuales = $Datos[0]['dv'];
        }

        $vacacionesDias = [];
        $ausenciaDias = [];
        $paternidadDias = [];
        $incapacidadDias = [];
        $permisosEspecialesDias = [];
        foreach ($vacaciones as $daysonthecalendar) {
            // Obtener los días asociados a la solicitud
            $Days = VacationDays::where('vacation_request_id', $daysonthecalendar->id)->get();
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
        
        return view('request.vacations-collaborators', compact('users', 'solicitudes', 'diasreservados', 'diasdisponibles', 'totalvacaciones', 'totalvacaionestomadas', 'porcentajetomadas', 'fecha_expiracion_actual', 'vacaciones_actuales', 'fecha_expiracion_entrante', 'vacaciones_entrantes', 'vacacionescalendar', 'porcentajeespecial'));
    }

    public function CreatePurchase(Request $request)
    {

        $user = auth()->user();

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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables
                ];
            }

            if (count($Datos) > 1) {
                $PrimerPeriodo = (int) $Datos[0]['dv'];
                $SegundoPeriodo = (int) $Datos[1]['dv'];
                $totalambosperidos = $PrimerPeriodo + $SegundoPeriodo;
            } elseif (count($Datos) == 1) {
                $totalambosperidos = $Datos[0]['dv'];
            }

            $dates = $request->dates;
            $datesArray = json_decode($dates, true);
            $diasTotales = count($datesArray);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

            if ($diasTotales > $totalambosperidos) {
                return back()->with('error', 'No cuentas con días suficientes');
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
                $PrimerPeriodo = (int) $Datos[0]['dv'];
                $Periodo = $Datos[0]['period'];
                $SegundoPeriodo = (int) $Datos[1]['dv'];
                $Periododos = $Datos[1]['period'];
                $primerWaiting = $Datos[0]['waiting'];
                $segundoWaiting = $Datos[1]['waiting'];
                $primerDaysEnjoyed = $Datos[0]['days_enjoyed'];
                $SegundoDaysEnjoyed = $Datos[1]['days_enjoyed'];
                $primerDaysAvailables = $Datos[0]['days_availables'];
                $segundoDaysAvailables = $Datos[1]['days_availables'];

                // Cálculos de disponibilidad
                $totalAmbosPeriodos = $PrimerPeriodo + $SegundoPeriodo;
                $Disponibilidad1 = $primerWaiting + $primerDaysEnjoyed;
                $Disponibilidad2 = $segundoWaiting + $SegundoDaysEnjoyed;
                $prueba = $Disponibilidad1 + $Disponibilidad2;

                if ($diasTotales > $totalAmbosPeriodos) {
                    return  back()->with('error', 'No cuentas con los días solicitados.');
                }

                if ($diasTotales <= $totalAmbosPeriodos) {
                    // Caso donde los días solicitados están dentro del primer periodo
                    if ($diasTotales <= $PrimerPeriodo && $PrimerPeriodo > 0) {
                        $cercadv = $diasTotales + $primerWaiting;
                        $cercadv2 = $diasTotales + $segundoWaiting;

                        if ($cercadv <= $PrimerPeriodo) {
                            DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                                'waiting' => $cercadv
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
                        } elseif ($cercadv2 <= $SegundoPeriodo) {
                            $resta = $PrimerPeriodo - $primerWaiting;
                            if ($resta >= 0) {
                                $proxdv = $diasTotales - $resta;
                                $nuevodv = $diasTotales - $proxdv;
                                $newdv = $primerWaiting + $nuevodv;
                                $prodv = $proxdv + $segundoWaiting;
                                if ($newdv <= $PrimerPeriodo && $prodv  <= $SegundoPeriodo) {
                                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                                        'waiting' => $newdv
                                    ]);
                                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                        'waiting' => $prodv
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
                                }
                            }
                        } elseif (($primerWaiting + $diasTotales) > $PrimerPeriodo) {
                            ////ESTE TIENE POCOS DIAS EN EL PRIMER PERIODO Y ESTAN PENDIENTES.
                            ////TOMA LOS QUE LE FALTAN Y SIGUE CON EL SIGUIENTE PERIODO.
                            $verificar = $primerWaiting + $diasTotales;
                            $diasdisponiblesprimerperiodo = $verificar - $PrimerPeriodo;
                            $faltantesdias = $diasTotales - $diasdisponiblesprimerperiodo;
                            $reservados1nuevo = $primerWaiting + $diasdisponiblesprimerperiodo;
                            $reservados2nuevo = $segundoWaiting + $faltantesdias;
                            if ($reservados1nuevo <= $PrimerPeriodo && $reservados2nuevo <= $SegundoPeriodo) {
                                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                                    'waiting' => $reservados1nuevo
                                ]);

                                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                    'waiting' => $reservados2nuevo
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
                            }
                        } else {
                            return back()->with('error', 'Asegúrate que no tienes vacaciones por autorizar, ya que tienes días disponibles, pero están en espera.');
                        }
                        return back()->with('message', 'Vacaciones actualizadas correctamente. 1');
                    }

                    // Caso donde los días solicitados están en ambos periodos
                    if ($diasTotales > $PrimerPeriodo) {
                        $faltan = $diasTotales - $PrimerPeriodo;
                        $ambosWaiting = $primerWaiting + $segundoWaiting;

                        if ($ambosWaiting <= $totalAmbosPeriodos) {
                            $restadedv = $diasTotales - $faltan;
                            $cercadv = $restadedv + $primerWaiting;

                            if ($cercadv <= $PrimerPeriodo && $faltan <= $SegundoPeriodo && $PrimerPeriodo > 0) {
                                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                                    'waiting' => $restadedv
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

                                if ($faltan > 0 && $PrimerPeriodo > 0) {
                                    if ($SegundoPeriodo > 0) {
                                        if ($faltan <= $SegundoPeriodo) {
                                            if ($segundoWaiting == $SegundoPeriodo || $segundoWaiting > $SegundoPeriodo) {
                                                return  back()->with('No tienes mas días');
                                            }
                                            $cercadv2 = $faltan + $segundoWaiting;
                                            DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                                'waiting' => $cercadv2
                                            ]);
                                        } else {
                                            return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                                        }
                                    }
                                }
                            } elseif ($SegundoPeriodo > 0 && $PrimerPeriodo == 0 && ($segundoWaiting + $diasTotales) <= $SegundoPeriodo) {
                                $prueba = $diasTotales + $segundoWaiting;
                                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                    'waiting' => $prueba
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
                            } else {
                                return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                            }


                            /* if ($faltan > 0 && $PrimerPeriodo > 0) {
                                return 'aqui cae igual';
                                if ($SegundoPeriodo > 0) {
                                    if ($faltan <= $SegundoPeriodo) {
                                        if ($segundoWaiting == $SegundoPeriodo || $segundoWaiting > $SegundoPeriodo) {
                                            return  back()->with('No tienes mas días');
                                        }
                                        $cercadv2 = $faltan + $segundoWaiting;
                                        DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                            'waiting' => $cercadv2
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
                                    } else {
                                        return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                                    }
                                }
                            } */
                            return back()->with('message', 'Vacaciones actualizadas correctamente.');
                        } else {
                            return  back()->with('error', 'No cuentas con los días solicitados.');
                        }
                    }
                }
            }

            if (count($Datos) == 1) {
                $totalunsoloperido = $Datos[0]['dv'];
                $Periodo = $Datos[0]['period'];
                $primerWaiting = $Datos[0]['waiting'];
                $primerDaysEnjoyed = $Datos[0]['days_enjoyed'];
                $dvupdate = $primerWaiting + $diasTotales;
                $Disponibilidad = $primerWaiting + $primerDaysEnjoyed;

                if ($diasTotales > $totalunsoloperido) {
                    return back()->with('error', 'No cuentas con los días solicitados.');
                }

                if ($dvupdate > $totalunsoloperido) {
                    return back()->with('error', 'Asegurate que no tengas solicitudes pendientes.');
                }

                if ($diasTotales <= $totalunsoloperido) {
                    //dd($dvupdate);
                    if ($diasTotales <= $dvupdate) {
                        DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                            'waiting' => $dvupdate,
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
                        return back()->with('message', 'Se creó tu solicitud de vacaciones.');
                    }
                }
            }

            return back()->with('message', 'Se creó tu solicitud de vacaciones.');
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
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

            if ($request->ausenciaTipo == 'salida_antes') {

                if ($dias > 1) {
                    return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
                }

                $hora5PM = Carbon::today()->setHour(17)->setMinute(0)->setSecond(0);
                $start = $request->hora_salida;

                $diferenciaEnMinutos = $hora5PM->diffInMinutes($start);
                // Convertir la diferencia a horas y minutos
                $diferenciaEnHoras = intdiv($diferenciaEnMinutos, 60); // Horas
                $diferenciaEnMinutosRestantes = $diferenciaEnMinutos % 60; // Minutos restantes

                if ($diferenciaEnHoras > 4) {
                    return back()->with('error', 'No puedes tomar más de cuatro horas.');
                }

                $horaSalidaCarbon = Carbon::createFromFormat('H:i', $start);

                if ($horaSalidaCarbon->greaterThanOrEqualTo($hora5PM)) {
                    return back()->with('error', 'No se pueden crear solicitudes después de las 17 horas.');
                }


                $more_information[] = [
                    'Tipo_de_ausencia' => $request->ausenciaTipo == 'salida_antes' ? 'Salir antes' : 'No encontro el valor',
                    'value_type' => $request->ausenciaTipo,
                ];
                $moreinformation = json_encode($more_information);
                if ($horaSalidaCarbon->lessThan($hora5PM)) {
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
                            'start' => $horaSalidaCarbon,
                            'vacation_request_id' => $Vacaciones->id,
                            'status' => 0,
                        ]);
                    }
                    return back()->with('message', 'Solicitud creada exitosamente.');
                } else {
                    return back()->with('error', 'No puedes seleccionar la misma hora de salida');
                }
            }

            if ($request->ausenciaTipo == 'salida_durante') {
                $hora8AM = Carbon::today()->setHour(8)->setMinute(0)->setSecond(0);
                $hora5PM = Carbon::today()->setHour(17)->setMinute(0)->setSecond(0);
                $start = $request->hora_salida;
                $end = $request->hora_regreso;

                $hora1Carbon = Carbon::createFromFormat('H:i', $start);
                $hora2Carbon = Carbon::createFromFormat('H:i', $end);

                // Calcular la diferencia en minutos
                $diferenciaEnMinutos = $hora2Carbon->diffInMinutes($hora1Carbon);

                // Convertir la diferencia a horas y minutos
                $diferenciaEnHoras = intdiv($diferenciaEnMinutos, 60); // Horas
                $diferenciaEnMinutosRestantes = $diferenciaEnMinutos % 60; // Minutos restantes
                if ($dias > 1) {
                    return back()->with('error', 'Sí tienes más de una solicitud, debes crearla una por una.');
                }

                if ($diferenciaEnHoras > 4) {
                    return back()->with('error', 'No puedes tomar más de cuatro horas.');
                }

                ///aqui ya se va crear la solicitud
                if ($hora1Carbon->greaterThanOrEqualTo($hora8AM) && $hora1Carbon->lessThan($hora5PM) && $hora2Carbon->greaterThanOrEqualTo($hora1Carbon) && $hora2Carbon->lessThanOrEqualTo($hora5PM)) {
                    // Hora dentro del rango permitido
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
                    return back()->with('message', 'Solicitud creada exitosamente.');
                } else {
                    // Hora fuera del rango permitido
                    return back()->with('message', 'La hora de inicio está fuera del rango permitido.');
                }
            }
            //dd($diferenciaEnHoras.'...'.$diferenciaEnMinutosRestantes);
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

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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
            return back()->with('message', 'Se creo exitosamente la solicitud. Recuerda que estos días son naturales y además estos los paga el IMSS.');
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
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

                return back()->with('message', 'Se creó con éxito tu solicitud.');
            }

            if ($request->Permiso == 'Matrimonio del colaborador') {
                ///VACACIONES PENDIENTES O APROBADAS///
                if ($dias > 5) {
                    return back()->with('error', 'Solo tienes derecho a tomar cinco días.');
                }

                if ($dias == 5) {
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
                    return back()->with('message', 'Se creó con éxito tu solicitud.');
                } else {
                    return back()->with('error', 'Debes tomar tus cinco días.');
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

                $currentYear = date('Y');
                $AsuntosPersonales = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 5)
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', ["$currentYear-01-01 00:00:00", "$currentYear-12-31 23:59:59"])
                    ->get();

                $contadorAsuntosPersonales = 0;
                foreach ($AsuntosPersonales as $asuntoPersonal) {
                    $moreInformation = json_decode($asuntoPersonal->more_information, true);
                    if (!empty($moreInformation) && isset($moreInformation[0]['Tipo_de_permiso_especial']) && $moreInformation[0]['Tipo_de_permiso_especial'] === 'Asuntos personales') {
                        $contadorAsuntosPersonales++;
                    }
                }

                if ($contadorAsuntosPersonales >= 3) {
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
                return back()->with('message', 'Se creó con éxito tu solicitud.');
            }
        }
    }

    public function RequestBoss()
    {
        $user = auth()->user();

        $HeIsBossOf = Employee::where('jefe_directo_id', $user->id)->where('status', 1)->pluck('user_id');
        $Solicitudes = DB::table('vacation_requests')->whereIn('user_id', $HeIsBossOf)->orderBy('created_at', 'desc')->paginate(5);
        $SumaSolicitudes = $Solicitudes->total();
        $InfoSolicitud = [];
        foreach ($Solicitudes as $Solicitud) {
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
            $Vacaciones = DB::table('vacations_availables')
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

            $InfoSolicitud[] = [
                'image' => $nameUser->image,
                'created_at' => $Solicitud->created_at,
                'id' => $Solicitud->id,
                'name' => $nameUser->name . ' ' . $nameUser->lastname,
                'current_vacation' => $Datos[0]['dv'] ?? null,
                'current_vacation_expiration' => $Datos[0]['cutoff_date'] ?? null,
                'next_vacation' => empty($Datos[1]['dv']) ? null : $Datos[1]['dv'],
                'expiration_of_next_vacation' => empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'],
                'direct_manager_status' => $Solicitud->direct_manager_status,
                'rh_status' => $Solicitud->rh_status,
                'request_type' => $RequestType->type,
                'specific_type' => $RequestType->type == 1 ?: '-',
                'days_absent' => $dias,
                'method_of_payment' => $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro')))),
                'reveal_id' => $Reveal->name . ' ' . $Reveal->lastname,
                'file' => $Solicitud->file == null ? null : $Solicitud->file,
                'time' => $Solicitud->request_type_id == 2 ? $time : null,
                'more_information' => $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true),
            ];
        }

        $InfoSolicitudesUsuario = json_encode($InfoSolicitud);
        return view('request.authorize', compact('InfoSolicitud', 'InfoSolicitudesUsuario', 'SumaSolicitudes'));
    }

    public function authorizeRequestRH()
    {
        $user = auth()->user();

        $Solicitudes = DB::table('vacation_requests')->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada')->orderBy('created_at', 'desc')->paginate(5);
        $sumaAprobadas = $Solicitudes->total();
        $SolicitudesAprobadas = [];
        foreach ($Solicitudes as $Solicitud) {
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
            $Vacaciones = DB::table('vacations_availables')
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

            $SolicitudesAprobadas[] = [
                'image' => $nameUser->image,
                'created_at' => $Solicitud->created_at,
                'id' => $Solicitud->id,
                'name' => $nameUser->name . ' ' . $nameUser->lastname,
                'current_vacation' => $Datos[0]['dv'],
                'current_vacation_expiration' => $Datos[0]['cutoff_date'],
                'next_vacation' =>  empty($Datos[1]['dv']) ? null : $Datos[1]['dv'],
                'expiration_of_next_vacation' => empty($Datos[1]['cutoff_date']) ? null : $Datos[1]['cutoff_date'],
                'direct_manager_status' => $Solicitud->direct_manager_status,
                'rh_status' => $Solicitud->rh_status,
                'request_type' => $RequestType->type,
                'specific_type' => $RequestType->type == 1 ?: '-',
                'days_absent' => $dias,
                'method_of_payment' => $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro')))),
                'reveal_id' => $Reveal->name . ' ' . $Reveal->lastname,
                'file' => $Solicitud->file == null ? null : $Solicitud->file,
                'time' => $Solicitud->request_type_id == 2 ? $time : null,
                'more_information' => $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true),
            ];
        }

        $Aprobadas = $SolicitudesAprobadas;

        $SolicitudesPendientes = DB::table('vacation_requests')
            ->where('direct_manager_status', 'Aprobada')
            ->where('rh_status', 'Pendiente')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $sumaPendientes = $SolicitudesPendientes->total();

        $Pendientes = [];
        foreach ($SolicitudesPendientes as $Solicitud) {
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
            $Vacaciones = DB::table('vacations_availables')
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

            $Pendientes[] = [
                'image' => $nameUser->image,
                'created_at' => $Solicitud->created_at,
                'id' => $Solicitud->id,
                'name' => $nameUser->name . ' ' . $nameUser->lastname,
                'current_vacation' => $Datos[0]['dv'] ?? null,
                'current_vacation_expiration' => $Datos[0]['cutoff_date'] ?? null,
                'next_vacation' => !empty($Datos[1]['dv']) ? $Datos[1]['dv'] : null,
                'expiration_of_next_vacation' => !empty($Datos[1]['cutoff_date']) ? $Datos[1]['cutoff_date'] : null,
                'direct_manager_status' => $Solicitud->direct_manager_status,
                'rh_status' => $Solicitud->rh_status,
                'request_type' => $RequestType->type,
                'specific_type' => $RequestType->type == 1 ? 'Específico' : '-',
                'days_absent' => $dias,
                'method_of_payment' => $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro')))),
                'reveal_id' => $Reveal->name . ' ' . $Reveal->lastname,
                'file' => $Solicitud->file ?? null,
                'time' => $Solicitud->request_type_id == 2 ? $time : null,
                'more_information' => $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true),
            ];
        }
        $SolicitudesPendientes = $Pendientes;

        $SolicitudesRechazadas = DB::table('vacation_requests')->where('direct_manager_status', 'Cancelada por el usuario')
            ->where('rh_status', 'Cancelada por el usuario')->orderBy('created_at', 'desc')->paginate(5);

        $sumaCanceladasUsuario = $SolicitudesRechazadas->total();

        $rechazadas = [];
        foreach ($SolicitudesRechazadas as $Solicitud) {
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
            $Vacaciones = DB::table('vacations_availables')
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

            $rechazadas[] = [
                'image' => $nameUser->image,
                'created_at' => $Solicitud->created_at,
                'id' => $Solicitud->id,
                'name' => $nameUser->name . ' ' . $nameUser->lastname,
                'current_vacation' => $Datos[0]['dv'] ?? null,
                'current_vacation_expiration' => $Datos[0]['cutoff_date'] ?? null,
                'next_vacation' => !empty($Datos[1]['dv']) ? $Datos[1]['dv'] : null,
                'expiration_of_next_vacation' => !empty($Datos[1]['cutoff_date']) ? $Datos[1]['cutoff_date'] : null,
                'details' => $Solicitud->details,
                'commentary' => $Solicitud->commentary,
                'direct_manager_status' => $Solicitud->direct_manager_status,
                'rh_status' => $Solicitud->rh_status,
                'request_type' => $RequestType->type,
                'specific_type' => $RequestType->type == 1 ? 'Específico' : '-',
                'days_absent' => $dias,
                'method_of_payment' => $Solicitud->request_type_id == 1 ? 'A cuenta de vacaciones' : ($Solicitud->request_type_id == 2 ? 'Ausencia' : ($Solicitud->request_type_id == 3 ? 'Paternidad' : ($Solicitud->request_type_id == 4 ? 'Incapacidad' : ($Solicitud->request_type_id == 5 ? 'Permisos especiales' : 'Otro')))),
                'reveal_id' => $Reveal->name . ' ' . $Reveal->lastname,
                'file' => $Solicitud->file ?? null,
                'time' => $Solicitud->request_type_id == 2 ? $time : null,
                'more_information' => $Solicitud->more_information == null ? null : json_decode($Solicitud->more_information, true),
            ];
        }

        $usersid = DB::table('employees')->where('status', 1)->pluck('user_id');
        $IdandNameUser = [];
        foreach ($usersid as $userid) {
            $Usuario = User::where('id', $userid)->first();
            $IdandNameUser[] = [
                'name' => $Usuario->name . ' ' . $Usuario->lastname,
                'id' => $userid
            ];
        }

        $agregarvacaciones = MakeUpVacations::all();
        $vacacionesagregadas = [];
        foreach ($agregarvacaciones as $vacacionesUser) {
            $Usuario = User::where('id', $vacacionesUser->user_id)->first();
            $vacacionesagregadas[] = [
                'iduser' => $Usuario->name . ' ' . $Usuario->lastname,
                'num_days' => $vacacionesUser->num_days,
                'description' => $vacacionesUser->description
            ];
        }

        // dd($Pendientes);

        return view('request.authorize_rh', compact('SolicitudesPendientes', 'Pendientes', 'Aprobadas', 'SolicitudesAprobadas', 'sumaAprobadas', 'sumaPendientes', 'sumaCanceladasUsuario', 'rechazadas', 'IdandNameUser', 'vacacionesagregadas'));
    }

    public function AuthorizePermissionBoss(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'id' => 'required',
        ]);

        $IsBoss = VacationRequest::where('id', $request->id)->value('direct_manager_id');
        if ($IsBoss != $user->id) {
            //dd('Solo su jefe directo puede autorizar la solicitud');
            return back()->with('error', 'Sólo su jefe directo puede autorizar la solicitud');
        }

        DB::table('vacation_requests')->where('id', $request->id)->update([
            'direct_manager_status' => 'Aprobada'
        ]);

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

        if ($solicitud->request_type_id == 1) {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'direct_manager_status' => 'Rechazada',
                'rh_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_availables')
                ->where('users_id', $solicitud->user_id)
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
                    'days_availables' => $vaca->days_availables
                ];
            }

            $dias = VacationDays::where('vacation_request_id', $request->id)->count();
            if (count($Datos) > 1) {
                $datoswaiting = $Datos[0]['waiting'];
                $datoswaitingdos = $Datos[1]['waiting'];
                $peridoUno = $Datos[0]['period'];
                $peridoDos = $Datos[1]['period'];
                if ($dias <= $datoswaiting) {
                    $menosWaiting = $datoswaiting - $dias;

                    DB::table('vacations_availables')->where('users_id', $solicitud->user_id)->where('period', $peridoUno)->update([
                        'waiting' => $menosWaiting
                    ]);
                } elseif ($dias > $datoswaiting) {
                    $restawaiting = ($datoswaiting - $dias) * (-1);
                    $finalwaitinguno = $dias - $restawaiting;
                    $waiting2 = $datoswaitingdos - $restawaiting;

                    if (($finalwaitinguno <= $datoswaiting) && ($restawaiting <= $datoswaitingdos)) {
                        $menosWaiting = $datoswaiting - $finalwaitinguno;
                        ////waiting 1////
                        DB::table('vacations_availables')->where('users_id', $solicitud->user_id)->where('period', $peridoUno)->update([
                            'waiting' => $menosWaiting,

                        ]);
                        ////waiting 2////
                        DB::table('vacations_availables')->where('users_id', $solicitud->user_id)->where('period', $peridoDos)->update([
                            'waiting' => $waiting2,
                        ]);
                    }
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
            } elseif (count($Datos) == 1) {
                $diasreservados = $Datos[0]['waiting'];
                $PeridoUno = $Datos[0]['period'];
                if ($dias <= $diasreservados) {
                    $menosWaiting = $diasreservados - $dias;
                    DB::table('vacations_availables')->where('users_id', $solicitud->user_id)->where('period', $PeridoUno)->update([
                        'waiting' => $menosWaiting,
                    ]);
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                // dd('Se rechazó la solicitud exitosamente.');
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
            }
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'direct_manager_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);
            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);
        }



        return back()->with('message', 'Solicitud rechazada exitosamente.');
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

        if ($Solicitud->request_type_id == 1) {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'commentary' => $request->commentary,
                'direct_manager_status' => 'Cancelada por el usuario',
                'rh_status' => 'Cancelada por el usuario'
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_availables')
                ->where('users_id', $Solicitud->user_id)
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
                    'days_availables' => $vaca->days_availables
                ];
            }

            $dias = VacationDays::where('vacation_request_id', $request->id)->count();
            if (count($Datos) > 1) {
                $datoswaiting = $Datos[0]['waiting'];
                $datoswaitingdos = $Datos[1]['waiting'];
                $peridoUno = $Datos[0]['period'];
                $peridoDos = $Datos[1]['period'];
                if ($dias <= $datoswaiting) {
                    $menosWaiting = $datoswaiting - $dias;

                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                        'waiting' => $menosWaiting
                    ]);
                } elseif ($dias > $datoswaiting) {
                    $restawaiting = ($datoswaiting - $dias) * (-1);
                    $finalwaitinguno = $dias - $restawaiting;
                    $waiting2 = $datoswaitingdos - $restawaiting;

                    if (($finalwaitinguno <= $datoswaiting) && ($restawaiting <= $datoswaitingdos)) {
                        $menosWaiting = $datoswaiting - $finalwaitinguno;
                        ////waiting 1////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                            'waiting' => $menosWaiting,

                        ]);
                        ////waiting 2////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoDos)->update([
                            'waiting' => $waiting2,
                        ]);
                    }
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
            } elseif (count($Datos) == 1) {
                $diasreservados = $Datos[0]['waiting'];
                $PeridoUno = $Datos[0]['period'];
                if ($dias <= $diasreservados) {
                    $menosWaiting = $diasreservados - $dias;
                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $PeridoUno)->update([
                        'waiting' => $menosWaiting,
                    ]);
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                // dd('Se rechazó la solicitud exitosamente.');
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
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
                ->whereIn('request_type_id', [2, 3, 4, 5])
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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
                return back()->with('error', 'Debes enviar al menos un día de vacaciones.');
            }

            // Convertir ambos arrays a conjuntos (sets) para la comparación
            $diasSet = collect($dias)->unique()->sort()->values();
            $datesSet = collect($dates)->unique()->sort()->values();

            // Comparar los conjuntos para encontrar diferencias
            $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
            $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

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
                    'waiting' => $vaca->waiting,
                    'days_enjoyed' => $vaca->days_enjoyed,
                    'days_availables' => $vaca->days_availables
                ];
            }

            if (count($Datos) > 1) {
                $PrimerPeriodo = (int) $Datos[0]['dv'];
                $SegundoPeriodo = (int) $Datos[1]['dv'];
                $totalambosperidos = $PrimerPeriodo + $SegundoPeriodo;
            } elseif (count($Datos) == 1) {
                $totalambosperidos = $Datos[0]['dv'];
            }

            if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                DB::table('vacation_requests')->where('id', $request->id)->update([
                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                ]);
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

                    $Waiting1 = $Datos[0]['waiting'];
                    $Vacaciones = $Datos[0]['dv'];
                    if ((($Waiting1 - $diaseliminar) + $diasneuvos) > $Vacaciones) {
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

                    $Waitings = $Datos[0]['waiting'] + $Datos[1]['waiting'];
                    $Vacaciones = $Datos[0]['dv'] + $Datos[1]['dv'];
                    $prueba = (($Waitings - $diaseliminar) + $diasneuvos);

                    if ((($Waitings - $diaseliminar) + $diasneuvos) > $Vacaciones) {
                        return back()->with('error', 'Verifica tu disponibilidad de vacaciones');
                    }
                }

                //DÍAS A ELIMINAR
                if (!$missingInDates->isEmpty()) {
                    $eliminar = $missingInDates->implode(', ');
                    $eliminarArray = explode(', ', $eliminar);
                    $dias = count($eliminarArray);

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
                            'waiting' => $vaca->waiting,
                            'days_enjoyed' => $vaca->days_enjoyed,
                            'days_availables' => $vaca->days_availables
                        ];
                    }


                    if (count($Datos) > 1) {
                        $datoswaiting = $Datos[0]['waiting'];
                        $datoswaitingdos = $Datos[1]['waiting'];
                        $peridoUno = $Datos[0]['period'];
                        $peridoDos = $Datos[1]['period'];
                        if ($dias <= $datoswaiting) {
                            //return 0;
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
                            ]);
                            $menosWaiting = $datoswaiting - $dias;
                            DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                                'waiting' => $menosWaiting
                            ]);
                        } elseif ($dias > $datoswaiting) {
                            $restawaiting = ($datoswaiting - $dias) * (-1);
                            $finalwaitinguno = $dias - $restawaiting;
                            $waiting2 = $datoswaitingdos - $restawaiting;

                            if (($finalwaitinguno <= $datoswaiting) && ($restawaiting <= $datoswaitingdos)) {
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
                                ]);

                                $menosWaiting = $datoswaiting - $finalwaitinguno;
                                ////waiting 1////
                                DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                                    'waiting' => $menosWaiting,

                                ]);
                                ////waiting 2////
                                DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoDos)->update([
                                    'waiting' => $waiting2,
                                ]);
                            }
                        } else {
                            //dd('No tienes días reservados.');
                            return back()->with('error', 'No tienes días reservados.');
                        }
                    } elseif (count($Datos) == 1) {
                        $diasreservados = $Datos[0]['waiting'];
                        $PeridoUno = $Datos[0]['period'];
                        if ($dias <= $diasreservados) {
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
                            ]);

                            $menosWaiting = $diasreservados - $dias;
                            DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $PeridoUno)->update([
                                'waiting' => $menosWaiting,
                            ]);
                        }
                    }
                }

                //NUEVOS DÍAS//
                if (!$missingInDias->isEmpty()) {
                    $newVacaciones = DB::table('vacations_availables')
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
                            'days_availables' => $vaca->days_availables
                        ];
                    }

                    ///Estos días son nuevos en el arreglo, se deben agregar a la solicitud:
                    $registrar = $missingInDias->implode(', ');
                    $registrarArray = explode(', ', $registrar);
                    $diasTotales = count($registrarArray);

                    if (count($Datosnew) > 1) {
                        // Extracción de datos de los dos periodos
                        $PrimerPeriodo = (int) $Datosnew[0]['dv'];
                        $Periodo = $Datosnew[0]['period'];
                        $SegundoPeriodo = (int) $Datosnew[1]['dv'];
                        $Periododos = $Datosnew[1]['period'];
                        $primerWaiting = $Datosnew[0]['waiting'];
                        $segundoWaiting = $Datosnew[1]['waiting'];
                        $primerDaysEnjoyed = $Datosnew[0]['days_enjoyed'];
                        $SegundoDaysEnjoyed = $Datosnew[1]['days_enjoyed'];


                        // Cálculos de disponibilidad
                        $totalAmbosPeriodos = $PrimerPeriodo + $SegundoPeriodo;
                        $Disponibilidad1 = $primerWaiting + $primerDaysEnjoyed;
                        $Disponibilidad2 = $segundoWaiting + $SegundoDaysEnjoyed;
                        $prueba = $Disponibilidad1 + $Disponibilidad2;

                        if ($diasTotales > $totalAmbosPeriodos) {
                            return  back()->with('error', 'No cuentas con los días solicitados.');
                        }

                        if ($diasTotales <= $totalAmbosPeriodos) {
                            // Caso donde los días solicitados están dentro del primer periodo
                            if ($diasTotales <= $PrimerPeriodo && $PrimerPeriodo > 0) {
                                $cercadv = $diasTotales + $primerWaiting;
                                $cercadv2 = $diasTotales + $segundoWaiting;

                                if ($cercadv <= $PrimerPeriodo) {
                                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periodo)->update([
                                        'waiting' => $cercadv
                                    ]);

                                    foreach ($registrarArray as $registro) {
                                        VacationDays::create([
                                            'day' => $registro,
                                            'vacation_request_id' => $Solicitud->id,
                                            'status' => 0,
                                        ]);
                                    }

                                    DB::table('vacation_requests')->where('id', $request->id)->update([
                                        'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                        'details' => $request->details == null ? $Solicitud->details : $request->details,
                                        'file' => $request->archivos == null ? $Solicitud->file : $path,
                                    ]);
                                } elseif ($cercadv2 <= $SegundoPeriodo) {
                                    $resta = $PrimerPeriodo - $primerWaiting;
                                    if ($resta >= 0) {
                                        $proxdv = $diasTotales - $resta;
                                        $nuevodv = $diasTotales - $proxdv;
                                        $newdv = $primerWaiting + $nuevodv;
                                        $prodv = $proxdv + $segundoWaiting;
                                        if ($newdv <= $PrimerPeriodo && $prodv  <= $SegundoPeriodo) {
                                            DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periodo)->update([
                                                'waiting' => $newdv
                                            ]);
                                            DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periododos)->update([
                                                'waiting' => $prodv
                                            ]);

                                            DB::table('vacation_requests')->where('id', $request->id)->update([
                                                'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                                'details' => $request->details == null ? $Solicitud->details : $request->details,
                                                'file' => $request->archivos == null ? $Solicitud->file : $path,
                                            ]);

                                            foreach ($registrarArray as $registro) {
                                                VacationDays::create([
                                                    'day' => $registro,
                                                    'vacation_request_id' => $Solicitud->id,
                                                    'status' => 0,
                                                ]);
                                            }
                                        }
                                    }
                                } else {
                                    return back()->with('error', 'Asegúrate que no tienes vacaciones por autorizar, ya que tienes días disponibles, pero están en espera.');
                                }
                                //return back()->with('message', 'Vacaciones actualizadas correctamente. 1');
                            }

                            // Caso donde los días solicitados están en ambos periodos
                            if ($diasTotales > $PrimerPeriodo) {
                                $faltan = $diasTotales - $PrimerPeriodo;
                                $ambosWaiting = $primerWaiting + $segundoWaiting;
                                if ($ambosWaiting <= $totalAmbosPeriodos) {
                                    $restadedv = $diasTotales - $faltan;
                                    $cercadv = $restadedv + $primerWaiting;
                                    if ($cercadv <= $PrimerPeriodo && $faltan <= $SegundoPeriodo && $PrimerPeriodo > 0) {
                                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periodo)->update([
                                            'waiting' => $restadedv
                                        ]);

                                        DB::table('vacation_requests')->where('id', $request->id)->update([
                                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        ]);

                                        foreach ($registrarArray as $registro) {
                                            VacationDays::create([
                                                'day' => $registro,
                                                'vacation_request_id' => $Solicitud->id,
                                                'status' => 0,
                                            ]);
                                        }
                                        if ($faltan > 0 && $PrimerPeriodo > 0) {
                                            if ($SegundoPeriodo > 0) {
                                                if ($faltan <= $SegundoPeriodo) {
                                                    if ($segundoWaiting == $SegundoPeriodo || $segundoWaiting > $SegundoPeriodo) {
                                                        return  back()->with('No tienes mas días');
                                                    }
                                                    $cercadv2 = $faltan + $segundoWaiting;
                                                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periododos)->update([
                                                        'waiting' => $cercadv2
                                                    ]);
                                                } else {
                                                    return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                                                }
                                            }
                                        }
                                    } elseif ($SegundoPeriodo > 0 && $PrimerPeriodo == 0 && ($segundoWaiting + $diasTotales) <= $SegundoPeriodo) {
                                        $prueba = $diasTotales + $segundoWaiting;
                                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periododos)->update([
                                            'waiting' => $prueba
                                        ]);

                                        DB::table('vacation_requests')->where('id', $request->id)->update([
                                            'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                            'details' => $request->details == null ? $Solicitud->details : $request->details,
                                            'file' => $request->archivos == null ? $Solicitud->file : $path,
                                        ]);

                                        foreach ($registrarArray as $registro) {
                                            VacationDays::create([
                                                'day' => $registro,
                                                'vacation_request_id' => $Solicitud->id,
                                                'status' => 0,
                                            ]);
                                        }
                                    } else {
                                        return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                                    }

                                    /* if ($faltan > 0 && $PrimerPeriodo > 0) {
                                        if ($SegundoPeriodo > 0) {
                                            if ($faltan <= $SegundoPeriodo) {
                                                if ($segundoWaiting == $SegundoPeriodo || $segundoWaiting > $SegundoPeriodo) {
                                                    return  back()->with('No tienes mas días');
                                                }
                                                $cercadv2 = $faltan + $segundoWaiting;
                                                DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periododos)->update([
                                                    'waiting' => $cercadv2
                                                ]);

                                                DB::table('vacation_requests')->where('id', $request->id)->update([
                                                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                                                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                                                ]);

                                                foreach ($registrarArray as $registro) {
                                                    VacationDays::create([
                                                        'day' => $registro,
                                                        'vacation_request_id' => $Solicitud->id,
                                                        'status' => 0,
                                                    ]);
                                                }
                                            } else {
                                                return back()->with('error', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes.');
                                            }
                                        }
                                    } */
                                    //return back()->with('message', 'Vacaciones actualizadas correctamente.');
                                } else {
                                    return  back()->with('error', 'No te cuentas con los días solicitados.');
                                }
                            }
                        }
                    }

                    if (count($Datosnew) == 1) {
                        $totalunsoloperido = $Datosnew[0]['dv'];
                        $Periodo = $Datosnew[0]['period'];
                        $primerWaiting = $Datosnew[0]['waiting'];
                        $primerDaysEnjoyed = $Datosnew[0]['days_enjoyed'];
                        $dvupdate = $primerWaiting + $diasTotales;
                        $Disponibilidad = $primerWaiting + $primerDaysEnjoyed;

                        $diaseliminar = 0;
                        if (!$missingInDates->isEmpty()) {
                            $eliminar = $missingInDates->implode(', ');
                            $eliminarArray = explode(', ', $eliminar);
                            $diaseliminar = count($eliminarArray);
                        }
                        $prueba = ($primerWaiting - $diaseliminar) + $diasTotales;

                        if ($dvupdate  > $totalunsoloperido) {
                            return back()->with('error', 'No cuentas con los días solicitados.');
                        }

                        if ($prueba > $totalunsoloperido) {
                            return back()->with('error', 'Asegurate que no tengas solicitudes pendientes.');
                        }

                        if ($diasTotales <= $totalunsoloperido) {
                            //dd($dvupdate);
                            if ($diasTotales <= $dvupdate) {
                                DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $Periodo)->update([
                                    'waiting' => $dvupdate,
                                ]);

                                DB::table('vacation_requests')->where('id', $request->id)->update([
                                    'reveal_id' => $request->reveal_id == null ? $Solicitud->reveal_id : $request->reveal_id,
                                    'details' => $request->details == null ? $Solicitud->details : $request->details,
                                    'file' => $request->archivos == null ? $Solicitud->file : $path,
                                ]);

                                foreach ($registrarArray as $registro) {
                                    VacationDays::create([
                                        'day' => $registro,
                                        'vacation_request_id' => $Solicitud->id,
                                        'status' => 0,
                                    ]);
                                }
                            }
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
                ->toArray();  // Convertir a array para facilitar la comparación

            $datesupdate = $request->dates;
            $dates = json_decode($datesupdate, true);
            $diasTotales = count($dates);

            ///VACACIONES PENDIENTES O APROBADAS///
            $vacaciones = DB::table('vacation_requests')
                ->where('user_id', $user->id)
                ->whereIn('request_type_id', [1, 3, 4, 5])
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

            if ($request->ausenciaTipo == 'salida_antes') {

                $hora5PM = Carbon::today()->setHour(17)->setMinute(0)->setSecond(0);
                $start = $request->hora_salida;

                $diferenciaEnMinutos = $hora5PM->diffInMinutes($start);
                // Convertir la diferencia a horas y minutos
                $diferenciaEnHoras = intdiv($diferenciaEnMinutos, 60); // Horas
                $diferenciaEnMinutosRestantes = $diferenciaEnMinutos % 60; // Minutos restantes

                if ($diferenciaEnHoras > 4) {
                    return back()->with('error', 'No puedes tomar más de cuatro horas.');
                }

                $horaSalidaCarbon = Carbon::createFromFormat('H:i', $start);

                if ($horaSalidaCarbon->greaterThanOrEqualTo($hora5PM)) {
                    return back()->with('error', 'No se pueden crear solicitudes después de las 17 horas.');
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
                        'start' => $horaSalidaCarbon,
                        'end' => null,
                    ]);
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
                        ]);
                        if ($horaSalidaCarbon->lessThan($hora5PM)) {
                            foreach ($dates as $dia) {
                                VacationDays::create([
                                    'day' => $dia,
                                    'start' => $horaSalidaCarbon,
                                    'vacation_request_id' => $request->id,
                                    'status' => 0,
                                ]);
                            }
                        } else {
                            return back()->with('error', 'No puedes seleccionar la misma hora de salida');
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
                }
                return back()->with('message', 'Se actualizó correctamente la solicitud.');
            }

            if ($request->ausenciaTipo == 'salida_durante') {
                $hora8AM = Carbon::today()->setHour(8)->setMinute(0)->setSecond(0);
                $hora5PM = Carbon::today()->setHour(17)->setMinute(0)->setSecond(0);
                $start = $request->hora_salida;
                $end = $request->hora_regreso;

                $hora1Carbon = Carbon::createFromFormat('H:i', $start);
                $hora2Carbon = Carbon::createFromFormat('H:i', $end);

                // Calcular la diferencia en minutos
                $diferenciaEnMinutos = $hora2Carbon->diffInMinutes($hora1Carbon);

                // Convertir la diferencia a horas y minutos
                $diferenciaEnHoras = intdiv($diferenciaEnMinutos, 60); // Horas
                $diferenciaEnMinutosRestantes = $diferenciaEnMinutos % 60; // Minutos restantes

                if ($diferenciaEnHoras > 4) {
                    return back()->with('error', 'No puedes tomar más de cuatro horas.');
                }

                // Convertir ambos arrays a conjuntos (sets) para la comparación
                $diasSet = collect($dias)->unique()->sort()->values();
                $datesSet = collect($dates)->unique()->sort()->values();

                // Comparar los conjuntos para encontrar diferencias
                $missingInDias = $datesSet->diff($diasSet);  // Días en $dates pero no en $dias
                $missingInDates = $diasSet->diff($datesSet); // Días en $dias pero no en $dates

                if ($missingInDias->isEmpty() && $missingInDates->isEmpty()) {
                    if ($hora1Carbon->greaterThanOrEqualTo($hora8AM) && $hora1Carbon->lessThan($hora5PM) && $hora2Carbon->greaterThanOrEqualTo($hora1Carbon) && $hora2Carbon->lessThanOrEqualTo($hora5PM)) {
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

                        return back()->with('message', 'Solicitud actualizada correctamente.');
                    } else {
                        // Hora fuera del rango permitido
                        return back()->with('error', 'La hora de inicio está fuera del rango permitido.');
                    }
                } else {
                    if (!$missingInDias->isEmpty()) {
                        if ($hora1Carbon->greaterThanOrEqualTo($hora8AM) && $hora1Carbon->lessThan($hora5PM) && $hora2Carbon->greaterThanOrEqualTo($hora1Carbon) && $hora2Carbon->lessThanOrEqualTo($hora5PM)) {
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

                            foreach ($dates as $dia) {
                                VacationDays::create([
                                    'day' => $dia,
                                    'start' => $hora1Carbon,
                                    'end' => $hora2Carbon,
                                    'vacation_request_id' => $request->id,
                                    'status' => 0,
                                ]);
                            }
                        } else {
                            // Hora fuera del rango permitido
                            return back()->with('error', 'La hora de inicio está fuera del rango permitido.');
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
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

            if ($diasTotales < 5) {
                return back()->with('message', 'Debes ingresar los cinco días.');
            }

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
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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
                    ]);
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            }
        }

        ///PERMISOS ESPECIALES
        if ($request->request_type_id == 5) {
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
                ->whereIn('request_type_id', [1, 2, 3, 4])
                ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

            $Ingreso = DB::table('employees')->where('user_id', $user->id)->first();
            $jefedirecto = $Ingreso->jefe_directo_id;
            $fechaIngreso = Carbon::parse($Ingreso->date_admission);
            $fechaActual = Carbon::now();
            $mesesTranscurridos = $fechaIngreso->diffInMonths($fechaActual);

            if (!empty($diasParecidos)) {
                return back()->with('error', 'Verifica que los días seleccionados no los hayas solicitado anteriormente.');
            }

            if ($request->Permiso == 'Fallecimiento de un familiar') {
                if ($diasTotales < 3) {
                    return back()->with('error', 'Debes ingresar los tres días permitidos.');
                }

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
                        ]);

                        foreach ($eliminarArray as $eliminar) {
                            $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                            DB::table('vacation_days')->where('id', $idfecha)->delete();
                        }
                    }
                    return back()->with('message', 'Solicitud actualizada correctamente.');
                }
                return back()->with('message', 'Solicitud actualizada correctamente.');
            }

            if ($request->Permiso == 'Matrimonio del colaborador') {
                if ($diasTotales < 5) {
                    return back()->with('error', 'Debes ingresar los cinco días permitidos.');
                }
                if ($diasTotales > 5) {
                    return back()->with('error', 'Solo tienes derecho a tomar cinco días.');
                }

                if ($diasTotales == 5) {
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
                            ]);

                            foreach ($eliminarArray as $eliminar) {
                                $idfecha = VacationDays::where('vacation_request_id', $Solicitud->id)->where('day', $eliminar)->value('id');
                                DB::table('vacation_days')->where('id', $idfecha)->delete();
                            }
                        }
                        return back()->with('message', 'Actualización exitosa.');
                    }
                } else {
                    return back()->with('error', 'Debes tomar tus cinco días.');
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
                    return back()->with('message', 'Se actualizó correctamente la solicitud.');
                }
            }

            if ($request->Permiso == 'Asuntos personales') {
                ///VACACIONES PENDIENTES O APROBADAS///
                //dd($request);
                $vacaciones = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->whereIn('request_type_id', [5])
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
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

                if ($mesesTranscurridos < 3) {
                    return back()->with('error', 'No has cumplido el tiempo suficiente para solicitar este permiso.');
                }

                if ($diasTotales > 1) {
                    return back()->with('error', 'Solo puedes tomar un día a la vez.');
                }

                $currentYear = date('Y');
                $AsuntosPersonales = DB::table('vacation_requests')
                    ->where('user_id', $user->id)
                    ->where('request_type_id', 5)
                    ->whereNotIn('direct_manager_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereNotIn('rh_status', ['Rechazada', 'Cancelada por el usuario'])
                    ->whereBetween('created_at', ["$currentYear-01-01 00:00:00", "$currentYear-12-31 23:59:59"])
                    ->get();

                $contadorAsuntosPersonales = 0;
                foreach ($AsuntosPersonales as $asuntoPersonal) {
                    $moreInformation = json_decode($asuntoPersonal->more_information, true);
                    if (!empty($moreInformation) && isset($moreInformation[0]['Tipo_de_permiso_especial']) && $moreInformation[0]['Tipo_de_permiso_especial'] === 'Asuntos personales') {
                        $contadorAsuntosPersonales++;
                    }
                }

                if ($contadorAsuntosPersonales >= 3) {
                    return back()->with('error', 'Solo tienes derecho a 3 permisos especiales por año.');
                }

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

        if ($Solicitud->request_type_id == 1) {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_availables')
                ->where('users_id', $Solicitud->user_id)
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
                    'days_availables' => $vaca->days_availables
                ];
            }

            $dias = VacationDays::where('vacation_request_id', $request->id)->count();
            if (count($Datos) > 1) {
                $datoswaiting = $Datos[0]['waiting'];
                $datoswaitingdos = $Datos[1]['waiting'];
                $peridoUno = $Datos[0]['period'];
                $peridoDos = $Datos[1]['period'];
                if ($dias <= $datoswaiting) {
                    $menosWaiting = $datoswaiting - $dias;

                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                        'waiting' => $menosWaiting
                    ]);
                } elseif ($dias > $datoswaiting) {
                    $restawaiting = ($datoswaiting - $dias) * (-1);
                    $finalwaitinguno = $dias - $restawaiting;
                    $waiting2 = $datoswaitingdos - $restawaiting;

                    if (($finalwaitinguno <= $datoswaiting) && ($restawaiting <= $datoswaitingdos)) {
                        $menosWaiting = $datoswaiting - $finalwaitinguno;
                        ////waiting 1////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                            'waiting' => $menosWaiting,

                        ]);
                        ////waiting 2////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoDos)->update([
                            'waiting' => $waiting2,
                        ]);
                    }
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
            } elseif (count($Datos) == 1) {
                $diasreservados = $Datos[0]['waiting'];
                $PeridoUno = $Datos[0]['period'];
                if ($dias <= $diasreservados) {
                    $menosWaiting = $diasreservados - $dias;
                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $PeridoUno)->update([
                        'waiting' => $menosWaiting,
                    ]);
                } else {
                    return back()->with('error', 'No tienes días reservados.');
                }
                // dd('Se rechazó la solicitud exitosamente.');
                return back()->with('message', 'Se rechazó la solicitud exitosamente.');
            }
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Rechazada',
                'commentary' => $request->commentary
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 0
            ]);
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
        if ($Solicitud->direct_manager_status == 'Pendiente' || $Solicitud->direct_manager_status == 'Rechazada') {
            //dd('La solicitud se encuentra Pendiente o fue Rechazada por su jefe directo.');
            return back()->with('error', 'La solicitud se encuentra Pendiente o fue Rechazada por su jefe directo.');
        }

        if ($Solicitud->request_type_id == 1) {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Aprobada'
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 1
            ]);

            $fechaActual = Carbon::now();
            $Vacaciones = DB::table('vacations_availables')
                ->where('users_id', $Solicitud->user_id)
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
                    'days_availables' => $vaca->days_availables
                ];
            }

            $dias = VacationDays::where('vacation_request_id', $request->id)->count();
            if (count($Datos) > 1) {
                $datoswaiting = $Datos[0]['waiting'];
                $datoswaitingdos = $Datos[1]['waiting'];
                $peridoUno = $Datos[0]['period'];
                $peridoDos = $Datos[1]['period'];
                if ($dias <= $datoswaiting) {
                    $menosWaiting = $datoswaiting - $dias;
                    $nuevodaysenjoyed = $Datos[0]['days_enjoyed'] + $dias;
                    $nuevodv = $Datos[0]['dv'] - $dias;


                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                        'waiting' => $menosWaiting,
                        'days_enjoyed' => $nuevodaysenjoyed,
                        'dv' => $nuevodv
                    ]);
                } elseif ($dias > $datoswaiting) {
                    $restawaiting = ($datoswaiting - $dias) * (-1);
                    $finalwaitinguno = $dias - $restawaiting;
                    $waiting2 = $datoswaitingdos - $restawaiting;
                    $nuevosdisfrutados = $Datos[1]['days_enjoyed'] + $restawaiting;
                    $nuevodvperiododos = $Datos[1]['dv'] - $restawaiting;

                    if (($finalwaitinguno <= $datoswaiting) && ($restawaiting <= $datoswaitingdos)) {
                        $menosWaiting = $datoswaiting - $finalwaitinguno;
                        $nuevodaysenjoyed = $Datos[0]['days_enjoyed'] + $finalwaitinguno;
                        $nuevodv = $Datos[0]['dv'] - $finalwaitinguno;

                        ////waiting 1////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoUno)->update([
                            'waiting' => $menosWaiting,
                            'days_enjoyed' => $nuevodaysenjoyed,
                            'dv' => $nuevodv
                        ]);
                        ////waiting 2////
                        DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $peridoDos)->update([
                            'waiting' => $waiting2,
                            'days_enjoyed' => $nuevosdisfrutados,
                            'dv' => $nuevodvperiododos
                        ]);
                    }
                } else {
                    return back()->with('error', 'No tienes vacaciones disponibles.');
                }

                return back()->with('message', 'Autorización exitosa');
            } elseif (count($Datos) == 1) {
                $diasreservados = $Datos[0]['waiting'];
                $diasdisponibles = $Datos[0]['dv'];
                $totalvacaionestomadas = $Datos[0]['days_enjoyed'];
                $PeridoUno = $Datos[0]['period'];
                if ($dias <= $diasreservados) {
                    $menosWaiting = $diasreservados - $dias;
                    $nuevodaysenjoyed = $totalvacaionestomadas + $dias;
                    $nuevodv =  $diasdisponibles - $dias;
                    DB::table('vacations_availables')->where('users_id', $Solicitud->user_id)->where('period', $PeridoUno)->update([
                        'waiting' => $menosWaiting,
                        'days_enjoyed' => $nuevodaysenjoyed,
                        'dv' => $nuevodv
                    ]);
                } else {
                    return back()->with('error', 'No tienes vacaciones disponibles.');
                }
            }
            return back()->with('message', 'Autorización exitosa');
        } else {
            DB::table('vacation_requests')->where('id', $request->id)->update([
                'rh_status' => 'Aprobada'
            ]);

            DB::table('vacation_days')->where('vacation_request_id', $request->id)->update([
                'status' => 1
            ]);

            return back()->with('message', 'Autorización exitosa');
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
        $vacaciones = DB::table('vacations_availables')
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
                        DB::table('vacations_availables')
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
                        DB::table('vacations_availables')
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
                        DB::table('vacations_availables')
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
                        DB::table('vacations_availables')
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
        dd($aniversariosPorUsuario);
    }
}
