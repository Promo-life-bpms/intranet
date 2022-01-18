<?php

namespace App\Exports;

use App\Models\Request;
use App\Models\RequestCalendar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FilterRequestExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('request.excelFilterReport', [
            'requests' =>  Request::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada'),
            'requestDays' => RequestCalendar::all(),
        ]);
    }
}
