<?php

namespace App\Exports;

use App\Models\Contact;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class directoryExport implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('admin.contact.export', [
            'userContact' => Contact::all(),
            'departments' => Department::all(),
        ]);
    }
}
