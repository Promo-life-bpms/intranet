<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class MonthController extends Controller
{
    public function __invoke()
    {
        $monthEmployeeController = MonthController::getEmpoyeeMonth();
        return view('month.index',compact('monthEmployeeController'));
    }

    public static function getEmpoyeeMonth()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://evaluacion.promolife.lat/api/empleado-del-mes");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($res);
        $users = [];
        $employeesMonth = [];
        foreach ($res as $data) {
            try {
                $user = User::where('email', '=', $data->email)->firstOrFail();
                if ($user != null) {
                    array_push($users, $user);
                    array_push($employeesMonth, (object)[
                        'name' => $user->name . ' ' . $user->lastname,
                        'position' => $data->puesto,
                        'star' => $data->star,
                        'photo' => $user->image
                    ]);
                }
            } catch (Exception $e) {
            }
        }
        return $employeesMonth;
    }
}
