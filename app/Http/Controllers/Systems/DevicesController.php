<?php

namespace App\Http\Controllers\Systems;

use App\Http\Controllers\Controller;
use App\Models\Devices;
use App\Models\User;
use Illuminate\Http\Request;
use UAParser\Result\Device;

class DevicesController extends Controller
{
    public function index()
    {
        $users_data = [];

        $users = User::all()->where('status',1);

        foreach($users as $user){

            $user_devices = Devices::all()->where('id', $user->id);

            array_push($users_data, (object)[
                
                'id' => $user->id,
                'fullname' => $user->name . " ". $user->lastname,
                'email' => $user->email,
                'department' => $user->employee->position->department->name,
                'position' => $user->employee->position->name ,
                'devices' => $user_devices,
            ]);
        }

        return view('systems.index', compact('users_data'));
    }
}
