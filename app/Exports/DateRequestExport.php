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
class DateRequestExport implements FromView
{
    use Exportable;

    protected $start, $end;

    public function __construct($start, $end,$daySelected)
    {
        $this->start = $start;
        $this->end = $end;
        $this->daySelected = $daySelected;
    }
    
    public function view(): View
    {
        return view('request.excelFilterReport', [

            'requestDays' => RequestCalendar::all()->where('start', '>=', $this->start)->where('end', '<=',$this->end),
    /*         'daySelected' => RequestCalendar::all()->where('start', '>=', $this->start)->where('end', '<=',$this->end)->pluck('requests_id','requests_id'), */
            'requests' => Request ::where('direct_manager_status', 'Aprobada')->where('human_resources_status', 'Aprobada')->whereIn('id',$this->daySelected)->get(),
        ]);
    }
}
