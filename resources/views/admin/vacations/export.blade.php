
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Ingreso</th>
                <th>Empresa</th>
                <th>Periodo</th>
                <th>Días Actuales</th>
               <th>Al</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($vacations as $user)
                <tr>
                    <td>{{ $user->user->name.' '.$user->user->lastname }}</td>
                    <td>{{ $user->user->employee->date_admission->format('d-m-y') }}</td>
                    <td>
                        @foreach ($user->user->employee->companies as $company)
                            {{$company->name_company}},
                        @endforeach
                    </td>
                    <td>
                        @if($user->period == 1)
                            Periodo actual (Se esta generando)

                        @elseif($user->period == 2) 
                            Periodo viejito (Aún no caduca)
                        @else
                            Este periodo ya caduco
                        @endif

                    </td>
                    <td>
                        @if ($user->dv==null)
                            0
                        @else
                        {{$user->dv}}
                        @endif
                    </td>
                    <td>{{ now()->format('d-m-y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


