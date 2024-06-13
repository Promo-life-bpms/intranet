<table>
    <thead>
        <tr>
            <th scope="col">Solicitante</th>
            <th scope="col">Tipo</th>
            <th scope="col">Pago</th>
            <th scope="col">Fechas ausencia</th>
            <th scope="col">Tiempo</th>
            <th scope="col">Motivo</th>
            <th scope="col">Vacaciones disponibles</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                <td>{{ $request->type_request }}</td>
                <td>{{ $request->payment }}</td>
                <td>
                    @foreach ($request->requestDays as $requestDay)
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
                <td>
                    @if ($request->employee->user->vacation)
                        {{ $request->employee->user->vacation->dv }}
                    @else
                        N/A 
                    @endif
                </td>
            </tr>
        @endforeach

    </tbody>
</table>