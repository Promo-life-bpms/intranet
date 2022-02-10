<?php

namespace App\Exports;

use App\Models\Request;
use App\Models\RequestCalendar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;

class FilterRequestExport implements FromView, ShouldAutoSize
{
 
    use Exportable;

    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
    
    public function view(): View
    {
        return view('request.excelFilterReport', [
            'requests' => Request::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->whereRaw('DATE(created_at) >= ?', [$this->start])->whereRaw('DATE(created_at) <= ?', [$this->end])->get(),
/*             'requests' =>  Request::all()->where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada'), */
            'requestDays' => RequestCalendar::all(),
        ]);
    }
}
