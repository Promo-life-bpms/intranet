<?php

namespace App\Exports;

use App\Models\Vacations;
use App\Models\VacationsAvailablePerUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VacationsExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {
        return view('admin.vacations.export', [
            'vacations' =>  VacationsAvailablePerUser::all(),
        ]);
    }
}