<table>
    <thead>
        <tr>
            <th scope="col">Solicitante</th>
            <th scope="col">Tipo</th>
            <th scope="col">Fechas ausencia</th>
            <th scope="col">Tiempo</th>
            <th scope="col">Motivo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
        <tr>
            <td>
                {{ $request->users->name.' '.$request->users->lastname }}
            </td>

            <td>
                {{ $request->RequestType->type }}
            </td>

            <td>
                @foreach ($requestDays as $requestDay)
                @if ($request->id == $requestDay->vacation_request_id)
                {{ $requestDay->day }} ,
                @endif
                @endforeach
            </td>

            <td>
                @if ($request->request_type_id === 1)
                    Tiempo completo
                @else
                @foreach ($requestDays as $requestDay)
                    @if ($request->id == $requestDay->vacation_request_id)
                        @if ($requestDay->start !== null && $requestDay->end !== null)
                            {{ 'Salida: ' . $requestDay->start . ' Reingreso: ' . $requestDay->end }}
                        @elseif ($requestDay->start === null)
                            {{ 'Reingreso: ' . $requestDay->end }}
                        @endif
                    @endif
                @endforeach
                @endif
            </td>
            <td> 
                {{ $request->details }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>