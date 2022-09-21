<?php

namespace App\Http\Livewire;

use App\Models\Publications;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Symfony\Component\HttpFoundation\Request;

class CreatePublication extends Component
{
    public $content, $files;

    public function render()
    {
        return view('livewire.create-publication');
    }

    public function savePublication(Request $request)
    {
        // Obtener los datos de la publicacion
        dd($request);
        dd($this->content, $this->files);
        if (($this->content != '' || $this->files != '')) {
            if ($this->content) {
                $content = $this->content;
            } else {
                $content = '';
            }

            //Crear publicacion
            $publication = Publications::create([
                'user_id' => auth()->user()->id,
                'content_publication' => $content,
            ]);

            if (trim($this->files) != "" || $this->files != null) {
                foreach (explode(',', $this->files) as $item) {
                    # code...
                    //Registar imagen
                    $data = [
                        'resource' => $item,
                        'type_file' => 'photo',
                    ];
                    $publication->files()->create($data);
                }
            }
        }
        dd(1);
    }
}
