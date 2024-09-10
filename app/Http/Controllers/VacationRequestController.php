<?php

namespace App\Http\Controllers;

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

class VacationRequestController extends Controller
{


    public function index(Request $request)
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
                        $users[$emp->user->id] = //Guardar el user name , lastname y el id
                            [
                                'name' => $emp->user->name . " " . $emp->user->lastname,
                                'id' => $emp->user->id
                            ];
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

            // Crear un objeto en lugar de un array
            $solicitud = new \stdClass();
            $solicitud->id_request = $vacacion->id;
            $solicitud->tipo = $typeRequest;
            $solicitud->details = $vacacion->details;
            $solicitud->reveal_id = $nameResponsable->name . ' ' . $nameResponsable->lastname;
            $solicitud->direct_manager_id = $nameManager->name . ' ' . $nameManager->lastname;
            $solicitud->direct_manager_status = $vacacion->direct_manager_status;
            $solicitud->rh_status = $vacacion->rh_status;
            $solicitud->file = $vacacion->file == null ? 'No hay justificante' : $vacacion->file;
            $solicitud->commentary = $vacacion->commentary == null ? 'No hay un comentario' : $vacacion->commentary;
            $solicitud->days = $dias;

            // Agregar el objeto al array de solicitudes
            $solicitudes[] = $solicitud;
        }

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
                'days_availables' => $vaca->days_availables
            ];
        }

        if (count($Datos) > 1) {
            $diasreservados = $Datos[0]['waiting'] + $Datos[1]['waiting'];
            $diasdisponibles = $Datos[0]['dv'] + $Datos[1]['dv'];
            $totalvacaciones = $Datos[0]['days_availables'] + $Datos[1]['days_availables'];
            $totalvacaionestomadas = $Datos[0]['days_enjoyed'] + $Datos[1]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
        } elseif (count($Datos) == 1) {
            $diasreservados = $Datos[0]['waiting'];
            $diasdisponibles = $Datos[0]['dv'];
            $totalvacaciones = $Datos[0]['days_availables'];
            $totalvacaionestomadas = $Datos[0]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
        }

        return view('request.vacations-collaborators', compact('users', 'solicitudes', 'diasreservados', 'diasdisponibles', 'totalvacaciones', 'totalvacaionestomadas', 'porcentajetomadas'));
    }

    public function CreatePurchase(Request $request)
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

        if ($diasTotales > $totalambosperidos) {
            return back()->with('message', 'No cuentas con días suficientes');
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
            $totalDisponibilidad = $Disponibilidad1 + $Disponibilidad2;
            $sumaWaiting = $primerWaiting + $segundoWaiting;
            $sumaDaysAvailables = $primerDaysAvailables + $segundoDaysAvailables;
            $prueba = $Disponibilidad1 + $Disponibilidad2;

            if ($diasTotales > $totalAmbosPeriodos) {
                return  back()->with('message', 'No cuentas con los días solicitados.');
            }

            if ($diasTotales <= $totalAmbosPeriodos) {
                // Caso donde los días solicitados están dentro del primer periodo
                if ($diasTotales <= $PrimerPeriodo) {
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
                        if ($resta > 0) {
                            $proxdv = $diasTotales - $resta;
                            $nuevodv = $diasTotales - $proxdv;
                            $newdv = $primerWaiting + $nuevodv;
                            $prodv = $proxdv + $segundoWaiting;
                            if($newdv <= $PrimerPeriodo && $prodv  <= $SegundoPeriodo)
                            {
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
                        
                        /* $prueba = $diasTotales + $segundoWaiting;
                        if($prueba <= $SegundoPeriodo)
                        {
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
                        } */
                    } else {
                        return back()->with('message', 'Asegúrate que no tienes vacaciones por autorizar, ya que tienes días disponibles, pero están en espera.');
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

                        if ($cercadv <= $PrimerPeriodo && $faltan <= $SegundoPeriodo) {
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
                        } else {
                            return back()->with('message', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes. (1)');
                        }

                        if ($faltan > 0) {
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
                                    return back()->with('message', 'Asegúrate que no tienes días solicitados por aprobar, ya que hemos detectado que tienes vacaciones pendientes. (2)');
                                }
                            }
                        }
                        return back()->with('message', 'Vacaciones actualizadas correctamente.');
                    } else {
                        return  back()->with('message', 'No te alcanza para los días solicitados.');
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

            if ($diasTotales > $totalunsoloperido || $Disponibilidad  > $totalunsoloperido) {
                return back()->with('message', 'No cuentas con los días solicitados.');
            }

            if ($dvupdate > $totalunsoloperido) {
                return back()->with('message', 'Asegurate que no tengas solicitudes pendientes.');
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
                    return back()->with('message', 'Vacaciones actualizadas');
                }
            }
        }

        return back()->with('message', 'Se creo tu solicitud de vacaciones.');
    }

    public function AuthorizePermissionBoss($id)
    {
        $user = auth()->user();

        /* $request->validate([
            'id' => 'required',
        ]); */

        DB::table('vacation_requests')->where('id', $id)->update([
            'direct_manager_status' => 'Aprobada'
        ]);

        DB::table('vacation_days')->where('vacation_request_id', $id)->update([
            'status' => 1
        ]);


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
                'days_availables' => $vaca->days_availables
            ];
        }

        $dias = VacationDays::where('vacation_request_id', $id)->count();
        if (count($Datos) > 1) {
            $datoswaiting = $Datos[0]['waiting'];
            $datoswaitingdos = $Datos[1]['waiting'];
            $peridoUno = $Datos[0]['period'];
            $peridoDos = $Datos[1]['period'];
            if ($dias <= $datoswaiting) {
                $menosWaiting = $datoswaiting - $dias;
                $nuevodaysenjoyed = $Datos[0]['days_enjoyed'] + $dias;
                $nuevodv = $Datos[0]['dv'] - $dias;


                DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $peridoUno)->update([
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
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $peridoUno)->update([
                        'waiting' => $menosWaiting,
                        'days_enjoyed' => $nuevodaysenjoyed,
                        'dv' => $nuevodv
                    ]);
                    ////waiting 2////
                    DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $peridoDos)->update([
                        'waiting' => $waiting2,
                        'days_enjoyed' => $nuevosdisfrutados,
                        'dv' => $nuevodvperiododos
                    ]);
                }
            }
        } elseif (count($Datos) == 1) {
            $diasreservados = $Datos[0]['waiting'];
            $diasdisponibles = $Datos[0]['dv'];
            $totalvacaciones = $Datos[0]['days_availables'];
            $totalvacaionestomadas = $Datos[0]['days_enjoyed'];
            $porcentajetomadas = (($totalvacaionestomadas / $totalvacaciones) * 100);
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
