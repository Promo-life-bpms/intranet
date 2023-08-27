<?php

namespace App\Http\Livewire;

use App\Models\Request as ModelRequest;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class ListRequestComponent extends Component
{
    use WithPagination, WithFileUploads;
    protected $paginationTheme = 'bootstrap';

    public $request, $archivos_permiso, $editarJustificante;

    public function render()
    {
        $myrequests = auth()->user()->employee->requestDone()->orderBy('created_at', "DESC")->paginate(10);
        return view('livewire.list-request-component', ['myrequests' => $myrequests]);
    }

    public function deleteRequest(ModelRequest $request)
    {
        $request->visible = false;
        $request->save();
        session()->flash('message', 'Solicitud Eliminada Correctamente.');
    }

    // ver detalle de la solicitud
    public function showRequest(ModelRequest $request)
    {
        $this->request = $request;
        $this->editarJustificante = $request->doc_permiso == null ? true : false;
        $this->dispatchBrowserEvent('showRequest');
    }

    public function subirJustificante(ModelRequest $modelRequest)
    {
        $this->validate([
            'archivos_permiso' => 'required|array',
        ]);

        // Eliminar los archivos anteriores
        if ($modelRequest->doc_permiso != null) {
            $docPermisosAnteriores =  explode(',', $modelRequest->doc_permiso);
            foreach ($docPermisosAnteriores as $docPermisoAnterior) {
                if ($docPermisoAnterior != null) {
                    unlink(public_path('storage/archivosPermisos/' . $docPermisoAnterior));
                }
            }
        }
        $docPermiso =  [];
        $archivos = $this->archivos_permiso;
        if ($archivos != null) {
            foreach ($archivos as $archivo) {
                $n = $archivo->getClientOriginalName();
                $nombreImagen = time() . ' ' . Str::slug($n) . "." . $archivo->getClientOriginalExtension();
                $archivo->storeAs( 'public/archivosPermisos', $nombreImagen );
                array_push($docPermiso, $nombreImagen);
            }
        }

        $modelRequest->update([
            'doc_permiso' => count($docPermiso) > 0 ? implode(',', $docPermiso) : null,
        ]);
        $this->request = $modelRequest;
        $this->editarJustificante = $modelRequest->doc_permiso == null ? true : false;
        session()->flash('updateFile', 'Archivos Cargado Correctamente.');

        // Redirige de nuevo a donde sea apropiado
    }

    //cambiarEditarJustificante
    public function cambiarEditarJustificante()
    {
        $this->editarJustificante = !$this->editarJustificante;
    }
}
