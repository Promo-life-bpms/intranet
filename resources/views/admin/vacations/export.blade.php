
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Fecha de Ingreso</th>
                <th>Empresa</th>
                <th>Dias Actuales</th>
               <th>AL</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($vacations as $user)
                <tr>
                    <td>{{ $user->user->name.' '.$user->lastname }}</td>
                    <td>{{ $user->user->employee->date_admission->format('d-m-y') }}</td>
                    <td>
                        @foreach ($user->user->employee->companies as $company)
                            {{$company->name_company}},
                        @endforeach
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


