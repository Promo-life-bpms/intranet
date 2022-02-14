
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Ingreso</th>
                <th>Dias de periodos cumplidos</th>
                <th>Dias Actuales</th>
                <th>D.V.</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($vacations as $user)
                <tr>
                    <td>{{ $user->user->name.' '.$user->lastname }}</td>
                    <td>{{ $user->user->employee->date_admission }}</td>
                    <td>
                        @if ($user->period_days==null)
                            0
                        @else 
                        {{$user->period_days}}
                        @endif
                    </td>
                    <td>
                        @if ($user->current_days==null)
                            0
                        @else
                        {{$user->current_days}}
                        @endif
                    </td>
                    <td>
                        @if ($user->dv==null)
                        0
                        @else 
                        {{ $user->dv}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


