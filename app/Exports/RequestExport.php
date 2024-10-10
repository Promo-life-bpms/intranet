<?php

namespace App\Exports;

use App\Models\Request;
use App\Models\RequestCalendar;
use App\Models\VacationDays;
use App\Models\VacationRequest;
use App\Models\Vacations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RequestExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        return view('request.excelReport', [
            'requests' =>  VacationRequest::all()->where('direct_manager_status', 'Aprobada')->where('rh_status', 'Aprobada'),
            'requestDays' => VacationDays::all(),
        ]);
    }
}
