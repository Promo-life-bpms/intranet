<?php

namespace App\Http\Livewire;

use App\Models\Publications;
use Livewire\Component;

class PublicationsProfileComponent extends Component
{
    public $user;

    public function render()
    {
        $publications = $this->user->publications()->where('visible', 1)->orderBy('created_at', 'desc')->simplePaginate(10); //get first 10 rows
        return view('livewire.publications-component', ['publications' => $publications]);
    }

    public function eliminar(Publications $publication)
    {
        $publication->visible = 0;
        $publication->save();
        return 1;
    }
}
