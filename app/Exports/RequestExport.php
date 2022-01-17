<?php

namespace App\Exports;

use App\Models\Request;
use App\Models\RequestCalendar;
use App\Models\Vacations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;


class RequestExport implements FromView
{

    public function view(): View
    {
        return view('request.excelReport', [
            'requests' =>  Request::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada'),
            'requestDays' => RequestCalendar::all(),
        ]);
    }
}
