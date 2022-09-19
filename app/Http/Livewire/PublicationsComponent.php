<?php

namespace App\Http\Livewire;

use App\Models\Publications;
use Livewire\Component;
use Livewire\WithPagination;

class PublicationsComponent extends Component
{
    use WithPagination;

    public $publication;

    public function render()
    {
        $publications = Publications::where('visible', 1)->orderBy('created_at', 'desc')->simplePaginate(10); //get first 10 rows
        return view('livewire.publications-component', ['publications' => $publications]);
    }

    public function eliminar(Publications $publication)
    {
        $publication->visible = 0;
        $publication->save();
        return 1;
    }
}
