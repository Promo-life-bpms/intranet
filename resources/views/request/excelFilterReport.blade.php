<table>
    <thead>
        <tr>
           
            <th>Solicitante</th>
            <th>Tipo</th>
            <th>Pago</th>
            <th>Fechas ausencia</th>
            <th>Tiempo</th>
            <th>Motivo</th>
            <th>Vacaciones disponibles</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                
                <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                <td>{{ $request->type_request }}</td>
                <td>{{ $request->payment }}</td>
                <td>
                    @foreach ($requestDays as $requestDay)
                        @if ($request->id == $requestDay->requests_id)
                            {{ $requestDay->start }} ,

                        @endif
                    @endforeach
                </td>

                <td>
                    @if ($request->start == null)
                    Tiempo completo
                    @else
                        @if ($request->end ==null) 
                        {{'Salida: '. $request->start . ' ' }}
                        @else
                            {{'Salida: '. $request->start . ' ' .'Reingreso:' . ' ' . $request->end }}
                        @endif
                    @endif
                </td>
                <td>{{ $request->reason }}</td>
                <td>{{ $request->employee->user->vacation->dv }} </td>
            </tr>
        @endforeach

    </tbody>
</table>