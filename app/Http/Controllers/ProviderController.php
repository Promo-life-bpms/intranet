<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class ProviderController
 * @package App\Http\Controllers
 */
class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers = Provider::all();

        return view('provider.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provider = new Provider();
        return view('provider.create', compact('provider'));
    }
    public function create_import()
    {
        $provider = new Provider();
        return view('provider.createImport');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(Provider::$rules);

        $provider = Provider::create($request->all());

        return redirect()->route('providers.index')
            ->with('success', 'Provider created successfully.');
    }
    public function store_import(Request $request)
    {
        $excel = $request->file('file');
        $rutaArchivo = public_path('storage/excel/') . $excel->getClientOriginalName();
        $excel->move(public_path('storage/excel'), $excel->getClientOriginalName());
        $documento = IOFactory::load($rutaArchivo);


        // TODO: Proceso de importacion
        $documento = IOFactory::load($rutaArchivo);

        #Obtener hoja en el indice que valla del ciclo
        $hojaActual = $documento->getSheet(0);

        # Calcular el máximo valor de la fila como entero, es decir, el
        # límite de nuestro ciclo
        $numeroMayorDeFila = $hojaActual->getHighestRow(); // Numérico
        $letraMayorDeColumna = $hojaActual->getHighestColumn(); // Letra
        # Convertir la letra al número de columna correspondiente

        # Iterar filas con ciclo for e índices
        $provider = [];
        for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
            $celda = $hojaActual->getCellByColumnAndRow(1, $indiceFila);
            $provider['name'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(2, $indiceFila);
            $provider['service'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(3, $indiceFila);
            $provider['type'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(4, $indiceFila);
            $provider['name_contact'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(5, $indiceFila);
            $provider['position'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(6, $indiceFila);
            $provider['tel_office'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(7, $indiceFila);
            $provider['tel_cel'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(8, $indiceFila);
            $provider['email'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(9, $indiceFila);
            $provider['address'] = trim($celda->getValue());
            $celda = $hojaActual->getCellByColumnAndRow(10, $indiceFila);
            $provider['web_page'] = trim($celda->getValue());
            // Registrar el usuario
            print_r($provider);
            $provider = Provider::create($provider);
            $provider = [];
        }
        return redirect()->back();
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $provider = Provider::find($id);

        return view('provider.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $provider = Provider::find($id);

        return view('provider.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Provider $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Provider $provider)
    {
        request()->validate(Provider::$rules);

        $provider->update($request->all());

        return redirect()->route('providers.index')
            ->with('success', 'Provider updated successfully');
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $provider = Provider::find($id)->delete();

        return redirect()->route('providers.index')
            ->with('success', 'Provider deleted successfully');
    }
}
