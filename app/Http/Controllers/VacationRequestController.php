<?php

namespace App\Http\Controllers;

use App\Models\VacationRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VacationRequestController extends Controller
{
    public function CreatePurchase(Request $request)
    {
        $currentDate = Carbon::now();
        $startOfYear = Carbon::create($currentDate->year, 1, 1);
        $endOfYear = Carbon::create($currentDate->year, 12, 31);
        $daysInYear = $endOfYear->diffInDays($startOfYear) + 1;
        return $daysInYear;
    }

    public function CreateVacationRequest(Request $request)
    {
        $user = auth()->user();

        /*  $this->validate($request, [
            'details' => 'required',
            'reveal_id' => 'required'

        ]); */

        if (auth()->user()->employee->jefe_directo_id == null) {
            return back()->with('message', 'No puedes crear solicitudes por que no tienes un jefe directo asignado o no llenaste todos los campos');
        }

        $soliVaca = 9;

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
            ];
        }

        $PrimerPeriodo = $Datos[0]['Vacaciones'];
        $Periodo = $Datos[0]['period'];

        if($soliVaca > $PrimerPeriodo){
            $diasFaltan = $PrimerPeriodo - $soliVaca;
            /* DB::table('vacations_availables')->where('users_id', $user->id)->where('period', $Periodo)->update([
                'dv'=> 0,
            ]); */
        }
        //dd($Datos);

        /* user_id
request_type_id
details
reveal_id
file
commentary
direct_manager_id
direct_manager_status
rh_status */
    }
}
