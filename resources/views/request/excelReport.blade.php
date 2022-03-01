<table>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Fecha de creación</th>
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
                <td>{{ $request->id }}</td>
                <td>{{ $request->created_at }}</td>
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